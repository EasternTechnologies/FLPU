<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\analyst\various\Variousarticle;
use App\models\analyst\various\Variouscategory;
use App\models\analyst\various\Variousreport;
use App\models\analyst\various\Varioussubcategory;
use App\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VariousController extends Controller
{

    public function __construct () {
        $this->middleware('auth');

        $reports = Variousreport::all();//++
        foreach ( $reports as $report ) {

            if ( $report->published == 2 ) {

            }
            else {
                $count_all    = 0;
                $count_no_pub = 0;

                $articleses = $report->articles;

                foreach ( $articleses as $articl ) {
                    if ( $articl->published != 2 ) {
                        $count_no_pub++;
                    }
                    $count_all++;
                }

                //0 - Ожидает, 1 - Готов к публикации
                if ( $count_no_pub == 0 ) {
                    $report->update(['published' => 1]);
                }
                else {
                    $report->update(['published' => 0]);
                }

                //Нет материалов
                if ( $count_all == 0 ) {
                    $report->update(['published' => -1]);
                }

            }
        }
    }

    public function list () {

        $reporttitle = ReportType::find(7)->title;

	    if( $this->role() == 'user' || $this->role() =='employee' ){

		    $reports  = Variousreport::latest('start_date')->active()->paginate(20);

	    } else {

		    $reports  = Variousreport::latest('start_date')->paginate(20);

	    }

        return view('analyst.various.index', compact('reports', 'reporttitle'));

    }

    public function item ( Variousreport $variousreport ) {
        $reporttitle = ReportType::find(7)->title;
        $articles    = Variousreport::where('variousreports.id', $variousreport->id)
                                    ->with('categories', 'categories.subcategories.articles')
                                    ->get();
        if ( $articles->count() !== 0 ) {

            $items = $articles;
        }
        else {
            $items = [];
        }
        $month      = $variousreport->month;
        $year       = $variousreport->year;
        $week       = $variousreport->week;
        $start_date = $variousreport->start_date;
        $end_date   = $variousreport->end_date;

        return view('analyst.various.item', compact('variousreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories', 'start_date', 'end_date')//return view('weeklyreview.analyst.add_form_step2', compact('items', 'year', 'title')

        );
    }

    public function item_article ( Variousarticle $variousarticle, VariousReport $variousreport ) {
        $reporttitle = ReportType::find(7)->title;

        return view('analyst.various.item_article', [
          'article'       => $variousarticle,
          'reporttitle'   => $reporttitle,
          'variousreport' => $variousreport,
        ]);

    }

    public function delete_report ( Variousreport $variousreport ) {

        $reports = Variousreport::where('variousreports.id', $variousreport->id)
                                ->with('categories', 'categories.subcategories.articles')
                                ->get();
        if ( $reports->count() == 0 ) {
            $variousreport->delete();

            return redirect()->back()->with('status', 'Отчет удален');
        }

        foreach ( $reports as $report ) {

            if ( !empty($report->subcategory) ) {
                foreach ( $report->subcategory as $article ) {

                    $pics = $article->images()->get();
                    foreach ( $pics as $pic ) {
                        Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    }
                    $article->images()->delete();
                    $article->delete();
                }
                $report->delete();
            }
            if ( !empty($report->category) ) {
                foreach ( $report->category as $article ) {

                    $pics = $article->images()->get();
                    foreach ( $pics as $pic ) {
                        Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    }
                    $article->images()->delete();
                    $article->delete();
                }
                $report->delete();
            }

            $variousreport->delete();

        }

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function delete_article ( Variousarticle $variousarticle ) {
        $pics = $variousarticle->images()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $variousarticle->images()->delete();
        $variousarticle->companies()->detach();
        $variousarticle->countries()->detach();
        $variousarticle->vvttypes()->detach();
        $variousarticle->personalities()->detach();
        $variousarticle->delete();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_category ( Variouscategory $variouscategory ) {

        $category     = $variouscategory;
        $cat_articles = $variouscategory->articles;
        if ( isset($cat_articles) ) {
            foreach ( $cat_articles as $cat_article ) {
                $pics = $cat_article->images()->get();
                foreach ( $pics as $pic ) {
                    Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                }
                $cat_article->images()->delete();
                $cat_article->companies()->detach();
                $cat_article->countries()->detach();
                $cat_article->vvttypes()->detach();
                $cat_article->personalities()->detach();
                $cat_article->delete();
            }
        }
        if ( isset($category) ) {
            foreach ( $category->subcategories as $subcategory ) {
                foreach ( $subcategory->articles as $article ) {
                    $pics = $article->images()->get();
                    foreach ( $pics as $pic ) {
                        Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    }
                    $article->images()->delete();
                    $article->companies()->detach();
                    $article->countries()->detach();
                    $article->vvttypes()->detach();
                    $article->personalities()->detach();
                    $article->delete();
                }
                $subcategory->delete();

            }
            $category->delete();

        }

        return redirect()->back()->with('status', 'Раздел удален');
    }

    public function delete_subcategory ( Varioussubcategory $varioussubcategory ) {
        //dd($varioussubcategory);
        foreach ( $varioussubcategory->articles as $article ) {
            $pics = $article->images()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->images()->delete();
            $article->companies()->detach();
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach();
            $article->delete();
        }
        $varioussubcategory->delete();

        return redirect()->back()->with('status', 'Подраздел удален');

    }

    public function publish ( Variousreport $variousreport ) {
            //dd($variousreport->articles());
        foreach ( $variousreport->articles as $article ) {
            $article->update(['published' => 2]);
        }

        $variousreport->update(['published' => 2]);

        return redirect()->back()->with('status', 'Отчет опубликован');

    }

    public function article_for_approval ( Variousarticle $variousarticle ) {

        $variousarticle->update(['published' => 1]);

        return redirect()->back()->with('status', 'Материал на утверждении');
    }

    public function article_publish ( Variousarticle $variousarticle, $variousreport ) {

        $variousarticle->update(['published' => 2]);

        $path = '/analyst/various/show/' . $variousreport;

        return redirect()->back()->with('status', 'Материал утвержден');

    }

    public function create1form () {

        return view('analyst.various.add_form_step1');
    }

    public function create1 ( Request $request ) {
        $request->validate([
          'number' => 'required',
          'start_period' => 'required',
          'end_period' => 'required',
        ]);
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
        $number       = $request->input('number');

        $week     = date('W', $start_period);
        $week_end = date('W', $end_period);

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
        }//++

        $created_report = Variousreport::where([
          'year'       => $year,
          'month'      => $month,
          'number'     => $number,
          'week'       => $week,
          'start_date' => $start_period,
        ])->get();

        if ( $created_report->count() ) {

            return redirect()->route('home')->with('status', 'Такой отчет уже существует');

        }
        else {

            $report = new Variousreport();

            $report->start_date = $start_period;
            $report->end_date   = $end_period;
            $report->year       = $year;
            $report->month      = $month;
            $report->number     = $number;
            $report->week       = $week;
            $report->save();
            $path = '/analyst/various/add2/' . $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');

        }

    }

    public function updreportform ( Variousreport $variousreport ) {

        return view('analyst.various.updreportform', compact('variousreport'));
    }

    public function updreport ( Request $request, Variousreport $variousreport ) {
        $request->validate([
          'number' => 'required',
          'start_period' => 'required',
          'end_period' => 'required',
        ]);
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
        $number       = $request->input('number');

        $week     = date('W', $start_period);
        $week_end = date('W', $end_period);

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
        }//++

        $variousreport->start_date = $start_period;
        $variousreport->end_date   = $end_period;
        $variousreport->year       = $year;
        $variousreport->month      = $month;
        $variousreport->number     = $number;
        $variousreport->week       = $week;
        $variousreport->save();
        $path = '/analyst/various';

        return redirect()->to($path)->with('status', 'Отчет отредактирован');

    }

    public function createcategory ( Request $request ) {
        $this->validate($request, [
          'title' => 'required',
        ]);

        $title         = $request->input('title');
        $variousreport = $request->input('variousreport');
        $variousreport = Variousreport::find($variousreport);

        $created_category = $variousreport->categories()->create(['title' => $title]);
        $path             = '/analyst/various/add2/' . $variousreport->id;

        return redirect()->to($path)->with('status', 'Раздел создан');
    }

    public function createsubcategory ( Request $request ) {
        $this->validate($request, [
          'title' => 'required',
        ]);

        $title           = $request->input('title');
        $variousreport   = $request->input('variousreport');
        $variousreport   = Variousreport::find($variousreport);
        $variouscategory = $request->input('yearlycategory');
        $variouscategory = Variouscategory::find($variouscategory);
        //dd($yearlycategory);
        $created_category = $variouscategory->subcategories()->create(['title' => $title]);
        $path             = '/analyst/various/add2/' . $variousreport->id;

        return redirect()->to($path)->with('status', 'Раздел создан');
    }

    public function create2form ( Variousreport $variousreport ) {

        /*
         * формирование массива статей по категориям всем
         * */

        $reporttitle = ReportType::find(7)->title;
        $articles    = Variousreport::where('variousreports.id', $variousreport->id)
                                    ->with('categories', 'categories.subcategories.articles')
                                    ->get();

        if ( $articles->count() !== 0 ) {

            $items = $articles;
        }
        else {
            $items = [];
        }

        $month      = $variousreport->month;
        $year       = $variousreport->year;
        $week       = $variousreport->week;
        $start_date = $variousreport->start_date;
        $end_date   = $variousreport->end_date;

        return view('analyst.various.add_form_step2', compact('variousreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories'));

    }

    public function create3form ( Variousreport $variousreport, Variouscategory $variouscategory, $varioussubcategory = 0 ) {

        if ( $varioussubcategory != 0 ) {

            $varioussubcategory = Varioussubcategory::find($varioussubcategory);
        }

        return view('analyst.various.add_form_step3', compact('variousreport', 'variouscategory', 'varioussubcategory'));
    }

    public function create3 ( Request $request, $flag = NULL ) {

        $this->validate($request, [
          'editor1' => 'required',
          'title'   => 'required',
        ]);

        $title         = $request->input('title');
        $body          = $request->input('editor1');
        $start_period  = $request->input('start_period');
        $end_period    = $request->input('end_period');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');
        $year          = $request->input('year');
        $month         = $request->input('month');
        $week          = date('W', mktime(0, 1, 0, $month, $start_period, $year));
        $week_end      = date('W', mktime(0, 1, 0, $month, $end_period, $year));

        $variousreport_id = $request->input('variousreport');

//        if ( !empty($request->input('varioussubcategory')) ) {
//            $varioussubcategory = Varioussubcategory::find($request->input('varioussubcategory'));
//
//            $created_article = $varioussubcategory->articles()->create([
//              'title'        => $title,
//              'body'         => $body,
//              'start_period' => $start_period,
//              'end_period'   => $end_period,
//              'year'         => $year,
//              'month'        => $month,
//              'week'         => $week,
//
//              'variouscategory_id' => $request->input('variouscategory'),
//            ]);
//        }
//        else {
//            $variouscategory = Variouscategory::find($request->input('variouscategory'));
//            $created_article = $variouscategory->articles()->create([
//              'title'        => $title,
//              'body'         => $body,
//              'start_period' => $start_period,
//              'end_period'   => $end_period,
//              'year'         => $year,
//              'month'        => $month,
//              'week'         => $week,
//            ]);
//        }
	    $variousreport = Variousreport::find($request->input('variousreport'));
	    $created_article = $variousreport->articles()->create([
		    'title'        => $title,
		    'body'         => $body,
		    'start_period' => $start_period,
		    'end_period'   => $end_period,
		    'year'         => $year,
		    'month'        => $month,
		    'week'         => $week,
	    ]);
        $created_article->countries()->sync($countries);
        $created_article->companies()->sync($companies);
        $created_article->personalities()->sync($personalities);
        $created_article->vvttypes()->sync($vvt_types);

        if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

                $pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
                $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
                Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);

                $created_article->images()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }
        }

        if ( $flag == 1 ) { //++
            $created_article->update(['published' => 1]);
        }

        $path = '/analyst/various/add2/' . $variousreport_id;

        return redirect()->to($path)->with('status', 'Статья создана');
    }

    public function upd_form ( Variousarticle $variousarticle ) {
        if ( isset($variousarticle->subcategory) ) {
            $variousreport = Variousreport::find($variousarticle->subcategory->category->variousreport_id);

        }
        else {
            $variousreport = Variousreport::find($variousarticle->variousreport_id);

        }

        $tags                     = [];
        $tags [ 'companies' ]     = $variousarticle->companies->pluck('id');
        $tags [ 'countries' ]     = $variousarticle->countries->pluck('id')->toArray();
        $tags [ 'vvt_types' ]      = $variousarticle->vvttypes->pluck('id');
        $tags [ 'personalities' ] = $variousarticle->personalities->pluck('id');
	    $tags ['article']          = $variousarticle->id;
	    $array_class = explode('\\', get_class ($variousarticle));
	    $tags ['report'] = array_pop($array_class);

        return view('analyst.various.upd_form', compact('variousarticle', 'variousreport', 'tags'));

    }

    public function update ( Request $request, $flag = NULL ) {

        $this->validate($request, [
          'editor1' => 'required',
          'title'   => 'required',
        ]);

        $title         = $request->input('title');
        $body          = $request->input('editor1');
        $start_period  = $request->input('start_period');
        $end_period    = $request->input('end_period');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');
        $year          = $request->input('year');
        $month         = $request->input('month');
        $week          = date('W', mktime(0, 1, 0, $month, $start_period, $year));

        $variousareport_id = $request->input('variousreport');
        $reset_img         = $request->input('reset_img');

        $variousarticle        = $request->input('variousarticle');
        $article               = Variousarticle::find($variousarticle);
        $article->title        = $title;
        $article->body         = $body;
        $article->start_period = $start_period;
        $article->end_period   = $end_period;
        $article->year         = $year;
        $article->month        = $month;
        $article->week         = $week;
        $article->save();

        $article->countries()->sync($countries);
        $article->companies()->sync($companies);
        $article->personalities()->sync($personalities);
        $article->vvttypes()->sync($vvt_types);

        $pics = $article->images()->get();//++
        foreach ( $pics as $pic ) {

            foreach ( $reset_img as $img ) {
                if ( "/images/" . $pic->image == $img ) {

                    Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    $article->images()->where('id', $pic->id)->delete();

                }
            }

        }//++

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

        $report = Variousreport::find($variousareport_id); //++

        if ( $flag == 1 ) { //++
            $article->update(['published' => 1]);
        }
        elseif ( $flag == 2 ) {
            $article->update(['published' => 2]);
        }
        else {
            $report->update(['published' => 0]);
            $article->update(['published' => 0]);

        }//++

        $path = '/analyst/various/add2/' . $variousareport_id;

        return redirect()->to($path)->with('status', 'Статья обновлена!');
    }

	public function role () {

		return Auth::user()->roles[0]->title;

	}
}
