<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\Report;
use App\ArticleReports;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Country;
use App\Models\Personality;
use App\Models\VvtType;
use App\ReportType;
use App\User;
use Illuminate\Http\Request;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct () {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index () {

        $report_types = ReportType::all()->except(6);

        $total_array = [];
        $i=$k=0;

        foreach ($report_types as $report_type) {

            $total_array[$k][] = [
            	$report_type->description,$report_type->slug,
	            Report::latest('date_start')->where('type_id',$report_type->id)->active()->take(3)->get(),
            ];

            $i++;
            if($i==2) {
                $i=0;
                $k++;
            }

        }
        
        return view('user.home', compact('title', 'total_array'));
    }

    public function cabinet ( User $user ) {

        $role = $user->roles->first();

        return view('user.cabinet', compact('user', 'role'));
    }

    public function search ( Request $request) {
	    if($request->ajax()){

		    $q = $request->q;

	    } else {

		    $q = strip_tags($request->q);

	    }

	    $articles = ArticleReports::search($q, $size = 10000);

		$articles = $this->paginate($articles, 20);

		$articles->appends($request->all())->setPath('/simply_search');

	    if($request->ajax()){
		    return   $articles;
	    }
        return view('user.advan_search_result', compact('articles'));
    }

    public function advanced_search_form () {
        $countries         = Country::orderBy('title')->get();
        $companies         = Company::orderBy('title')->get();
        $vvt_types         = VvtType::orderBy('title')->get();
        $personalities     = Personality::orderBy('title')->get();
        $report_types      = \App\ReportType::$data;
        $weeklycategories  = Category::where('report_type_id', 1)->get();
        $monthlycategories = Category::where('report_type_id', 2)->get();

        return view('user.advan_search', compact('report_types', 'result', 'countries', 'companies', 'vvt_types', 'personalities', 'weeklycategories', 'monthlycategories'));
    }

    public function advanced_search ( Request $request ) {

        $start_period  = $request->input('start_period');
        $end_period    = $request->input('end_period');
        $countries     = $request->input('countries') ? Country::whereIn('id', $request->input('countries'))
                                                               ->get() : collect([]);
        $companies     = $request->input('companies') ? Company::whereIn('id', $request->input('companies'))
                                                               ->get() : collect([]);
        $personalities = $request->input('personalities') ? Personality::whereIn('id', $request->input('personalities'))
                                                                       ->get() : collect([]);
        $vvt_types     = $request->input('vvt_types') ? VvtType::whereIn('id', $request->input('vvt_types'))
                                                               ->get() : collect([]);
        $report_type   = ReportType::where('slug', $request->input('report_type'))
                                   ->first() ? ReportType::where('slug', $request->input('report_type'))
                                                                               ->first() : $request->input('report_type');
        $report_slug   = ReportType::where('slug', $request->input('report_type'))
                                   ->first() ? ReportType::where('slug', $request->input('report_type'))
                                                                             ->first()->id : $request->input('report_type');
        $new_weekly    = (int) $request->input('new_weekly');
        $new_monthly   = (int) $request->input('new_monthly');

        if ( $new_weekly !== 0) {

	        $category = $new_weekly;

        } elseif( $new_monthly !== 0 ) {

	        $category = $new_monthly;

        } else {

	        $category = 0;

        }

        if ( $countries->count() == 0 and $companies->count() == 0 and $personalities->count() == 0 and $vvt_types->count() == 0 ) {
            //поиск без учета тегов

			if ( $report_slug == 'all_reports' ){
				$articles =  ArticleReports::where([
					['date_start', '>=', $start_period],
					['date_end', '<=', $end_period],
				])->active()->paginate(40);
				$articles->appends($request->all());
			} else {

				$reports = Report::where([
					['type_id' , $report_slug],
					['date_start', '>=', $start_period],
					['date_end', '<=', $end_period],
				])->pluck('id')->toArray();

				if ( $category === 0 ) {

					$articles = ArticleReports::whereIn( 'report_id', $reports )->active()->paginate(40);
					$articles->appends($request->all());

				} else {

					$articles = ArticleReports::whereIn(
						'report_id', $reports )->where( 'category_id', $category )->active()->paginate(40);
					$articles->appends($request->all());

				}

			}

        }
        else {
            //поиск с учетом тегов
            //Получаем количество отмеченных тегов
            $tags_count = $countries->count() + $companies->count() + $vvt_types->count() + $personalities->count();

	        if ( $report_slug == 'all_reports' ) {
		        //поиск по всем таблицам
		        $this->findbytagsinalltables( $countries, $companies, $vvt_types, $personalities, $start_period, $end_period, $articles );
		        $strong = collect();
		        if ( isset( $articles ) ) {
			        //группируем все стаьи
			        foreach ( $articles->groupBy( 'id' ) as $value ) {
				        //если одна запись вытянулась на каждый тег
				        if ( $value->count() == $tags_count ) {
					        //добавляем ее в массив, передаваемый во вьюху
					        $strong = $strong->concat($value);
				        }
			        };
		        }

		        $articles = isset( $strong ) ? $strong : collect( [] );
		        $articles = $this->paginate($articles);
		        $articles->appends($request->all())->setPath('search');

	        } else {
		        $strong = collect();
				$articles = collect();
		        $reports = Report::where([
			        ['type_id' , $report_slug],
			        ['date_start', '>=', $start_period],
			        ['date_end', '<=', $end_period],
		        ])->pluck('id')->toArray();

		        if ( $category === 0 ) {

			        foreach ( $countries as $country ) {

				        $articles = $articles->concat($country->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
					        ] )->whereIn( 'report_id', $reports )->active()->get());
			        }
			        foreach ( $companies as $company ) {

				        $articles = $articles->concat($company->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
					        ] )->whereIn( 'report_id', $reports )->active()->get());
			        }
			        foreach ( $vvt_types as $vvt_type ) {

				        $articles = $articles->concat($vvt_type->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
					        ] )->whereIn( 'report_id', $reports )->active()->get());

			        }
			        foreach ( $personalities as $personality ) {

				        $articles = $articles->concat($personality->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
					        ] )->whereIn( 'report_id', $reports )->active()->get());

			        }

			        if ( isset( $articles ) ) {
				        //группируем все стаьи
				        foreach ( $articles->groupBy( 'id' ) as $value ) {
					        //если одна запись вытянулась на каждый тег
					        if ( $value->count() == $tags_count ) {
						        //добавляем ее в массив, передаваемый во вьюху
						        $strong = $strong->concat($value);
					        }
				        };
			        }

			        $articles = isset( $strong ) ? $strong : collect( [] );
			        $articles = $this->paginate($articles);
			        $articles->appends($request->all())->setPath('search');
		        } else {
			        foreach ( $countries as $country ) {

				        $articles = $articles->concat($country->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
			                    [ 'category_id', $category ]
					        ] )->whereIn( 'report_id', $reports )->active()->get());

				        }
			        foreach ( $companies as $company ) {

				        $articles = $articles->concat($company->articles()->where( [
					        [ 'date_start', '>=', $start_period ],
					        [ 'date_end', '<=', $end_period ],
					        [ 'category_id', $category ]
					        ] )->whereIn( 'report_id', $reports )->active()->get());

			        }
			        foreach ( $vvt_types as $vvt_type ) {

				        $articles = $articles->concat($vvt_type->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
						        [ 'category_id', $category ]
					        ] )->whereIn( 'report_id', $reports )->active()->get());

			        }
			        foreach ( $personalities as $personality ) {

				        $articles = $articles->concat($personality->articles()->where( [
						        [ 'date_start', '>=', $start_period ],
						        [ 'date_end', '<=', $end_period ],
						        [ 'category_id', $category ]
					        ] )->whereIn( 'report_id', $reports )->active()->get());

			        }

		        //отбираем недельные статьи которые вытянулсь на каждый тег
			        if ( isset( $articles ) ) {
				        //группируем все стаьи
				        foreach ( $articles->groupBy( 'id' ) as $value ) {
					        //если одна запись вытянулась на каждый тег
					        if ( $value->count() == $tags_count ) {
						        //добавляем ее в массив, передаваемый во вьюху
						        $strong = $strong->concat($value);
					        }
				        };
			        }

			        $articles = isset( $strong ) ? $strong : collect( [] );
			        $articles = $this->paginate($articles);
			        $articles->appends($request->all())->setPath('search');
        }
    }
	        }


			$isadvantage  =true;

        return view('user.advan_search_result', compact('articles', 'report_type', 'start_period', 'end_period', 'countries', 'companies', 'personalities', 'vvt_types','isadvantage'));
    }

	public function search_choose(Request $request)
	{
		if(count($request->id)){
			$articles = ArticleReports::whereIn('id',$request->id)->get();
		}
		$choose = true;
		return view('user.advan_search_result', compact('articles','choose'));
	}

    public function findbytagsinalltables ( $countries, $companies, $vvt_types, $personalities, $start_period, $end_period, &$articles ) {

	    $articles = collect();

        //поиск всех статей содержащих указанные метки и упаковывание в ассоциативный массив
        foreach ( $countries as $country ) {
	        $articles = $articles->concat($country->articles()->where( [
              ['date_start', '>=', $start_period],
              ['date_end', '<=', $end_period],
            ])->active()->get() );

        }
        foreach ( $companies as $company ) {
	        $articles = $articles->concat($company->articles()->where( [
	            ['date_start', '>=', $start_period],
	            ['date_end', '<=', $end_period],
            ])->active()->get() );

        }
        foreach ( $vvt_types as $vvt_type ) {
	        $articles = $articles->concat($vvt_type->articles()->where( [
	            ['date_start', '>=', $start_period],
	            ['date_end', '<=', $end_period],
            ])->active()->get() );

        }
        foreach ( $personalities as $personality ) {
	        $articles = $articles->concat($personality->articles()->where( [
	            ['date_start', '>=', $start_period],
	            ['date_end', '<=', $end_period],
            ])->active()->get() );

        }

        return $articles;
    }

	public function paginate($items, $perPage = 40, $page = null, $options = [])
	{
		$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
		$items = $items instanceof Collection ? $items : Collection::make($items);
		return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
	}

    public function bug(Request $request){

    	if($request) {

    		$theme = $request->input('theme');
    		$message = $request->input('text');

		    if ( $request->file('file') ) {

		    	$file = $request->file('file');

				$fileName = time() . '_' . $file->getClientOriginalName();
				$file->storeAs('email_photo', $fileName, ['disk' => 'bsvt']);

		    }

	    }
		switch ($theme){
			case 'info':
				$theme_mail = 'Информация на сайте';
				break;
			case 'error':
				$theme_mail = 'Ошибка в работе сайта';
				break;
			case 'suggestion':
				$theme_mail = 'Предложения/пожелания по сайту';
				break;
			case 'registration':
				$theme_mail = 'Регистрация/авторизация';
				break;
			case 'other':
				$theme_mail = 'Другие вопросы и комментарии';
				break;
		}
			    $objMail = new \stdClass();
			    $objMail->url = $request->input('url');
			    $objMail->subject= $theme_mail;
			    $objMail->text = $message;
			    $objMail->sender = Auth::user()->name." ".Auth::user()->surname;
			    $objMail->email = Auth::user()->email;
			    if(isset($fileName)) {

				    $objMail->path = $fileName;

			    }



	    Mail::to("lutchin@gmail.com")
	        ->cc("it@bsvt.by")
	        ->send(new SendEmail($objMail));

    	return redirect()->back()->with('status', 'Сообщение отправлено');
    }

    public function indexes() {

    	ArticleReports::putMapping($ignoreConflicts = true);
    	ArticleReports::addAllToIndex();

	    return redirect()->to('/report');

    }
}
