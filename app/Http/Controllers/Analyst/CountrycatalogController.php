<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\analyst\yearly\Countrycatalog;
use App\models\analyst\yearly\InfoCountry;
use App\models\analyst\yearly\Region;
use App\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CountrycatalogController extends Controller
{

    public function __construct () {
        $this->middleware('auth');

        $reports = Countrycatalog::all();//++

        foreach ( $reports as $report ) {

            if ( $report->published == 2 ) {

            }
            else {
                $count_all    = 0;
                $count_no_pub = 0;
                $reg_no_pub   = 0;

                foreach ( $report->regions as $region ) {
                    $articleses = $region->countries()->get();

                    if ( $region->published != 2 ) {
                        $count_no_pub++;
                    }

                    $count_all++;

                    foreach ( $articleses as $articl ) {

                        if ( $articl->published != 2 ) {
                            $count_no_pub++;
                        }

                        $count_all++;
                    }

                    if ( $count_no_pub != 0 ) {
                        $reg_no_pub++;
                    }

                }

                //0 - Ожидает, 1 - Отчет готов к публикации
                if ( $reg_no_pub == 0 && $count_all != 0 ) {
                    $report->update(['published' => 1]);
                }
                elseif ( $count_all == 0 ) {
                    $report->update(['published' => -1]);
                }
                else {
                    $report->update(['published' => 0]);
                }

            }
        }//++
    }

    public function yearly_list () {

	    $reporttitle = ReportType::find(3)->title;

	    if ( $this->role() == 'user' || $this->role() == 'employee' ) {

	    $reports = Countrycatalog::latest( 'start_date' )->active()->paginate(20);

         } else {

		 $reports = Countrycatalog::latest( 'start_date' )->paginate(20);

	    }

        return view('analyst.countrycatalog.index', compact('reports', 'reporttitle'));
    }


    public function yearly_item ( Countrycatalog $countrycatalog ) {

        $title    = ReportType::find(3)->title;
        $articles = Region::join('countrycatalogs', 'countrycatalogs.id', '=', 'regions.countrycatalog_id')
                          ->where('countrycatalogs.id', $countrycatalog->id)
                          ->with('countries')
                          ->select('regions.*')
                          ->get();

        if ( $articles->count() !== 0 ) {

            $items = $articles;
        }
        else {
            $items = [];
        }

        $month      = $countrycatalog->month;
        $year       = $countrycatalog->year;
        $week       = $countrycatalog->week;
        $start_date = $countrycatalog->start_date;
        $end_date   = $countrycatalog->end_date;

        return view('analyst.countrycatalog.item', compact('countrycatalog', 'items', 'year', 'week', 'title', 'month', 'categories', 'start_date', 'end_date'));
    }


    public function yearly_item_article ( $infocountry, $countrycatalog = null ) {

        $reporttitle = ReportType::find(3)->title;

        $infocountry = InfoCountry::with('region')->where('id', $infocountry)->first();

		if(!$countrycatalog) {

			$countrycatalog = $infocountry->region->countrycatalog_id;

		}

        return view('analyst.countrycatalog.item_article', [
          'infocountry'       => $infocountry,
          'reporttitle'       => $reporttitle,
          'countrycatalog_id' => $countrycatalog,
        ]);

    }

    public function publish ( Countrycatalog $countrycatalog ) {

        foreach ( $countrycatalog->regions as $region ) {
            foreach ( $region->countries as $country ) {

                $country->update(['published' => 2]);

            }
            $region->update(['published' => 2]);
        }
        $countrycatalog->update(['published' => 2]);

        return redirect()->to('/analyst/countrycatalog')->with('status', 'Отчет опубликован');

    }

    public function article_for_approval ( InfoCountry $infocountry ) {

        $infocountry->update(['published' => 1]);

        return redirect()->back()->with('status', 'Материал на утверждении');
    }

    public function article_publish ( InfoCountry $infocountry ) {

        $infocountry->update(['published' => 2]);

        return redirect()->back()->with('status', 'Материал утвержден');

    }

    public function create1form () {

	    $reporttitle = ReportType::find(3)->title;

        return view('analyst.countrycatalog.add_form_step1', compact('reporttitle'));
    }

    public function create1 ( Request $request ) {

        $request->validate([
          //'number'       => 'required',
          'start_period' => 'required',
          'end_period'   => 'required',
        ]);

        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
        //$number       = $request->input('number');
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
        }//++

        $created_report = Countrycatalog::where([
          'year'       => $year,
          //'number'     => $number,
          'month'      => $month,
          'week'       => $week,
          'start_date' => $start_period,
        ])->get();
        if ( $created_report->count() ) {

            $path = '/analyst/countrycatalog/'; //++

            return redirect()->to($path)->with('status', 'Такой отчет уже существует');

        }
        else {

            $report = new Countrycatalog();

            $report->start_date = $start_period;
            $report->end_date   = $end_period;
           // $report->number     = $number;
            $report->year       = $year;
            $report->month      = $month;
            $report->week       = $week;
            $report->save();
            $path = '/analyst/countrycatalog/add2/' . $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');

        }

    }

    public function updreportform ( Countrycatalog $countrycatalog ) {

	    $reporttitle = ReportType::find(3)->title;

        return view('analyst.countrycatalog.updreportform', compact('countrycatalog', 'reporttitle'));
    }

    public function updreport ( Countrycatalog $countrycatalog, Request $request ) {

        $request->validate([
          //'number'       => 'required',
          'start_period' => 'required',
          'end_period'   => 'required',
        ]);
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
        //$number       = $request->input('number');
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
        }//++

        $countrycatalog->start_date = $start_period;
        $countrycatalog->end_date   = $end_period;
       // $countrycatalog->number     = $number;
        $countrycatalog->year       = $year;
        $countrycatalog->month      = $month;
        $countrycatalog->week       = $week;
        $countrycatalog->save();
        $path = '/analyst/countrycatalog';

        return redirect()->to($path)->with('status', 'Отчет обновлен');

    }

    public function createregionform ( Countrycatalog $countrycatalog ) {

	    $reporttitle = ReportType::find(3)->title;

        return view('analyst.countrycatalog.add_region_form', compact('countrycatalog', 'reporttitle'));

    }

    public function createregion ( Request $request, $flag = NULL ) { //++
        $this->validate($request, [
          'editor1' => 'required',
          'title'   => 'required',
        ]);
        $title          = $request->input('title');
        $overview       = $request->input('editor1');
        $start_period   = $request->input('start_period');
        $end_period     = $request->input('end_period');
        $countrycatalog = $request->input('countrycatalog');
        $countrycatalog = Countrycatalog::find($countrycatalog);

        if ( $flag == 1 ) {
            $created_region = Region::create(['title' => $title, 'overview' => $overview, 'published' => 1]);
        }
        else {
            $created_region = Region::create(['title' => $title, 'overview' => $overview]);
        }
        $created_region->countrycatalog()->associate($countrycatalog);
        $created_region->save();

        $path = 'analyst/countrycatalog/add2/' . $countrycatalog->id;

        return redirect()->to($path)->with('status', 'Регион создан');
    }

    public function create2form ( Countrycatalog $countrycatalog ) {

        $reporttitle = ReportType::find(3)->title;
        //$regions = Region::all();
        $articles = Region::join('countrycatalogs', 'countrycatalogs.id', '=', 'regions.countrycatalog_id')
                          ->where('countrycatalogs.id', $countrycatalog->id)
                          ->with('countries')
                          ->select('regions.*')
                          ->get();

        if ( $articles->count() !== 0 ) {

            $items = $articles;
        }
        else {
            $items = [];
        }
        $month      = $countrycatalog->month;
        $year       = $countrycatalog->year;
        $week       = $countrycatalog->week;
        $start_date = $countrycatalog->start_date;
        $end_date   = $countrycatalog->end_date;

        return view('analyst.countrycatalog.add_form_step2', compact('countrycatalog', 'items', 'year', 'week', 'reporttitle', 'month', 'categories')

        );
    }

    public function create3form ( Region $region, Countrycatalog $countrycatalog ) {

	    $reporttitle = ReportType::find(3)->title;

        return view('analyst.countrycatalog.add_form_step3', compact('region', 'countrycatalog' , 'reporttitle'));
    }

    public function create3 ( Request $request, $flag = NULL ) { //++

        $this->validate($request, [
          'editor1' => 'required',
          'title'   => 'required',
        ]);

        $title = $request->input('title');
        /*$general_data          = $request->input('general_data');
        $expenses          = $request->input('expenses');
        $situation          = $request->input('situation');
        $military_structure          = $request->input('military_structure');
        $major_types_vvt          = $request->input('major_types_vvt');
        $military_indastry          = $request->input('military_indastry');
        $military_technical_cooperation          = $request->input('military_technical_cooperation');*/
        $overview      = $request->input('editor1');
        $start_period  = $request->input('start_period');
        $end_period    = $request->input('end_period');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');

        $countrycatalog = $request->input('countrycatalog');
        $region         = $request->input('region');
        $countrycatalog = Countrycatalog::find($countrycatalog);
        $year           = $request->input('year');
        $month          = $request->input('month');
        $week           = date('W', mktime(0, 1, 0, $month, $start_period, $year));
        $week_end       = date('W', mktime(0, 1, 0, $month, $end_period, $year));
        /*if ( $week != $week_end ) {
            return redirect()->refresh()->with('status', 'Выберите промежуток в рамках одной недели');
            die();
        }*/

        $infocountry = InfoCountry::create([
          'title'        => $title,
          'overview'     => $overview,
          'start_period' => $start_period,
          'end_period'   => $end_period,
        ]);

        $region = Region::find($region);
        //dd($infocountry);
        $infocountry->region()->associate($region);
        $infocountry->save();
        $infocountry->countries()->sync($countries);
        $infocountry->companies()->sync($companies);
        $infocountry->personalities()->sync($personalities);
        $infocountry->vvttypes()->sync($vvt_types);

        if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

                $pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
                $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
                Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);

                $infocountry->images()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }

        if ( $flag == 1 ) {
	        $countrycatalog->update(['published' => 0]);
            $infocountry->update(['published' => 1]);
        }  elseif ( $flag == 2 ) {

	        $infocountry->update(['published' => 2]);
        }
        else {
	        $countrycatalog->update(['published' => 0]);
	        $infocountry->update(['published' => 0]);

        }

        $path = '/analyst/countrycatalog/add2/' . $request->input('countrycatalog');

        return redirect()->to($path)->with('status', 'Статья создана');
    }

    public function delete_article ( InfoCountry $infocountry ) {

        $pics = $infocountry->images()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $infocountry->images()->delete();
        $infocountry->companies()->detach();
        $infocountry->countries()->detach();
        $infocountry->vvttypes()->detach();
        $infocountry->personalities()->detach();
        $infocountry->delete();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_region ( Region $region ) {

        if ( !empty($region->countries) ) {
            foreach ( $region->countries as $country ) {

                $pics = $country->images()->get();
                foreach ( $pics as $pic ) {
                    Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                }
                $country->images()->delete();
                $country->companies()->detach();
                $country->countries()->detach();
                $country->vvttypes()->detach();
                $country->personalities()->detach();
                $country->delete();
            }
            $region->delete();
        }

        return redirect()->back()->with('status', 'Регион удален');

    }

    public function upd_form_region ( Region $region, $countrycatalog ) {//++

        return view('analyst.countrycatalog.upd_form_region', [
          'region'         => $region,
          'countrycatalog' => $countrycatalog,
        ]);
    }

    public function update_region ( Request $request, Region $region, Countrycatalog $countrycatalog, $flag = NULL ) { //++

        $title = $request->input('title');
        $body  = $request->input('editor1');

        $article           = Region::find($region->id);
        $article->title    = $title;
        $article->overview = $body;
        $article->save();

        if ( $flag == 1 ) { //++
            $article->update(['published' => 1]);
        }
        elseif ( $flag == 2 ) {
            $article->update(['published' => 2]);
        }
        else {
            $countrycatalog->update(['published' => 0]);
            $article->update(['published' => 0]);

        }//++

        $path = '/analyst/countrycatalog/add2/' . $countrycatalog->id;

        return redirect()->to($path)->with('status', 'Регион обновлен');
    }

    public function delete_countrycatalog ( Countrycatalog $countrycatalog ) {

        $articles = Region::join('countrycatalogs', 'countrycatalogs.id', '=', 'regions.countrycatalog_id')
                          ->where('countrycatalogs.id', $countrycatalog->id)
                          ->with('countries')
                          ->select('regions.*')
                          ->get();
        if ( $articles->count() == 0 ) {
            $countrycatalog->delete();

            return redirect()->back()->with('status', 'Отчет удален');
        }

        foreach ( $articles as $region ) {

            if ( !empty($region->countries) ) {
                foreach ( $region->countries as $country ) {

                    $pics = $country->images()->get();
                    foreach ( $pics as $pic ) {
                        Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    }
                    $country->images()->delete();
                    $country->delete();
                }
                $region->delete();
            }

            $countrycatalog->delete();
        }

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function upd_form ( InfoCountry $infocountry, Countrycatalog $countrycatalog ) { //+
        $tags                     = [];
        $tags [ 'companies' ]     = $infocountry->companies->pluck('id');
        $tags [ 'countries' ]     = $infocountry->countries->pluck('id')->toArray();
        $tags [ 'vvttypes' ]      = $infocountry->vvttypes->pluck('id');
        $tags [ 'personalities' ] = $infocountry->personalities->pluck('id');
	    $tags ['article']          = $infocountry->id;
	    $array_class = explode('\\', get_class ($infocountry));
	    $tags ['report'] = array_pop($array_class);

        return view('analyst.countrycatalog.upd_form', compact('tags', 'infocountry', 'countrycatalog'));

    }

    public function update ( Request $request, $flag = NULL ) { //++

        $this->validate($request, [
          'editor1' => 'required',
          'title'   => 'required',
        ]);
        /*$general_data          = $request->input('general_data');
                $expenses          = $request->input('expenses');
                $situation          = $request->input('situation');
                $military_structure          = $request->input('military_structure');
                $major_types_vvt          = $request->input('major_types_vvt');
                $military_indastry          = $request->input('military_indastry');
                $military_technical_cooperation          = $request->input('military_technical_cooperation');*/

        $title         = $request->input('title');
        $body          = $request->input('editor1');
        $start_period  = $request->input('start_period');
        $end_period    = $request->input('end_period');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');
        $report_id     = $request->input('infocountry_report');
	    $reset_img     = $request->input('reset_img');

        $year     = $request->input('year');
        $month    = $request->input('month');
        $week     = date('W', mktime(0, 1, 0, $month, $start_period, $year));
        $week_end = date('W', mktime(0, 1, 0, $month, $end_period, $year));

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $article             = $request->input('infocountry');
        $article             = InfoCountry::find($article);
        $article->title      = $title;
        $article->overview   = $body;
        $article->start_date = $start_period;
        $article->end_date   = $end_period;
        $article->save();

        $article->countries()->sync($countries);
        $article->companies()->sync($companies);
        $article->personalities()->sync($personalities);
        $article->vvttypes()->sync($vvt_types);

        $pics = $article->images()->get(); //++
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

        $report = Countrycatalog::where('id', $report_id); //++

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

        $path = '/analyst/countrycatalog/add2/' . $request->input('infocountry_report');

        return redirect()->to($path)->with('status', 'Статья отредактирована');
    }

	public function role () {

		return Auth::user()->roles[0]->title;

	}

}
