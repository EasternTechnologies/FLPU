<?php
namespace App\Http\Controllers\Report;

use App\ArticleReports;
use App\Category;
use App\Http\Controllers\Controller;
use App\Models\VvtType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Report;

class UpdateController extends Controller
{
    protected $report = 0;

    public function __construct () {
        $this->middleware('auth');
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
        //$article->updateIndex();
        
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

				if($article->reports->types->slug != 'plannedexhibition') {
					$pathToFile  = Storage::disk( 'bsvt' )->getDriver()->getAdapter()->getPathPrefix();
					$whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
					Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);
				}

                $thumbnails  = 'article_images/' . 'th-' . $fileName;


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

}