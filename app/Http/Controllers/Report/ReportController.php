<?php
namespace App\Http\Controllers\Report;

use App\ArticleReports;
use App\Category;
use App\Http\Controllers\Controller;
use App\Models\VvtType;
use App\ReportType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\Report;

class ReportController extends Controller
{
    protected $report = 0;

    public function __construct () {
        $this->middleware('auth');
    }


    public function report_list ( $slug ,Request $request) {

        $report_type = ReportType::where('slug' , $slug)->first();

        $count = 20;

        if( $this->role() == 'user' || $this->role() =='employee' ){

            $reports     = Report::where('type_id', $report_type->id )->active()->orderBy('date_start', 'desk')->paginate($count);

        } else {

            $reports     = Report::where('type_id', $report_type->id )->orderBy('date_start', 'desk')->paginate($count);
        }

        $page = $request->page;

        return view('report.index', compact('reports', 'report_type','page','count'));

    }

    public function report_show ( $slug ,  Report $report , $q = 0 ,  Request $request) {

        if($report->types->slug!=$slug) {
            return redirect('/')->with('status', 'Отчет не найден');
        }



        if (  $report->types->slug == 'weekly' || $report->types->slug == 'monthly' ) {

            $categories  = Category::where('report_type_id', $report->types->id)->get();

        } else {

            $categories  = Category::where('report_id', $report->id)->get();
        }

        $page = null;
        if($slug != 'plannedexhibition') {

            if(!$q) {
                $articles = $report->articles()->where('report_id', $report->id)->get();
            }
            else{
                $articles = ArticleReports::whereIN('id',$request->id)->get()->sortBy('title');
            }

            $subcategories = Subcategory::whereIn('id',array_unique($articles->pluck('subcategory_id')->toArray()))->
            select('id','title')->get();

            foreach ( $articles as $article ) {
                if($article->category_id)
                    foreach ( $categories as $category ) {
                        if ( $article->category_id == $category->id ) {
                            $subcategory = $article->subcategory_id != false ?
                                $subcategories->where('id',$article->subcategory_id)->first()->title: false; // problem
                            $items[ $category->title ][$subcategory] [] = $article;
                        }
                    }
                else {
                    $subcategory = $article->subcategory_id != false ? $subcategories->where('id',$article->subcategory_id)->first()->title: false;
                    $items[ false][$subcategory] [] = $article;
                }
            }

            $template = 'report.common_show';
        }
        else {

            $items = $report->articles()->where('report_id', $report->id )->paginate(10);
            $template = 'report.plannedexhibition.item';
            $page = $request->page;
        }

        return view($template, compact('report', 'items','page','subcategories','categories', 'q'));
    }

    public function item_article ( $slug,  ArticleReports $article ) {

        if($slug!=$article->reports->types->slug) {
            return redirect(route('show_report',['slug'=>$article->reports->types->slug,'report'=>$article->reports->id]))->with('status', 'Отчет не найден');
        }

        $arr = [];
        if ( $article->category_id != 0 ) {
            $arr[ 'category' ] = Category::find($article->category_id)->title;
        }
        if ( isset($article->subcategory_id) ) {
            $arr[ 'subcategory' ] = Subcategory::find($article->subcategory_id)->title;
        }

        return view('report.item_article', ['article' => $article]);
    }


    public function publish ( $slug, Report $report ) {

        foreach ( $report->articles as $article ) {
            $article->update(['status' => 2]);
        }

        $report->update(['status' => 2]);

        return redirect()->to('/report/'.$report->types->slug. '/show/'. $report->id)->with('status', 'Отчет опубликован');
    }


    public function article_publish ( $slug, ArticleReports $article ) {

        $article->update(['status' => 2]);

        $path = '/report/'. $article->reports->types->slug .'/show/' . $article->reports->id;
        return redirect()->to($path)->with('status', 'Материал утвержден');
    }

    public function upd_form_category ( $slug, Category $category ) {

        return view('report.upd_form_category', [
            'category'         => $category,
            'slug'=>$slug
        ]);
    }

    public function update_category ( $slug, Request $request, Category $category ) {

        $title = $request->input('title');
        $description = $request->input('editor1');

        $article           = Category::find($category->id);
        $article->title    = $title;

        if( isset($description) ){

            $article->description = $description;

        }

        $article->save();

        $path = '/report/'. $category->report->types->slug .'yearly/add2/' . $category->report->id;

        return redirect()->to($path)->with('status', 'Категория обновлена');
    }

