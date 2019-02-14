<?php
 
namespace App\Http\Controllers\Analyst;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\analyst\weekly\Weeklyarticle;
use App\models\analyst\weekly\Weeklyreport;
use App\ReportType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class WeeklyController extends Controller
{
    public function __construct () {
        $this->middleware('auth');

		$reports     = Weeklyreport::all();//++
		foreach ($reports as $report) {

//			if($report->published == 2){
//
//			} else {

				$count_all    = 0;
				$count_no_pub = 0;
				$articleses   = $report->articles()->get();

				foreach ( $articleses as $articl ) {
					if ( $articl->published != 2 ) {
						$count_no_pub ++;
					}
					$count_all ++;
				}

				//0 - Ожидает, 1 - Готов к публикации
				if ( $count_no_pub == 0 ) {
					$report->update( [ 'published' => 2 ] );
				} else {
					$report->update( [ 'published' => 0 ] );
				}

				//Нет материалов
				if ( $count_all == 0 ) {
					$report->update( [ 'published' => - 1 ] );
				}
			}

//		}//++
    }

    public function weekly_list () {

    	$reporttitle = ReportType::find(1)->title;

	    if( $this->role() == 'user' || $this->role() =='employee' ){

		    $reports     = Weeklyreport::latest('start_date')->active()->paginate(20);

	    } else {

		    $reports     = Weeklyreport::latest('start_date')->paginate(20);
	    }


		return view('analyst.weeklyreview.index', compact('reports', 'reporttitle'));
    }

    public function weekly_item ( Weeklyreport $weeklyreport ) {

        $reporttitle = ReportType::find(1)->title;
        $categories  = Category::where('report_type_id', 1)->get();
        $articles    = $weeklyreport->articles()->with('category')->orderBy('category_id', 'asc')->get();

        if ( $articles->count() !== 0 ) {

            $items = $articles->mapToGroups(function( $item, $key )
            {
                if ( $item->category !== NULL ) {

                    return [$item->category->title => $item];
                }
                else {
                    return [0 => NULL];
                }
            });
        }
        else {
            $items = [];
        }
        $month      = $weeklyreport->month;
        $year       = $weeklyreport->year;
        $week       = $weeklyreport->week;
        $start_date = $weeklyreport->start_date;
        $end_date   = $weeklyreport->end_date;

        return view('analyst.weeklyreview.item', compact('weeklyreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories', 'start_date', 'end_date'));
		//++
    }

    public function weekly_item_article ( Weeklyarticle $weeklyarticle ) {

		$reporttitle = ReportType::find(1)->title;

        $arr[ 'article' ] = $weeklyarticle;

        if ( isset($weeklyarticle->category_id) ) {

		    $arr[ 'category' ] = Category::find($weeklyarticle->category_id)->title;

	    }

        if ( isset($weeklyarticle->subcategory_id) ) {

            $arr[ 'subcategory' ] = Subcategory::find($weeklyarticle->subcategory_id)->title;

        }

        return view('analyst.weeklyreview.item_article', ['arr' => $arr, 'reporttitle' => $reporttitle]);

    }

    public function delete_weeklyreport ( Weeklyreport $weeklyreport ) {

        $articles = $weeklyreport->articles()->with('images')->get();

        foreach ( $articles as $article ) {
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
        $weeklyreport->delete();

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function delete_article ( Weeklyarticle $weeklyarticle ) {
        $pics = $weeklyarticle->images()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $weeklyarticle->images()->delete();
        $weeklyarticle->companies()->detach();
        $weeklyarticle->countries()->detach();
        $weeklyarticle->vvttypes()->detach();
        $weeklyarticle->personalities()->detach();
        $weeklyarticle->delete();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_category ( Weeklyreport $weeklyreport, $category_id ) {
        $category = $weeklyreport->articles()->where('category_id', $category_id)->get();
        foreach ( $category as $article ) {
            $pics = $article->images()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->images()->delete();
            $article->images()->delete();
			
            $article->companies()->detach();
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach();
			
            $article->delete();
        }

        return redirect()->back()->with('status', 'Раздел удален');

    }
	
    public function publish ( Weeklyreport $weeklyreport ) {

        foreach ( $weeklyreport->articles as $article ) {

            $article->update(['published' => 2]);
        }

        $weeklyreport->update(['published' => 2]);
	   // dd($weeklyreport);
        return redirect()->to('/analyst/weekly')->with('status', 'Отчет опубликован'); //++

    }
	
    public function article_for_approval ( Weeklyarticle $weeklyarticle ) {

        $weeklyarticle->update(['published' => 1]);

        return redirect()->back()->with('status', 'Материал на утверждении'); //++
    }

    public function article_publish ( Weeklyarticle $weeklyarticle ) {

        $weeklyarticle->update(['published' => 2]);

        $path = '/analyst/weekly/show/' . $weeklyarticle->weeklyreport->id;
        return redirect()->to($path)->with('status', 'Материал утвержден');

    }

    public function create1form () {

        return view('analyst.weeklyreview.add_form_step1');
    }

    public function create1 ( Request $request ) {
        $request->validate([
          'number' => 'required',
          'start_period' => 'required',
          'end_period' => 'required',
        ]);
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $number   = $request->input('number');
        $year         = date("Y", $start_period);
        $month        = date("m", $start_period);
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);
	
		if(($end_period - $start_period) < 0) { //++
			return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
		}//++

        $created_report = Weeklyreport::where([
          'number'       => $number,
          'year'       => $year,
          'month'      => $month,
          'week'       => $week,
          'start_date' => $start_period,
        ])->get();
		
        if ( $created_report->count() ) {
            $path = '/analyst/weekly/'; //++

            return redirect()->to($path)->with('status', 'Такой отчет уже существует');

        }
        else {

            $report = new Weeklyreport();

            $report->start_date = $start_period;
            $report->end_date   = $end_period;
            $report->year       = $year;
            $report->month      = $month;
            $report->week       = $week;
            $report->number       = $number;
            $report->save();
            
            $path = '/analyst/weekly/add2/' . $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');

        }

    }

    public function updreportform (Weeklyreport $weeklyreport) {

        return view('analyst.weeklyreview.updreportform', compact('weeklyreport'));
    }

    public function updreport ( Request $request,Weeklyreport $weeklyreport ) {
        $request->validate([
          'number' => 'required',
          'start_period' => 'required',
          'end_period' => 'required',
        ]);
        
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $number   = $request->input('number');
        
        $year         = date("Y", $start_period);
        $month        = date("m", $start_period);
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);


            $weeklyreport->start_date = $start_period;
            $weeklyreport->end_date   = $end_period;
            $weeklyreport->year       = $year;
            $weeklyreport->month      = $month;
            $weeklyreport->week       = $week;
            $weeklyreport->number       = $number;
            $weeklyreport->save();

            $path = '/analyst/weekly';

            return redirect()->to($path)->with('status', 'Отчет отредактирован');



    }

    public function create2form ( Weeklyreport $weeklyreport ) {

        $reporttitle = ReportType::find(1)->title;
        $categories  = Category::where('report_type_id', 1)->get();
        $articles    = $weeklyreport->articles()->with('category')->get();
        foreach ( $categories as $category ) {
            foreach ( $articles as $article ) {
                if ( $article->category_id == $category->id ) {
                    $items[ $category->title ][] = $article;
                }
            }
        }
		
        $month = $weeklyreport->month;
        $year  = $weeklyreport->year;
        $week  = $weeklyreport->week;

        return view('analyst.weeklyreview.add_form_step2', compact('weeklyreport', 'items', 'year', 'week', 'reporttitle', 'month', 'categories')

        );
    }

    public function create3form ( Category $category, Weeklyreport $weeklyreport ) {

        return view('analyst.weeklyreview.add_form_step3', compact('category', 'weeklyreport'));
    }

    public function create3 ( Request $request, $flag = null ) { //++
		
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

        $weeklyreport = $request->input('weeklyreport');
        $weeklyreport = Weeklyreport::find($weeklyreport);
        $year         = date('Y', $start_period);
        $month        = date('m', $start_period);
        $week         = date('W', $start_period);
        $week_end     = date('W', $end_period);
		
		
		if(($end_period - $start_period) < 0) { //++
			return back()->with('status', 'Неправильный промежуток');
            die();
		}
		
        $weeklearticle               = new Weeklyarticle;
        $weeklearticle->title        = $title;
        $weeklearticle->body         = $body;
        $weeklearticle->start_period = $start_period;
        $weeklearticle->end_period   = $end_period;
        $weeklearticle->year         = $year;
        $weeklearticle->month        = $month;
        $weeklearticle->week         = $week;
        $weeklyreport->articles()->save($weeklearticle);

        $weeklearticle = Weeklyarticle::all()->last();

        $category = Category::find($category);
        $weeklearticle->category()->associate($category);
        $weeklearticle->save();
        $weeklearticle->countries()->sync($countries);
        $weeklearticle->companies()->sync($companies);
        $weeklearticle->personalities()->sync($personalities);
        $weeklearticle->vvttypes()->sync($vvt_types);

        if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

                $pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
                $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
                Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);

                $weeklearticle->images()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }
		
		if($flag == 1) { //++
			$weeklearticle->update(['published' => 1]);
		}//++
		
        $path = '/analyst/weekly/add2/' . $weeklyreport->id;

        return redirect()->to($path)->with('status', 'Статья создана');
    }

    public function upd_form ( Weeklyarticle $weeklyarticle ) {
        $tags                     = [];
        $tags [ 'companies' ]     = $weeklyarticle->companies->pluck('id');
        $tags [ 'countries' ]     = $weeklyarticle->countries->pluck('id')->toArray();
        $tags [ 'vvt_types' ]      = $weeklyarticle->vvttypes->pluck('id')->toArray();;
        $tags [ 'personalities' ] = $weeklyarticle->personalities->pluck('id');
        $tags ['article']          = $weeklyarticle->id;
	    $array_class = explode('\\', get_class ($weeklyarticle));
	    $tags ['report'] = array_pop($array_class);


        $weeklyreport = Weeklyreport::find($weeklyarticle->weeklyreport_id);


       // return view('analyst.weeklyreview.add_form_step3', compact('weeklyarticle', 'tags', 'weeklyreport'));
	    return view('analyst.weeklyreview.upd_form', compact('weeklyarticle', 'tags', 'weeklyreport'));

    }

    public function update ( Request $request, $flag = null  ) { //++
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
		$reset_img     = $request->input('reset_img');//++
		
        $year         = date("Y", $start_period);
        $month        = date("m", $start_period);
		
        $week     = date('W',$start_period);
        $week_end = date('W', $end_period);

		
        $weeklyarticle               = $request->input('weeklyarticle');
        $weeklyarticle               = Weeklyarticle::find($weeklyarticle);
			
		if(($end_period - $start_period) < 0) { //++
			return back()->with('status', 'Неправильный промежуток');
            die();
		}
		
        $weeklyarticle->title        = $title;
        $weeklyarticle->body         = $body;
        $weeklyarticle->start_period = $start_period;
        $weeklyarticle->end_period   = $end_period;
        $weeklyarticle->year         = $year;
        $weeklyarticle->month        = $month;
        $weeklyarticle->week         = $week;
        $weeklyarticle->save();

        $weeklyarticle->countries()->sync($countries);
        $weeklyarticle->companies()->sync($companies);
        $weeklyarticle->personalities()->sync($personalities);
        $weeklyarticle->vvttypes()->sync($vvt_types);
		
		$pics = $weeklyarticle->images()->get();//++
        foreach ( $pics as $pic ) {
        	
        	foreach ( $reset_img as $img ) {
        		if("/images/".$pic->image == $img) {
        			
        			Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
					$weeklyarticle->images()->where('id',$pic->id)->delete();
					
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

                $weeklyarticle->images()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }
		
		$weeklyreport = Weeklyreport::find($weeklyarticle->weeklyreport_id); //++
		
		if($flag == 1) { //++
			$weeklyarticle->update(['published' => 1]);
		} elseif($flag == 2) {
			$weeklyarticle->update(['published' => 2]);
		} else {
			$weeklyreport->update(['published' => 0]);
			$weeklyarticle->update(['published' => 0]);
			
		}//++
		
		$path = '/analyst/weekly/add2/' . $weeklyarticle->weeklyreport_id;
		
        return redirect()->to($path)->with('status', 'Статья обновлена!');
    }

    public function role () {

	    return Auth::user()->roles[0]->title;

    }

}
