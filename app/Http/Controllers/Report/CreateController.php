<?php
namespace App\Http\Controllers\Report;

use App\ArticleReports;
use App\Category;
use App\Http\Controllers\Controller;
use App\ReportType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Report;
//test
class CreateController extends Controller
{
    protected $report = 0;

    public function __construct () {
        $this->middleware('auth');
    }

    public function report_add_form ( $slug ) {

        $report_type = ReportType::where('slug' , $slug)->first();
        return view('report.add_form_step_1', compact( 'report_type'));
    }

    public function report_add ( $slug , Request $request ) {

        $report_type = ReportType::where('slug' , $slug)->first();

        if($slug=='weekly' || $slug=='monthly'){
            $request->validate([
                'number' => 'required',
                'date_start' => 'required',
                'date_end' => 'required',
            ]);
        }

        elseif($slug=='various') {
            $request->validate([
                'title' => 'required',
                'date_start' => 'required',
                'date_end' => 'required',
            ]);
        }

        else $request->validate([
            'date_start' => 'required',
            'date_end' => 'required',
        ]);

        $date_start = $request->input('date_start');
        $date_end   = $request->input('date_end');
        $number   = $request->input('number');
        $title = $request->title;

        if(($date_end - $date_start) < 0) {
            return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
        }

        $created_report = Report::where([
            'number'       => $number,
            'date_start'   => $date_start,
            'date_end'     => $date_end,
            'type_id'      => $report_type->id,
            'title'=>$title
        ])->get();

        if ( $created_report->count() ) {
            $path = '/report/'. $report_type->slug;

            return redirect()->to($path)->with('status', 'Такой отчет уже существует');
        }
        else {

            $report = new Report();

            $report->date_start = $date_start;
            $report->date_end   = $date_end;
            $report->number     = $number;
            $report->type_id    = $report_type->id;
            $report->title      = $title;
            $report->save();

            $path = '/report/'. $report_type->slug .'/add2/'. $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');
        }

    }

    public function report_step_2 ($slug, Report $report) {

        $count_all = 0;
        $count_no_pub = 0;
        $articles = $report->articles()->get();

        foreach ($articles as $article) {
            if($article->status != 2) $count_no_pub++;
            $count_all++;
        }

        //0 - Ожидает, 1 - Готов к публикации
        if($count_no_pub == 0) {
            $report->update(['status' => 2]);
        } else {
            $report->update(['status' => 0]);
        }

        //Нет материалов
        if($count_all == 0 ) {
            $report->update(['status' => -1]);
        }

        if ($report->types->slug == 'weekly' || $report->types->slug == 'monthly' ) {
            $categories  = Category::where('report_type_id', $report->types->id);
            $categories_id = $categories->pluck('id')->toArray();
            $categories = $categories->get();
        } else {
            $categories  = Category::where('report_id', $report->id);
            $categories_id = $categories->pluck('id')->toArray();
            $categories = $categories->get();
        }

        $articles = $report->articles()->where('report_id', $report->id )->get();
        $subcategories = [];

        $subcategories_array = Subcategory::whereIn('category_id',$categories_id)->get();

        $items = [];
        $items[false]  = [];

        foreach ($categories as $category) {
            $items[$category->id] = [];
            $items[$category->id][false] = [];
        }

        foreach ($subcategories_array as $subcategory)
        {
            $items[$subcategory->category_id][$subcategory->id] = [];
            $subcategories[$subcategory->category_id][$subcategory->id] = $subcategory->title;
        }

        foreach ($articles as $key => $article) {
            if ($article->category_id) {
                $subcategory = $article->subcategory_id != false ? $article->subcategory_id : false;
                $category = $article->category_id != false ? $article->category_id : false;
                $items[$category][$subcategory][] = $articles->pull($key);
            }
        }

        foreach ($articles as $key => $article) {
            $subcategory = $article->subcategory_id != false ?  $article->subcategory_id: false;
            $items[false][$subcategory][] = $article;
        }

        return view('report.add_form_step_2', compact('report', 'items', 'categories','subcategories'));
    }

    public function report_step_3 ( $slug, Report $report, Category $category, Subcategory $subcategory) {

        return view('report.add_form_step_3', compact('category', 'report', 'subcategory'));
    }

    public function create3 ( Request $request, $flag = null ) {

        $this->validate($request, [
            'editor1' => 'required',
        ]);

        $title         = $request->input('title');
        $place         = $request->input('place');
        $theme         = $request->input('title_1');
        $description   = $request->input('editor1');
        $date_start    = $request->input('start_period');
        $date_end      = $request->input('end_period');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');
        $category      = $request->input('category');
        $subcategory   = $request->input('subcategory');

        $report = $request->input('report');
        $report = Report::find($report);



        if(($date_end - $date_start) < 0) { //++
            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $article               = new ArticleReports();
        $article->title        = $title;
        $article->description  = $description;

        if(isset($theme) && isset($place)){

            $article->title        = $theme;
            $article->place        = $place;

        }

        $article->date_start   = $date_start;
        $article->date_end     = $date_end;
        $report->articles()->save($article);

        $article = ArticleReports::all()->last();

        if(isset($subcategory)){

            $subcategory = Subcategory::find($subcategory);
            $article->subcategory_id = $subcategory->id;
            $category = Category::find($category);
            $article->category_id = $category->id;

        } else {
            $category = Category::find($category);
            if(isset($category)) {
                $article->category_id = $category->id;
            }
        }

        $article->save();
        $article->companies()->sync($companies);
        $article->personalities()->sync($personalities);
        $article->vvttypes()->sync($vvt_types);
        $article->countries()->sync($countries);
        $article->addToIndex();


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

        if($flag == 1) {
            $article->update(['status' => 1]);
        }

        $path = '/report/'. $report->types->slug .'/add2/'. $report->id;

        return redirect()->to($path)->with('status', 'Статья создана');
    }


    public function createcategoryform ( $slug,  Report $report ) {

        return view('report.add_category_form', ['report' => $report]);
    }

    public function createcategory ( $slug, Request $request, $flag = NULL ) {

        if ( $slug == 'countrycatalog' ) {

            $this->validate( $request, [
                'editor1' => 'required',
                'title'   => 'required',
            ] );

        } else {

            $this->validate( $request, [
                'title'   => 'required',
            ]);
        }

        $title          = $request->input('title');
        $description      = $request->input('editor1');
        $report = $request->input('report');
        $report = Report::find($report);

        $created_category = new Category();
        if ( $flag == 1 ) {
            $created_category->title = $title;
            $created_category->description = $description;
            $created_category->status = 1;
        }
        else {
            $created_category->title = $title;
            $created_category->description = $description;
        }
        $created_category->report_type()->associate($report->types);
        $created_category->report()->associate($report);
        $created_category->save();

        $path = 'report/'.$report->types->slug.'/add2/' . $report->id;

        return redirect()->to($path)->with('status', 'Отчет обновлен');
    }

    public function createsubcategory ( Request $request ) {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $title          = $request->input('title');
        $report   = $request->input('report');
        $report   = Report::find($report);
        $category = $request->input('category');
        $category = Category::find($category);

        $category->subcategories()->create(['title' => $title]);
        $path             = '/report/'. $report->types->slug .'/add2/' . $report->id;

        return redirect()->to($path)->with('status', 'Раздел создан');
    }

}