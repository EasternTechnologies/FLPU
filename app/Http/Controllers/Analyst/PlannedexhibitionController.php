<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\models\analyst\exhibitions\Plannedexhibitionyear;
use App\models\analyst\exhibitions\Plannedexhibition;
use App\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PlannedexhibitionController extends Controller
{

    public function __construct () {
        $this->middleware('auth');
		
		$reports     = Plannedexhibitionyear::all();//++
		
		foreach ($reports as $report) {
			
			if($report->published == 2){

			} else {
				$count_all = 0;
				$count_no_pub = 0;
				$articleses = $report->plannedexhibitions()->get();
				
				foreach ($articleses as $articl) {
					if($articl->published != 2) $count_no_pub++;
					$count_all++;
				}
				
				//0 - Ожидает, 1 - Готов к публикации
				if($count_no_pub == 0) {
					$report->update(['published' => 1]);
				} else {
					$report->update(['published' => 0]);
				}
				
				//Нет материалов
				if($count_all == 0 ) {
					$report->update(['published' => -1]);
				}
				
			}
		}//++
    }

    public function years_list () {

        $reporttitle = ReportType::find(5)->title;

	    if( $this->role() == 'user' || $this->role() =='employee' ) {

		    $reports  = Plannedexhibitionyear::latest('start_date')->active()->paginate(20);

	    } else {

		    $reports  = Plannedexhibitionyear::latest('start_date')->paginate(20);

	    }

        return view('analyst.plannedexhibition.index', compact('reports', 'reporttitle'));

    }

    public function list ( Plannedexhibitionyear $plannedexhibitionyear ) {
    	
        $reporttitle = ReportType::find(5)->title;
        $reports     = $plannedexhibitionyear->plannedexhibitions()->orderBy('start', 'ASC')->paginate(20);

        return view('analyst.plannedexhibition.item', compact('reports', 'reporttitle', 'plannedexhibitionyear'));

    }

    public function article ( Plannedexhibition $plannedexhibition ) {
        $reporttitle = ReportType::find(5)->title;

        return view('analyst.plannedexhibition.article', compact('plannedexhibition', 'reporttitle'));

    }

    public function create1form () {

        return view('analyst.plannedexhibition.add_form_step1');
    }

    public function store1 ( Request $request ) {
    	
        $start_period = $request->input('start_period');
        $end_period   = $request->input('end_period');
        $year         = $request->input('year');
        $month        = $request->input('month');
        $week         = '';
		
		if(($end_period - $start_period) < 0) { //++
			return redirect()->refresh()->with('status', 'Неправильный промежуток');
            die();
		}//++
		
		$y = date("Y",$start_period);
        
        $created_report = Plannedexhibitionyear::where('start_date', '>=', mktime(0,0,0,1,1,$y))->where('start_date', '<=', mktime(0,0,0,12,31,$y))->get();
		
        if ( $created_report->count() ) {

            return redirect()->back()->with('status', 'Такой отчет уже существует');

        }  else {

            $report = new Plannedexhibitionyear();

            $report->start_date = $start_period;
            $report->end_date   = $end_period;
            $report->year       = $year;
            $report->month      = $month;
            $report->week       = $week;
            $report->save();
            $path = '/plannedexhibition/show/'. $report->id;

            return redirect()->to($path)->with('status', 'Отчет создан');

        }

    }

    public function create2form ( Plannedexhibitionyear $plannedexhibitionyear ) {
        $reporttitle = ReportType::find(5)->title;
        $reports     = $plannedexhibitionyear->plannedexhibitions()->orderBy('start', 'ASC')->paginate(20);

        return view('analyst.plannedexhibition.add_form_step2', compact('reports', 'reporttitle', 'plannedexhibitionyear'));
    }

    public function add3form ( Plannedexhibitionyear $plannedexhibitionyear ) {

        return view('analyst.plannedexhibition.add_form_step3', compact('reports', 'reporttitle', 'plannedexhibitionyear'));

    }

    public function store3 ( Request $request, $flag = null ) {

        $this->validate($request, [
          //'editor1'      => 'required',
          'title'        => 'required',
          //'start_period' => 'required',
         // 'end_period'   => 'required',
        ]);

        $title         = $request->input('title');
       // $region        = $request->input('region');
       // $country       = $request->input('country');
        $place         = $request->input('place');
        $start         = $request->input('start_period');
        $fin           = $request->input('end_period');
        $theme         = $request->input('theme');
        //$description   = $request->input('editor1');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');

        $year                  = $request->input('year');
        $month                 = $request->input('month');
        $plannedexhibitionyear = Plannedexhibitionyear::find($request->input('plannedexhibitionyear'));

        $article = $plannedexhibitionyear->plannedexhibitions()->create([
          'title'       => $title,
         // 'region'      => $region,
          //'country'     => $country,
          'place'       => $place,
          'start'       => $start,
          'fin'         => $fin,
          'theme'       => $theme,
          //'description' => $description,
        ]);

        $article->countries()->sync($countries);
        $article->companies()->sync($companies);
        $article->personalities()->sync($personalities);
        $article->vvttypes()->sync($vvt_types);

        if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {
                $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

                //$pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
               // $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
               // Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);
                $article->images()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }
		
		if($flag == 1) { //++
			$article->update(['published' => 1]);
		}//++

       // $path = '/plannedexhibition/add2/' . $plannedexhibitionyear->id;

        return redirect()->action(
	        'Analyst\PlannedexhibitionController@create2form', ['plannedexhibitionyear' => $plannedexhibitionyear->id]
        )->with('status', 'Материал создан');

    }

    public function pdf_article ( Plannedexhibition $plannedexhibition ) {
        $reporttitle = ReportType::find(5)->title;

        return view('user.plannedexhibition.pdf_article', compact('plannedexhibition', 'reporttitle'));

    }

    public function delete_article ( Plannedexhibition $plannedexhibition ) {

        $pics = $plannedexhibition->images()->get();
        if ( $pics->count() != 0 ) {
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
        }

        $plannedexhibition->images()->delete();
        $plannedexhibition->delete();

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function delete_year ( Plannedexhibitionyear $plannedexhibitionyear ) {

        if ( isset($plannedexhibitionyear->plannedexhibitions) ) {

            foreach ( $plannedexhibitionyear->plannedexhibitions as $plannedexhibition ) {
                $pics = $plannedexhibition->images()->get();
                if ( $pics->count() != 0 ) {
                    foreach ( $pics as $pic ) {
                        Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
                    }
                }
                $plannedexhibition->images()->delete();
                $plannedexhibition->delete();
            }
        }
		
        $plannedexhibitionyear->delete();

        return redirect()->back()->with('status', 'Отчет удален');
    }
	
    public function publish ( Plannedexhibitionyear $plannedexhibitionyear ) {

        foreach ( $plannedexhibitionyear->plannedexhibitions as $plannedexhibition ) {
            $plannedexhibition->update(['published' => 2]);
        }
        $plannedexhibitionyear->update(['published' => 2]);

        return redirect()->to('/plannedexhibition')->with('status', 'Отчет опубликован');

    }

    public function article_for_approval ( Plannedexhibition $plannedexhibition ) {

        $plannedexhibition->update(['published' => 1]);
		
        return redirect()->back()->with('status', 'Материал на утверждении');
    }

    public function article_publish ( Plannedexhibition $plannedexhibition ) {

        $plannedexhibition->update(['published' => 2]);

        return redirect()->back()->with('status', 'Материал опубликован');

    }

    public function upd_form ( Plannedexhibition $plannedexhibition ) {
        $tags                     = [];
        $tags [ 'companies' ]     = $plannedexhibition->companies->pluck('id');
        $tags [ 'countries' ]     = $plannedexhibition->countries->pluck('id')->toArray();
        $tags [ 'vvt_types' ]      = $plannedexhibition->vvttypes->pluck('id');
        $tags [ 'personalities' ] = $plannedexhibition->personalities->pluck('id');
	    $tags ['article']          = $plannedexhibition->id;
	    $array_class = explode('\\', get_class ($plannedexhibition));
	    $tags ['report'] = array_pop($array_class);
			
		$plannedexhibitionyear = Plannedexhibitionyear::where('id',$plannedexhibition->plannedexhibitionyear_id)->first();
			
        return view('analyst.plannedexhibition.upd_form', compact('plannedexhibition', 'tags','plannedexhibitionyear'));

    }

    public function update ( Request $request, $flag = null  ) {
        $this->validate($request, [
          //'editor1' => 'required',
          'title'   => 'required',
          //'pic' => 'mimes:pdf'
        ]);


        $title         = $request->input('title');
        $region        = $request->input('region');
        $country       = $request->input('country');
        $place         = $request->input('place');
        $start         = $request->input('start');
        $fin           = $request->input('fin');
        $theme         = $request->input('theme');
        $description   = $request->input('editor1');
        $countries     = $request->input('countries');
        $companies     = $request->input('companies');
        $personalities = $request->input('personalities');
        $vvt_types     = $request->input('vvt_types');
		    $reset_img     = $request->input('reset_img');

        $year     = $request->input('year');
        $month    = $request->input('month');
        $week     = date('W', mktime(0, 1, 0, $month, $start, $year));
        $week_end = date('W', mktime(0, 1, 0, $month, $fin, $year));
		
		$plannedexhibitionyear_id              = $request->input('plannedexhibitionyear');
        $plannedexhibition              = $request->input('exhibition');
        $plannedexhibition              = Plannedexhibition::find($plannedexhibition);
        $plannedexhibition->title       = $title;
        $plannedexhibition->region      = $region;
        $plannedexhibition->description = $description;
        $plannedexhibition->title       = $title;
        $plannedexhibition->region      = $region;
        $plannedexhibition->country     = $country;
        $plannedexhibition->place       = $place;
        $plannedexhibition->start       = $start;
        $plannedexhibition->fin         = $fin;
        $plannedexhibition->theme       = $theme;
        $plannedexhibition->description = $description;
        $plannedexhibition->save();

        $plannedexhibition->countries()->sync($countries);
        $plannedexhibition->companies()->sync($companies);
        $plannedexhibition->personalities()->sync($personalities);
        $plannedexhibition->vvttypes()->sync($vvt_types);
		
		    $pics = $plannedexhibition->images()->get();//++

	   
	        foreach ( $pics as $pic ) {
            if ( $reset_img) {
	            foreach ( $reset_img as $img ) {
	                if("/images/".$pic->image == $img) {

	                    Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
						      $plannedexhibition->images()->where('id',$pic->id)->delete();

	                }
				}

	        }//++
        }
          if ( $request->hasFile('pic') ) {
            foreach ( $request->file('pic') as $photo ) {

	              $fileName = time() . '_' . $photo->getClientOriginalName();
                $r        = $photo->storeAs('article_images', $fileName, ['disk' => 'bsvt']);

               // $pathToFile  = Storage::disk('bsvt')->getDriver()->getAdapter()->getPathPrefix();
               // $whereToSave = $pathToFile . 'article_images/' . 'th-' . $fileName;
                $thumbnails  = 'article_images/' . 'th-' . $fileName;
               // Image::make($pathToFile . $r)->fit(616, 308)->save($whereToSave, 100);

                $plannedexhibition->images()->create([
                  'image'     => $r,
                  'thumbnail' => $thumbnails,
                ]);
            }

        }
		
		$plannedexhibitionyear = Plannedexhibitionyear::find($plannedexhibitionyear_id); //++
		
		if($flag == 1) { //++
			$plannedexhibition->update(['published' => 1]);
		} elseif($flag == 2) {
			$plannedexhibition->update(['published' => 2]);
		} else {
			$plannedexhibitionyear->update(['published' => 0]);
			$plannedexhibition->update(['published' => 0]);
			
		}//++
		
        $path = '/plannedexhibition/add2/' . $plannedexhibitionyear_id;

        return redirect()->to($path)->with('status', 'Статья обновлена!');
    }


	public function role () {

		return Auth::user()->roles[0]->title;

	}
}
