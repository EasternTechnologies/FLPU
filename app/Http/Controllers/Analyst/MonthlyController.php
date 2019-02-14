<?php

namespace App\Http\Controllers\Analyst;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\analyst\monthly\Monthlyarticle;
use App\models\analyst\monthly\Monthlyreport;
use App\ReportType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MonthlyController extends Controller
{

    public function __construct () {
        $this->middleware('auth');

        $reports = Monthlyreport::all();//++
        foreach ( $reports as $report ) {

            if ( $report->published == 2 ) {

            }
            else {
                $count_all    = 0;
                $count_no_pub = 0;
                $articleses   = $report->articles()->get();

                foreach ( $articleses as $articl ) {
                    if ( $articl->published != 2 )
                        $count_no_pub++;
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
        }//++
    }

    public function monthly_list ( ) {

	    $reporttitle = ReportType::find(2)->title;

	    if( $this->role() == 'user' || $this->role() =='employee' ) {

		    $reports = Monthlyreport::latest( 'start_date' )->active()->paginate( 20 );

	    } else {

		    $reports = Monthlyreport::latest( 'start_date' )->paginate( 20 );

	    }

        return view('analyst.monthlyreview.index', compact('reports', 'reporttitle'));
    }


    public function monthly_item ( Monthlyreport $monthlyreport ) {

        $reporttitle = ReportType::find(2)->title;
        $year        = $monthlyreport->year;
        $month       = $monthlyreport->month;

        $articles = $monthlyreport->articles()->with(['category', 'subcategory'])->get();

        if ( $articles->count() !== 0 ) {

            foreach ( $articles as $index => $article ) {

                $category                           = $article->category != NULL ? $article->category->title : 'false';
                $subcategory                        = $article->subcategory != NULL ? $article->subcategory->title : 'false';
                $items[ $category ][ $subcategory ][] = $article;

            }
        } else {

            $items = [];

        }

        return view('analyst.monthlyreview.item', compact( 'items','year', 'month', 'reporttitle', 'monthlyreport'));
    }


    public function monthly_item_article ( Monthlyarticle $monthlyarticle ) {
        $reporttitle = ReportType::find(2)->title;

        $article          = $monthlyarticle;
        $arr[ 'article' ] = $article;

        if ( isset($article->category_id) ) {
            $arr[ 'category' ] = Category::find($article->category_id)->title;
        }
        if ( isset($article->subcategory_id) ) {
            $arr[ 'subcategory' ] = Subcategory::find($article->subcategory_id)->title;
        }

        return view('analyst.monthlyreview.item_article', ['arr' => $arr, 'reporttitle' => $reporttitle]);

    }

    public function delete_montlyreport ( Monthlyreport $monthlyreport ) {
        $articles = $monthlyreport->articles()->with('monthlyimages')->get();

        foreach ( $articles as $article ) {
            $pics = $article->monthlyimages()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->monthlyimages()->delete();

            $article->companies()->detach(); //++
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach(); //++

            $article->delete();
        }
        $monthlyreport->delete();

        return redirect()->back()->with('status', "Отчет  удален");
    }

    public function publish ( Monthlyreport $monthlyreport ) {

        foreach ( $monthlyreport->articles as $article  ) {
            $article->update(['published' => 2]);
        }


        $monthlyreport->update(['published' => 2]);

        return redirect()->to('/analyst/monthly')->with('status', 'Отчет опубликован');

    }

    public function article_for_approval ( Monthlyarticle $monthlyarticle ) {

        $monthlyarticle->update(['published' => 1]);

        return redirect()->back()->with('status', 'Материал на утверждении');
    }

    public function article_publish ( Monthlyarticle $monthlyarticle ) {

        $monthlyarticle->update(['published' => 2]);

        $path = '/analyst/monthly/show/' . $monthlyarticle->monthlyreport->id;

        return redirect()->to($path)->with('status', 'Материал утвержден');

    }

    public function create1form () {

        return view('analyst.monthlyreview.add_form_step1');
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
        $week         = '';

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $created_report = Monthlyreport::where([
          'year'       => $year,
          'month'      => $month,
          'number'     => $number,
          'start_date' => $start_period,
        ])->get();

        if ( $created_report->count() ) {
            $path = '/analyst/monthly/'; //++

            return redirect()->to($path)->with('status', 'Такой отчет уже существует');//++

        }
        else {

            $report = new Monthlyreport();

            $report->start_date = $start_period;
            $report->end_date   = $end_period;
            $report->year       = $year;
            $report->month      = $month;
            $report->number     = $number;
            $report->save();
            $path = '/analyst/monthly/add2/' . $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');

        }

    }

    public function updreportform ( Monthlyreport $monthlyreport ) {

        return view('analyst.monthlyreview.updreportform', compact('monthlyreport'));
    }

    public function updreport ( Request $request, Monthlyreport $monthlyreport ) {
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
        $week         = '';

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $monthlyreport->start_date = $start_period;
        $monthlyreport->end_date   = $end_period;
        $monthlyreport->year       = $year;
        $monthlyreport->month      = $month;
        $monthlyreport->number     = $number;
        $monthlyreport->save();
        $path = '/analyst/monthly/';

        return redirect()->to($path)->with('status', 'Отчет отредактирован');

    }

    public function create2form ( Monthlyreport $monthlyreport ) {

        $reporttitle   = ReportType::find(2)->title;
        $categories    = Category::where('report_type_id', 2)->get();
        $subcategories = Subcategory::all();
        $articles      = $monthlyreport->articles()->with(['category', 'subcategory'])->get();
        $items         = [];

        foreach ( $categories as $category ) {

            foreach ( $subcategories as $subcategory ) {
                foreach ( $articles as $article ) {
                    if ( $article->category_id == $category->id && $article->subcategory_id == NULL ) {
                        $items[ $category->title ][ $article->id ] = [$article];
                    }
                    elseif ( $article->subcategory_id == $subcategory->id && $subcategory->category_id == $category->id ) {

                        $items[ $category->title ][] = [$subcategory->title => $article];
                    }
                }
            }
        }

        $month = $monthlyreport->month;
        $year  = $monthlyreport->year;
        $week  = $monthlyreport->week;

        return view('analyst.monthlyreview.add_form_step2', compact('monthlyreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories', 'subcategories'));
    }

    public function create3form ( Category $category, $subcategoryid, Monthlyreport $monthlyreport ) {

        if ( $subcategoryid != 0 ) {

            $subcategory = Subcategory::find($subcategoryid);
        }
        else {
            $subcategory = NULL;
        }

        return view('analyst.monthlyreview.add_form_step3', compact('category', 'monthlyreport', 'subcategory'));
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
        $category      = $request->input('category');
        $subcategory   = $request->input('subcategory');

        $monthlyreport = $request->input('monthlyreport');
        //dump($monthlyreport);
        $monthlyreport = Monthlyreport::find($monthlyreport);
        //dd($monthlyreport);
        $year  = $request->input('year');
        $month = $request->input('month');
        $week  = date('W', mktime(0, 1, 0, $month, $start_period, $year));

        if ( ( $end_period - $start_period ) < 0 ) { //++

            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $monthlyarticle               = new Monthlyarticle;
        $monthlyarticle->title        = $title;
        $monthlyarticle->body         = $body;
        $monthlyarticle->start_period = $start_period;
        $monthlyarticle->end_period   = $end_period;
        $monthlyarticle->year         = $year;
        $monthlyarticle->month        = $month;
        $monthlyarticle->week         = $week;
        $monthlyreport->articles()->save($monthlyarticle);

        $monthlyarticle = Monthlyarticle::all()->last();

        $category    = Category::find($category);
        $subcategory = Subcategory::find($subcategory);
        $monthlyarticle->category()->associate($category);
        $monthlyarticle->subcategory()->associate($subcategory);
        $monthlyarticle->save();
        //$monthlyarticle->monthlyreport()->associate($monthlyreport);
        $monthlyarticle->countries()->sync($countries);
        $monthlyarticle->companies()->sync($companies);
        $monthlyarticle->personalities()->sync($personalities);
        $monthlyarticle->vvttypes()->sync($vvt_types);

        if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

                $pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
                $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
                Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);
                //dd($thumbnails);

                $monthlyarticle->monthlyimages()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }

	    if ( $flag == 1 ) {
		    $monthlyreport->update(['published' => 0]);//++
		    $monthlyarticle->update(['published' => 1]);
	    }
	    elseif ( $flag == 2 ) {
		    $monthlyarticle->update(['published' => 2]);
	    }
	    else {
		    $monthlyreport->update(['published' => 0]);
		    $monthlyarticle->update(['published' => 0]);

	    }

        $path = '/analyst/monthly/add2/' . $monthlyreport->id;

        return redirect()->to($path)->with('status', 'Статья создана');
    }

    public function delete_article ( Monthlyarticle $monthlyarticle ) {
        $pics = $monthlyarticle->monthlyimages()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $monthlyarticle->monthlyimages()->delete();
        $monthlyarticle->companies()->detach();
        $monthlyarticle->countries()->detach();
        $monthlyarticle->vvttypes()->detach();
        $monthlyarticle->personalities()->detach();
        $monthlyarticle->delete();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_category ( Monthlyreport $monthlyreport, $category_id ) {
        $category = $monthlyreport->articles()->where('category_id', $category_id)->get();
        foreach ( $category as $article ) {
            $pics = $article->monthlyimages()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->monthlyimages()->delete();
            $article->companies()->detach();
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach();
            $article->delete();
        }

        return redirect()->back()->with('status', 'Раздел удален');

    }

    public function upd_form ( Monthlyarticle $monthlyarticle ) {
        $tags                     = [];
        $tags [ 'companies' ]     = $monthlyarticle->companies->pluck('id');
        $tags [ 'countries' ]     = $monthlyarticle->countries->pluck('id')->toArray();
        $tags [ 'vvt_types' ]      = $monthlyarticle->vvttypes->pluck('id');
        $tags [ 'personalities' ] = $monthlyarticle->personalities->pluck('id');
	    $tags ['article']          = $monthlyarticle->id;
	    $array_class = explode('\\', get_class ($monthlyarticle));
	    $tags ['report'] = array_pop($array_class);

        return view('analyst.monthlyreview.upd_form', compact('tags', 'monthlyarticle'));

    }

    public function update ( Request $request, $flag = NULL ) { //++
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
        $reset_img     = $request->input('reset_img');

        $year     = $request->input('year');
        $month    = $request->input('month');
        $week     = date('W', mktime(0, 1, 0, $month, $start_period, $year));
        $week_end = date('W', mktime(0, 1, 0, $month, $end_period, $year));

        if ( ( $end_period - $start_period ) < 0 ) {
            return back()->with('status', 'Неправильный промежуток');
            die();
        }

        $article               = $request->input('monthlyarticle');
        $article               = Monthlyarticle::find($article);
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

        $pics = $article->monthlyimages()->get();//++
        foreach ( $pics as $pic ) {

            foreach ( $reset_img as $img ) {
                if ( "/images/" . $pic->image == $img ) {

                    Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    $article->monthlyimages()->where('id', $pic->id)->delete();

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

                $article->monthlyimages()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }

        $monthlyreport = $article->monthlyreport();//++

        if ( $flag == 1 ) { //++
            $article->update(['published' => 1]);
        }
        elseif ( $flag == 2 ) {
            $article->update(['published' => 2]);
        }
        else {
            $monthlyreport->update(['published' => 0]);
            $article->update(['published' => 0]);

        }//++

        $path = '/analyst/monthly/add2/' . $article->monthlyreport_id;

        return redirect()->to($path)->with('status', 'Статья отредактирована');
    }

	public function role () {

		return Auth::user()->roles[0]->title;

	}
}