    public function updreportform ( $slug, Report $report ) {

        return view('report.updreportform', ['report' => $report]);
    }

    public function updreport ( $slug, Request $request,Report $report ) {

        $report_slug = $report->types->slug;

        if($report_slug=='weekly' || $report_slug=='monthly'){

            $request->validate([
                'number' => 'required',
                'start_period' => 'required',
                'end_period' => 'required',
            ]);
            $report->number   = $request->input('number');
        }

        elseif($report_slug=='various') {
            $request->validate([
                'title' => 'required',
                'start_period' => 'required',
                'end_period' => 'required',
            ]);
            $report->title = $request->title;
        }

        else $request->validate([
            'start_period' => 'required',
            'end_period' => 'required',
        ]);

        $report->date_start = $request->input('start_period');
        $report->date_end    = $request->input('end_period');

        $report->save();

        $path = '/report/'. $report->types->slug;

        return redirect()->to($path)->with('status', 'Отчет отредактирован');
    }

    public function upd_form ( $slug, ArticleReports $article ) {
        $vvt_types_array = VvtType::find($article->companies->pluck('id'));
        $vvt_types = [];
        foreach($vvt_types_array as $vvt_type) {
            array_push($vvt_types, $vvt_type->id);
        }
        $tags                     = [];
        $tags [ 'companies' ]     = $article->companies->pluck('id');
        $tags [ 'countries' ]     = $article->countries->pluck('id');
        $tags [ 'vvt_types' ]     = $article->vvttypes->pluck('id');
        $tags [ 'personalities' ] = $article->personalities->pluck('id');
        $tags [ 'article' ]       = $article->id;
        $report = Report::find($article->report_id);

        return view('report.upd_form', compact('article', 'tags', 'report'));
    }

    public function update ( Request $request, $slug ='',$flag = null  ) {
        $this->validate($request, [
            'editor1' => 'required',
        ]);

        $place         = $request->input('place');
        $theme         = $request->input('title_1');
        $title         = $request->input('title');
        $body          = $request->input('editor1');
        $start_period  = $request->input('start_period');
        $end_period    = $request->input('end_period');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');
        $reset_img     = $request->input('reset_img');//++


        $article               = $request->input('article');
        $article               = ArticleReports::find($article);

        if(($end_period - $start_period) < 0) { //++
            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $article->title        = $title;
        $article->description  = $body;
        $article->date_start   = $start_period;
        $article->date_end     = $end_period;

        if(isset($theme) && isset($place)){

            $article->title        = $theme;
            $article->place        = $place;
        }

        $article->save();

        $article->countries()->sync($countries);
        $article->companies()->sync($companies);
        $article->personalities()->sync($personalities);
        $article->vvttypes()->sync($vvt_types);
        $article->updateIndex();

        $pics = $article->images()->get();//++
        foreach ( $pics as $pic ) {

            foreach ( $reset_img as $img ) {
                if("/images/".$pic->image == $img) {

                    Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    $article->images()->where('id',$pic->id)->delete();
                }
            }

        }

        if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

                $pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
                $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
                Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);

                $article->images()->create([
                    'image'     => $r,
                    'thumbnail' => $thumbnails,
                ]);
            }
        }

        $report = Report::find($article->report_id); //++

        if($flag == 1) { //++
            $article->update(['status' => 1]);
        } elseif($flag == 2) {
            $article->update(['status' => 2]);
        } else {
            $report->update(['status' => 0]);
            $article->update(['status' => 0]);
        }

        $path = '/report/'. $report->types->slug .'/add2/'. $article->report_id;

        return redirect()->to($path)->with('status', 'Статья обновлена!');
    }

    public function upd_form_subcategory ( $slug, Subcategory $subcategory ) {

        return view('report.upd_form_category', [
            'category'         => $subcategory,
        ]);
    }

    public function update_subcategory ( $slug, Request $request, Subcategory $subcategory ) { //++

        $title = $request->input('title');

        $article           = Subcategory::find($subcategory->id);
        $article->title    = $title;
        $article->save();

        $path = '/report/'. $subcategory->category->report->types->slug .'/add2/' . $subcategory->category->report->id;

        return redirect()->to($path)->with('status', 'Категория обновлена');
    }

    public function role () {

        return Auth::user()->roles[0]->title;
    }
}