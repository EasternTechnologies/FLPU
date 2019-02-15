<?php
namespace App\Http\Controllers\Report;

use App\ArticleReports;
use App\Category;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\VvtType;
use App\ReportType;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\Report;

class ReportController extends Controller
{
	protected $report = 0;

    public function __construct () {
        $this->middleware('auth');
        $this->reports();

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
                $articles = ArticleReports::whereIN('id',$request->id)->get();
                $articles = $articles->sortBy('title');
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

            $items = $report->articles()->where('report_id', $report->id )->paginate(10);
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

    public function delete_report ( $slug, Report $report ) {

        $articles = $report->articles()->get();

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
	        $article->removeFromIndex();

        }
        $report->delete();

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function delete_article ( $slug, ArticleReports $article ) {
        $pics = $article->images()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $article->images()->delete();
        $article->personalities()->detach();
        $article->companies()->detach();
        $article->delete();
        $article->removeFromIndex();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_subcategory ( $slug, Subcategory $subcategory ) {
        foreach ( $subcategory->article_reports as $article ) {
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
	        $article->removeFromIndex();
        }
        $subcategory->delete();

        return redirect()->back()->with('status', 'Подраздел удален');

    }

    public function delete_category ($slug,Category $category) {

        $articles = $category->article_reports()->get();
        foreach ( $articles as $article ) {
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
	        $article->removeFromIndex();
        }
        $category->delete();

        return redirect()->back()->with('status', 'Раздел удален');

    }

    public function publish ( $slug, Report $report ) {

        foreach ( $report->articles as $article ) {
            $article->update(['status' => 2]);
        }

        $report->update(['status' => 2]);
		
        return redirect()->to('/report/'.$report->types->slug. '/show/'. $report->id)->with('status', 'Отчет опубликован'); //++

    }
	
    public function article_for_approval ( Weeklyarticle $weeklyarticle ) {

        $weeklyarticle->update(['status' => 1]);

        return redirect()->back()->with('status', 'Материал на утверждении'); //++
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
        
    	if (  $report->types->slug == 'weekly' || $report->types->slug == 'monthly' ) {
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
//      dd($subcategories_array);


//        foreach ( $articles as $article ) {
//            if ($article->category_id) {
//                foreach ($categories as $category) {
//                    if ($article->category_id == $category->id) {
//                        $subcategory = $article->subcategory_id != false ? $article->subcategory_id : false; // problem
//                        $items[$article->category_id][$subcategory][] = $article;
//                        if ($subcategory) {
//                            $subcategories[$article->category_id][$article->subcategory_id] = $article->subcategory->title;
//                        }
//                    }
//                }
//            }
//                else {
//                    $subcategory = $article->subcategory_id != false ?  $article->subcategory_id: false; // problem
//                    $items[false][$subcategory][] = $article;
//                    if($subcategory) {
//                        $subcategories[$article->category_id][$article->subcategory_id] = $article->subcategory->title;
//                    }
//                }
//            }


        $items = [];

        foreach ($categories as $category) {
            $items[$category->id] = [];
        }
        foreach ($subcategories_array as $subcategory)
        {
            $items[$subcategory->category_id][$subcategory->id] = [];
            $subcategories[$subcategory->category_id][$subcategory->id] = $subcategory->title;
        }

        foreach ($articles as $key => $article) {
            if ($article->category_id) {
                    $subcategory = $article->subcategory_id != false ? $article->subcategory_id : false; // problem
                    $items[$article->category_id][$subcategory][] = $articles->pull($key);
//                    if ($subcategory) {
//                        $subcategories[$article->category_id][$article->subcategory_id] = $article->subcategory->title;
//                    }

            }
        }


        foreach ($articles as $key => $article) {
            $subcategory = $article->subcategory_id != false ?  $article->subcategory_id: false; // problem
            $items[false][$subcategory][] = $article;
//            if($subcategory) {
//                $subcategories[$article->category_id][$article->subcategory_id] = $article->subcategory->title;
//            }
        }

//        dump($items);
//    dd($subcategories);


        return view('report.add_form_step_2', compact('report', 'items', 'categories','subcategories')

        );
    }

    public function report_step_3 ( $slug, Report $report, Category $category, Subcategory $subcategory) {

        return view('report.add_form_step_3', compact('category', 'report', 'subcategory'));
    }

    public function create3 ( Request $request, $flag = null ) {


//        dd($request->all());


        $this->validate($request, [
            'editor1' => 'required',
            //'title_1'   => 'required',
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
	        $article->category_id = $category->id;
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

        if($flag == 1) {
            $article->update(['status' => 1]);
        }

        $path = '/report/'. $report->types->slug .'/add2/'. $report->id;

        return redirect()->to($path)->with('status', 'Статья создана');
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
        $report = Report::find($article->report_id);

       // return view('analyst.weeklyreview.add_form_step3', compact('weeklyarticle', 'tags', 'weeklyreport'));
	    return view('report.upd_form', compact('article', 'tags', 'report'));

    }

    public function update ( Request $request, $flag = null  ) {
        $this->validate($request, [
          'editor1' => 'required',
         // 'title'   => 'required',
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
        $article->updateIndex();
		
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
		
		$report = Report::find($article->report_id); //++
		
		if($flag == 1) { //++
			$article->update(['status' => 1]);
		} elseif($flag == 2) {
			$article->update(['status' => 2]);
		} else {
			$report->update(['status' => 0]);
			$article->update(['status' => 0]);
			
		}//++
		
		$path = '/report/'. $report->types->slug .'/add2/'. $article->report_id;
		
        return redirect()->to($path)->with('status', 'Статья обновлена!');
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
			] );

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

	public function role () {

		return Auth::user()->roles[0]->title;

	}

	public function reports(){

		$reports     = Report::all();//++

		foreach ($reports as $report) {


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

			}
		}

}
