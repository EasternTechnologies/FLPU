<?php
 
namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\analyst\yearly\Yearlyarticle;
use App\models\analyst\yearly\Yearlycategory;
use App\models\analyst\yearly\Yearlyreport;
use App\models\analyst\yearly\Yearlysubcategory;
use App\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class YearlyController extends Controller
{

    public function __construct () {
        $this->middleware('auth');
		
		$reports     = Yearlyreport::all();

		foreach ($reports as $report) {

//			if($report->published == 2){
//
//			} else {
			$count_all    = 0;
			$count_no_pub = 0;

			$articleses = collect($report->articles());

			$articles = $articleses->merge($report->articlesReport);

				foreach ($articles as $articl) {

					if($articl->published != 2) $count_no_pub++;
					$count_all++;
				}

				//0 - Ожидает, 1 - Готов к публикации
				if($count_no_pub == 0) {
					$report->update(['published' => 2]);
				} else {
					$report->update(['published' => 0]);
				}
				
				//Нет материалов
				if($count_all == 0 ) {
					$report->update(['published' => -1]);
				}
				
			}
//		}//++
		
    }

    public function list () {

        $reporttitle = ReportType::find(4)->title;

	    if( $this->role() == 'user' || $this->role() =='employee' ){

		    $reports  = Yearlyreport::latest('start_date')->active()->paginate(20);

	    } else {

		    $reports  = Yearlyreport::latest('start_date')->paginate(20);

	    }

        return view('analyst.yearlyreview.index', compact('reports', 'reporttitle'));

    }

    public function item ( Yearlyreport $yearlyreport ) {
        $reporttitle = ReportType::find(4)->title;
        $articles    = Yearlyreport::where('yearlyreports.id', $yearlyreport->id)//join('yearlyarticles', 'yearlysubcategories.id', '=', 'yearlyarticles.subcategory_id')
                                   ->with('categories', 'categories.subcategories.articles')//->select('yearlycategories.*')
                                   ->get();
        if ( $articles->count() !== 0 ) {

            $items = $articles;
        }
        else {
            $items = [];
        }
        $month      = $yearlyreport->month;
        $year       = $yearlyreport->year;
        $week       = $yearlyreport->week;
        $start_date = $yearlyreport->start_date;
        $end_date   = $yearlyreport->end_date;

        return view('analyst.yearlyreview.item', compact('yearlyreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories', 'start_date', 'end_date')//return view('weeklyreview.analyst.add_form_step2', compact('items', 'year', 'title')

        );
    }

    public function item_article ( Yearlyarticle $yearlyarticle ) {
        $reporttitle = ReportType::find(4)->title;
		
		if ( isset($yearlyarticle->subcategory ) ) {
            $yearlyreport = Yearlyreport::find($yearlyarticle->subcategory->category->yearlyreport_id);

        }
        elseif ( isset($yearlyarticle->subcategory )) {

            $yearlyreport = Yearlyreport::find($yearlyarticle->category->yearlyreport_id);

        } else {

			$yearlyreport = Yearlyreport::find($yearlyarticle->yearlyreport_id);

		}
		
        return view('analyst.yearlyreview.item_article', ['article' => $yearlyarticle, 'reporttitle' => $reporttitle, 'yearlyreport' => $yearlyreport]);

    }

    public function delete_report ( Yearlyreport $yearlyreport ) {

        $reports = Yearlyreport::where('yearlyreports.id', $yearlyreport->id)
                               ->with('categories', 'categories.subcategories.articles')
                               ->get();
        if ( $reports->count() == 0 ) {
            $yearlyreport->delete();

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

            $yearlyreport->delete();

        }

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function delete_article ( Yearlyarticle $yearlyarticle ) {
        $pics = $yearlyarticle->images()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $yearlyarticle->images()->delete();
        $yearlyarticle->companies()->detach();
        $yearlyarticle->countries()->detach();
        $yearlyarticle->vvttypes()->detach();
        $yearlyarticle->personalities()->detach();
        $yearlyarticle->delete();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_category ( Yearlycategory $yearlycategory ) {
        $category     = $yearlycategory;
        $cat_articles = $yearlycategory->articles;
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

    public function delete_subcategory ( Yearlysubcategory $yearlysubcategory ) {
        foreach ( $yearlysubcategory->articles as $article ) {
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
        $yearlysubcategory->delete();

        return redirect()->back()->with('status', 'Подраздел удален');

    }


    public function publish ( Yearlyreport $yearlyreport ) {

        foreach ( $yearlyreport->articlesReport as $article ) {

            $article->update(['published' => 2]);

        }

	    foreach ( $yearlyreport->articles() as $article ) {

			$article->update( [ 'published' => 2 ] );

	    }

        $yearlyreport->update(['published' => 2]);

        return redirect()->to('/analyst/yearly')->with('status', 'Отчет опубликован');

    }


    public function article_for_approval ( Yearlyarticle $yearlyarticle ) {

        $yearlyarticle->update(['published' => 1]);

        return redirect()->back()->with('status', 'Материал на утверждении');
    }


    public function article_publish ( Yearlyarticle $yearlyarticle ) {

        $yearlyarticle->update(['published' => 2]);

        if ($yearlyarticle->category){
            $path = '/analyst/yearly/show/' . $yearlyarticle->category->report->id;

        }else{
            $path = '/analyst/yearly/show/' . $yearlyarticle->subcategory->category->report->id;

        }
        return redirect()->to($path)->with('status', 'Материал утвержден');

    }

    public function create1form () {

	    $reporttitle = ReportType::find(4)->title;

        return view('analyst.yearlyreview.add_form_step1', compact('reporttitle'));
    }

    public function create1 ( Request $request ) {
        $request->validate([
         // 'number' => 'required',
          'start_period' => 'required',
          'end_period' => 'required',
        ]);
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
        //$number        = $request->input('number');
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);
		
		if(($end_period - $start_period) < 0) { //++
			return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
		}//++
		
        $created_report = Yearlyreport::where([
          'year'       => $year,
         // 'number'           => $number,
          'month'      => $month,
          'week'       => $week,
          'start_date' => $start_period,
        ])->get();
		
        if ( $created_report->count() ) {

            return redirect()->route('yearly')->with('status', 'Такой отчет уже существует');

        }
        else {

            $report = new Yearlyreport();

            $report->start_date = $start_period;
            $report->end_date   = $end_period;
           // $report->number           = $number;
            $report->year       = $year;
            $report->month      = $month;
            $report->week       = $week;
            $report->save();
			
            $path = '/analyst/yearly/add2/' . $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');

        }

    }

    public function updreportform (Yearlyreport $yearlyreport) {

	    $reporttitle = ReportType::find(4)->title;

        return view('analyst.yearlyreview.updreportform', compact('reporttitle', 'yearlyreport'));
    }

    public function updreport ( Request $request,Yearlyreport $yearlyreport ) {
        $request->validate([
         // 'number' => 'required',
          'start_period' => 'required',
          'end_period' => 'required',
        ]);
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
       //$number        = $request->input('number');
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);

		if(($end_period - $start_period) < 0) { //++
			return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
		}//++


            $yearlyreport->start_date = $start_period;
            $yearlyreport->end_date   = $end_period;
            //$yearlyreport->number           = $number;
            $yearlyreport->year       = $year;
            $yearlyreport->month      = $month;
            $yearlyreport->week       = $week;
            $yearlyreport->save();

            $path = '/analyst/yearly/';

            return redirect()->to($path)->with('status', 'Отчет отредактирован');



    }

    public function createcategory ( Request $request ) {
        $this->validate($request, [
          'title' => 'required',
        ]);

        $title        = $request->input('title');
        $yearlyreport = $request->input('yearlyreport');
        $yearlyreport = Yearlyreport::find($yearlyreport);

        $created_category = $yearlyreport->categories()->create(['title' => $title]);
        $path             = '/analyst/yearly/add2/' . $yearlyreport->id;

        return redirect()->to($path)->with('status', 'Раздел создан');
    }

    public function createsubcategory ( Request $request ) {
        $this->validate($request, [
          'title' => 'required',
        ]);

        $title          = $request->input('title');
        $yearlyreport   = $request->input('yearlyreport');
        $yearlyreport   = Yearlyreport::find($yearlyreport);
        $yearlycategory = $request->input('yearlycategory');
        $yearlycategory = Yearlycategory::find($yearlycategory);
        //dd($yearlycategory);
        $created_category = $yearlycategory->subcategories()->create(['title' => $title]);
        $path             = '/analyst/yearly/add2/' . $yearlyreport->id;

        return redirect()->to($path)->with('status', 'Раздел создан');
    }

    public function create2form ( Yearlyreport $yearlyreport ) {

        /*
         * формирование массива статей по категориям всем
         * */

        $reporttitle = ReportType::find(4)->title;
        $articles    = Yearlyreport::where('yearlyreports.id', $yearlyreport->id)//join('yearlyarticles', 'yearlysubcategories.id', '=', 'yearlyarticles.subcategory_id')
                                   ->with('categories', 'categories.subcategories.articles')//->select('yearlycategories.*')
                                   ->get();
        if ( $articles->count() !== 0 ) {

            $items = $articles;
        }
        else {
            $items = [];
        }
        $month      = $yearlyreport->month;
        $year       = $yearlyreport->year;
        $week       = $yearlyreport->week;
        $start_date = $yearlyreport->start_date;
        $end_date   = $yearlyreport->end_date;

        //dd($items);

        return view('analyst.yearlyreview.add_form_step2', compact('yearlyreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories'));

    }

    public function create3form ( Yearlyreport $yearlyreport, Yearlycategory $yearlycategory, $yearlysubcategory = 0 ) {

	    $reporttitle = ReportType::find(4)->title;

        if ( $yearlysubcategory != 0 ) {

            $yearlysubcategory = Yearlysubcategory::find($yearlysubcategory);
        }

        return view('analyst.yearlyreview.add_form_step3', compact('reporttitle', 'yearlyreport', 'yearlycategory', 'yearlysubcategory'));
    }

    public function create3 ( Request $request, $flag = null ) {
		
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
		$yearlyreport_id  = $request->input('yearlyreport');
		
        $week          = date('W', mktime(0, 1, 0, $month, $start_period, $year));
        $week_end      = date('W', mktime(0, 1, 0, $month, $end_period, $year));
		
		if(($end_period - $start_period) < 0) { //++
			return back()->with('status', 'Неправильный промежуток');
            die();
		}
		
        if ( !empty($request->input('yearlysubcategory')) ) {

            $yearlysubcategory = Yearlysubcategory::find($request->input('yearlysubcategory'));

            $created_article = $yearlysubcategory->articles()->create([
              'title'        => $title,
              'body'         => $body,
              'start_period' => $start_period,
              'end_period'   => $end_period,
              'year'         => $year,
              'month'        => $month,
              'week'         => $week,

              'yearlycategory_id' => $request->input('yearlycategory'),
            ]);

        }
        elseif (!empty($request->input('yearlycategory'))) {

            $yearlycategory  = Yearlycategory::find($request->input('yearlycategory'));
            $created_article = $yearlycategory->articles()->create([
              'title'        => $title,
              'body'         => $body,
              'start_period' => $start_period,
              'end_period'   => $end_period,
              'year'         => $year,
              'month'        => $month,
              'week'         => $week,
            ]);

        } else {

	        $yearlyreport  = Yearlyreport::find($yearlyreport_id );
	        $created_article = $yearlyreport->articlesReport()->create([
		        'title'        => $title,
		        'body'         => $body,
		        'start_period' => $start_period,
		        'end_period'   => $end_period,
		        'year'         => $year,
		        'month'        => $month,
		        'week'         => $week,
	        ]);

        }

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
		
		if($flag == 1) { //++
			$created_article->update(['published' => 1]);
		}
		
        $path = '/analyst/yearly/add2/' . $yearlyreport_id;

        return redirect()->to($path)->with('status', 'Статья создана');
    }

    public function upd_form ( Yearlyarticle $yearlyarticle ) {
	    $reporttitle = ReportType::find(4)->title;
        if ( isset($yearlyarticle->subcategory) ) {

            $yearlyreport = Yearlyreport::find($yearlyarticle->subcategory->category->yearlyreport_id);

        }
        elseif (isset($yearlyarticle->category)) {

            $yearlyreport = Yearlyreport::find($yearlyarticle->category->yearlyreport_id);

        } else {

	        $yearlyreport = Yearlyreport::find($yearlyarticle->yearlyreport_id);

        }

        $tags                     = [];
        $tags [ 'companies' ]     = $yearlyarticle->companies->pluck('id');
        $tags [ 'countries' ]     = $yearlyarticle->countries->pluck('id')->toArray();
        $tags [ 'vvt_types' ]      = $yearlyarticle->vvttypes->pluck('id');
        $tags [ 'personalities' ] = $yearlyarticle->personalities->pluck('id');
	    $tags ['article']          = $yearlyarticle->id;
	    $array_class = explode('\\', get_class ($yearlyarticle));
	    $tags ['report'] = array_pop($array_class);

        return view('analyst.yearlyreview.upd_form', compact('reporttitle', 'yearlyarticle', 'yearlyreport', 'tags'));

    }

    public function update ( Request $request, $flag = null) {
    	
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
		
		$yearlyreport_id = $request->input('yearlyreport_id');
		$reset_img     = $request->input('reset_img');
			
        $yearlyarticle         = $request->input('yearlyarticle');
        $article               = Yearlyarticle::find($yearlyarticle);
        $article->title        = $title;
        $article->body         = $body;
        $article->start_period = $start_period;
        $article->end_period   = $end_period;
        $article->year         = $year;
        $article->month        = $month;
        $article->week         = $week;
        $article->save();
		
		if(($end_period - $start_period) < 0) { //++
			return back()->with('status', 'Неправильный промежуток');
            die();
		}
		
        $article->countries()->sync($countries);
        $article->companies()->sync($companies);
        $article->personalities()->sync($personalities);
        $article->vvttypes()->sync($vvt_types);
		
		$pics = $article->images()->get();//++
        foreach ( $pics as $pic ) {
        	
        	foreach ( $reset_img as $img ) {
        		if("/images/".$pic->image == $img) {
        			
        			Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
					$article->images()->where('id',$pic->id)->delete();
					
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
		
		$report = Yearlyreport::find($yearlyreport_id); //++
		
		
		if($flag == 1) { //++
			$article->update(['published' => 1]);
		} elseif($flag == 2) {
			$article->update(['published' => 2]);
		} else {
			$report->update(['published' => 0]);
			$article->update(['published' => 0]);
			
		}//++

		$path = '/analyst/yearly/add2/' . $yearlyreport_id;//++
		
        return redirect()->to($path)->with('status', 'Статья обновлена!');
    }

	public function upd_form_category ( Yearlycategory $yearlycategory ) {

		return view('analyst.yearlyreview.upd_form_category', [
			'category'         => $yearlycategory,
		]);
	}

	public function update_category ( Request $request, Yearlycategory $yearlycategory, $flag = NULL ) { //++

		$title = $request->input('title');

		$article           = Yearlycategory::find($yearlycategory->id);
		$article->title    = $title;
		$article->save();

		if ( $flag == 1 ) { //++
			$article->update(['published' => 1]);
		}
		elseif ( $flag == 2 ) {
			$article->update(['published' => 2]);
		}
		else {
			$yearlycategory->report->update(['published' => 0]);
			$article->update(['published' => 0]);

		}//++

		$path = '/analyst/yearly/add2/' . $yearlycategory->report->id;

		return redirect()->to($path)->with('status', 'Категория обновлена');
	}



	public function upd_form_subcategory ( Yearlysubcategory $yearlysubcategory ) {

		return view('analyst.yearlyreview.upd_form_category', [
			'category'         => $yearlysubcategory,
		]);
	}


	public function update_subcategory ( Request $request, Yearlysubcategory $yearlysubcategory, $flag = NULL ) { //++

		$title = $request->input('title');

		$article           = Yearlysubcategory::find($yearlysubcategory->id);
		$article->title    = $title;
		$article->save();

		if ( $flag == 1 ) { //++
			$article->update(['published' => 1]);
		}
		elseif ( $flag == 2 ) {
			$article->update(['published' => 2]);
		}
		else {
			$yearlysubcategory->report->update(['published' => 0]);
			$article->update(['published' => 0]);

		}//++

		$path = '/analyst/yearly/add2/' . $yearlysubcategory->category->report->id;

		return redirect()->to($path)->with('status', 'Категория обновлена');
	}

	public function role () {
		return Auth::user()->roles[0]->title;
	}

}
