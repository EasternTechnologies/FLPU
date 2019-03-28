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
use Illuminate\Support\Facades\Redis;

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

                $choose_array = unserialize(Redis::get('search:key'.$q));
                $articles = ArticleReports::whereIN('id',$choose_array)->get()->sortBy('title');
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

            $items = $report->articles()->where('report_id', $report->id )->orderBy('date_start', 'ASC')->paginate(10);
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



    public function role () {

        return Auth::user()->roles[0]->title;
    }
}