<?php

namespace App\Http\Controllers\Report;

use App\ArticleReports;
use App\Category;
use App\Http\Controllers\Controller;
use App\Report;
use App\ReportType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ReportController extends Controller
{

    protected $report = 0;

    public function __construct () {
        $this->middleware('auth');
    }

    public function report_list ( $slug, Request $request ) {

        $report_type = ReportType::where('slug', $slug)->first();

        $count = 20;

        if ( $this->role() == 'user' || $this->role() == 'employee' ) {

            $reports = Report::where('type_id', $report_type->id)
                             ->active()
                             ->orderBy('date_start', 'desk')
                             ->paginate($count);

        }
        else {

            $reports = Report::where('type_id', $report_type->id)->orderBy('date_start', 'desk')->paginate($count);
        }

        $page = $request->page;

        return view('report.index', compact('reports', 'report_type', 'page', 'count'));

    }

    public function role () {

        return Auth::user()->roles[ 0 ]->title;
    }

    public function report_show ( $slug, Report $report, $q = 0, Request $request ) {

        if ( $slug == 'countrycatalog' and Auth::user()->roles()->first()->title == 'user' ) {
            return redirect()->to('/')->with('status', 'Доступ закрыт');
        }
        if ( $slug == 'yearly' and Auth::user()->roles()->first()->title == 'user' ) {
            return redirect()->to('/')->with('status', 'Доступ закрыт');
        }
        if ( $report->types->slug != $slug ) {
            return redirect('/')->with('status', 'Отчет не найден');
        }

        if ( $report->types->slug == 'weekly' || $report->types->slug == 'monthly' ) {

            $categories = Category::where('report_type_id', $report->types->id)->get();

        }
        else {

            $categories = Category::where('report_id', $report->id)->get();
        }

        $page = NULL;
        if ( $slug != 'plannedexhibition' ) {

            if ( !$q ) {
                $articles = $report->articles()->where('report_id', $report->id)->get();
            }
            else {

                $choose_array = unserialize(Redis::get('search:key' . $q));
                $articles     = ArticleReports::whereIN('id', $choose_array)->get()->sortBy('title');
            }

            $subcategories = Subcategory::whereIn('id', array_unique($articles->pluck('subcategory_id')->toArray()))
                                        ->select('id', 'title')
                                        ->get();

            foreach ( $articles as $article ) {
                if ( $article->category_id ) {
                    foreach ( $categories as $category ) {
                        if ( $article->category_id == $category->id ) {
                            $subcategory                                  = $article->subcategory_id != FALSE ? $subcategories->where('id', $article->subcategory_id)
                                                                                                                              ->first()->title : FALSE; // problem
                            $items[ $category->title ][ $subcategory ] [] = $article;
                        }
                    }
                }
                else {
                    $subcategory                       = $article->subcategory_id != FALSE ? $subcategories->where('id', $article->subcategory_id)
                                                                                                           ->first()->title : FALSE;
                    $items[ FALSE ][ $subcategory ] [] = $article;
                }
            }

            $template = 'report.common_show';
        }
        else {

            $items    = $report->articles()
                               ->where('report_id', $report->id)
                               ->orderBy('date_start', 'ASC')
                               ->paginate(10);
            $template = 'report.plannedexhibition.item';
            $page     = $request->page;
        }


        return view($template, compact('report', 'items', 'page', 'subcategories', 'categories', 'q'));
    }

    public function item_article ( $slug, ArticleReports $article, Request $request ) {
        if ( ( $request->get('q') ) != NULL ) {
            $q = $request->get('q');

        }
        if ( ( $request->get('needles') ) != NULL ) {
            //$needles = explode(';', urldecode($request->get('needles')));
            $patterns=$request->session()->get('patterns');
            $replacements=$request->session()->get('replacements');
            /*foreach ( $needles as $needle ) {
                $replacements[] = "<b class=\"highlight\">$needle</b>";
                //$patterns[]     = "~($needle)~";
            }*/

        }
        //dd($patterns,$replacements);
        if ( $slug == 'countrycatalog' and Auth::user()->roles()->first()->title == 'user' ) {
            return redirect()->to('/')->with('status', 'Доступ закрыт');
        }
        if ( $slug == 'yearly' and Auth::user()->roles()->first()->title == 'user' ) {
            return redirect()->to('/')->with('status', 'Доступ закрыт');
        }
        if ( $slug != $article->reports->types->slug ) {
            return redirect(route('show_report', [
              'slug'   => $article->reports->types->slug,
              'report' => $article->reports->id,
            ]))->with('status', 'Отчет не найден');
        }

        $arr = [];
        if ( $article->category_id != 0 ) {
            $arr[ 'category' ] = Category::find($article->category_id)->title;
        }
        if ( isset($article->subcategory_id) ) {
            $arr[ 'subcategory' ] = Subcategory::find($article->subcategory_id)->title;
        }

        return view('report.item_article', compact('article', 'replacements', 'patterns', 'q', 'needles'));
    }
}