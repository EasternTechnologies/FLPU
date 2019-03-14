<?php

namespace PragmaRX\Tracker\Vendor\Laravel\Controllers;

use App\Helpers\Helper;
use Bllim\Datatables\Facade\Datatables;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use PragmaRX\Tracker\Vendor\Laravel\Facade as Tracker;
use PragmaRX\Tracker\Vendor\Laravel\Support\Session;
use Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Session as DefaultSession;
use App\User;
use App\Role;
use PragmaRX\Tracker\Support\Minutes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Shared\File as Excel;

class Stats extends Controller
{
    private $adminProperties = [
        'admin',
        'root',
        'is_admin',
        'is_root',
    ];

    protected $sort_array = [
        'all'=>'Все',
        'log'=>'Зарегистрированные',
        'unlog'=>'Гости',
        'admin'=>'Администраторы',
        'analitic'=>'Аналитики',
        'employee'=>'Сотрудники',
        'user'=>'Пользователи',
        'manager'=>'Менеджеры',
    ];

    protected $show_array = [
        5,
        10,
        25,
        50,
        100
    ];


    public function __construct()
    {
        $this->authentication = app()->make('tracker.authentication');
    }

    public function index(Session $session)
    {


        if (!$this->isAuthenticated()) {
            return View::make('pragmarx/tracker::message')->with('message', trans('tracker::tracker.auth_required'));
        }

        if (!$this->hasAdminProperty()) {
            return View::make('pragmarx/tracker::message')->with('message', trans('tracker::tracker.miss_admin_prop'));
        }

       // if (!$this->isAdmin()) {
	        if (!$this->authentication->user()->isanalyst()) {
            return View::make('pragmarx/tracker::message')->with('message', trans('tracker::tracker.not_admin'));
        }

//        dump($session->getValue('page'));

//        $session->getValue('pages')?$page_link =$session->getValue('pages'):$page_link='visits';
//        dd($session->getValue('page'));
        $session->getValue('pages')?$page_link =$session->getValue('pages'):$page_link='visits';
        return $this->showPage($session, $page_link);
    }

    /**
     * @param Session $session
     */
    public function showPage($session, $page)
    {
        $me = $this;

        if (method_exists($me, $page)) {
            return $this->$page($session);
        }
    }

    public function visits(Session $session)
    {

        $start = Input::get('start_date');
        $end = Input::get('end_date');


        $range = new Minutes();

        if(empty($start) && empty($end)) {
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();

            $range->setStart($start);
            $range->setEnd($end);
        }
        elseif(!empty($start) && empty($end)) {
            $end = Carbon::now()->endOfDay();

            $range->setStart(Carbon::createFromTimestamp(strtotime($start))->startOfDay());
            $range->setEnd($end);
        }
        elseif(empty($start) && !empty($end)) {
            $start = Carbon::createFromTimestamp(strtotime($end))->startOfDay();

            $range->setStart($start);
            $range->setEnd(Carbon::createFromTimestamp(strtotime($end))->endOfDay());
        }
        else {
            $range->setStart(Carbon::createFromTimestamp(strtotime($start))->startOfDay());
            $range->setEnd(Carbon::createFromTimestamp(strtotime($end))->endOfDay());
        }

        $show = Input::get('show');
        if(empty($show)) $show = 25;
        $name = Input::get('name');
        $sort = Input::get('sort');
        if(empty($sort)) $sort = 'all';

        $query = Tracker::sessions($range, false);

        $query->select([
            'id',
            'uuid',
            'user_id',
            'device_id',
            'agent_id',
            'client_ip',
            'referer_id',
            'cookie_id',
            'geoip_id',
            'language_id',
            'is_robot',
            'updated_at',
        ]);

        $users = array_unique($query->pluck('user_id')->toArray());

        $users_all = User::whereIn('id',$users)->get();


        switch ($sort) {

            case 'log': $query->where('user_id','!=',null); break;

            case 'unlog':  $query->where('user_id',null); break;

            case 'admin': {
               $users = Role::where('id',1)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
            case 'analitic': {
               $users = Role::where('id',5)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
            case 'employee': {
                $users = Role::where('id',3)->first()->users->pluck('id')->toArray();$query->whereIn('user_id',$users); break;
            }
            case 'user': {
                $users = Role::where('id',4)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
            case 'manager': {
                $users = Role::where('id',2)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
        }

        if($name) {
            $users_name = User::where('surname','like','%'.$name.'%')->orWhere('name','like','%'.$name.'%')->get()->pluck('id')->toArray();
            $query->whereIn('user_id',$users_name);
        }

        if(Input::get('ajax')) {
            $query->where('user_id',Input::get('id'));
        }
//

        $results = $query->get()->groupBy([
            function($date) {
            return Carbon::parse($date->updated_at)->format('d.m.Y');
        },
            'user_id',
        ]);


//        $results = $query->groupBy([DB::raw('DATE(updated_at)'),'user_id',])->paginate(5);

//        dump($query->groupBy([DB::raw('DATE(updated_at)'),'user_id',])->paginate(5));
//        dump($query->groupBy([DB::raw('DATE(updated_at)'),'user_id',])->paginate(5)[0]->log instanceof Collection);

        Input::get('page')?$page=Input::get('page'):$page=1;
//
        $new_result = collect([]);
//
//        foreach ($results as $session) {
//
//                if($session->user) $name = $session->user->name.' '.$session->user->surname.
//                    " \n".$session->user->email; else $name = '';
//
////                $data = Helper::logsInfo($user);
//
//                $browser = '';
//                if($session->agent) {
//                    $browser = $session->agent->browser.' ('.$session->agent->browser_version.')';
//                }
//
//                $new_result[] = [
//                    'date'=>$session->update_at,
//                    'name'=>$name,
//                    'id'=>$id,
//                    'ip'=>$user[0]->client_ip,
//                    'country'=>Helper::country($user[0]),
//                    'device'=>Helper::device($user[0]),
//                    'browser'=>$browser,
//                    'categories'=>$data[0],
//                    'count'=>Helper::logsCount($user),
//                    'paths'=>$data[3],
//                    'sum'=>gmdate('z:H:i:s',$data[1]),
//                    'average'=>gmdate('z:H:i:s',$data[2])
//                ];
//        }




        foreach ($results as $key=>$users_collection) {
            foreach ($users_collection as $id=>$user) {

                if($id) $name = $users_all->where('id',$id)->first()->name.' '.$users_all->where('id',$id)->first()->surname.
                    " \n".$users_all->where('id',$id)->first()->email; else $name = '';

                $data = Helper::logsInfo($user);

                $browser = '';
                  if($user[0]->agent) {
                      $browser = $user[0]->agent->browser.' ('.$user[0]->agent->browser_version.')';
                }

                $new_result[] = [
                    'date'=>$key,
                    'name'=>$name,
                    'id'=>$id,
                    'ip'=>$user[0]->client_ip,
                    'country'=>Helper::country($user[0]),
                    'device'=>Helper::device($user[0]),
                    'browser'=>$browser,
                    'categories'=>$data[0],
                    'count'=>Helper::logsCount($user),
                    'paths'=>$data[3],
                    'sum'=>gmdate('z:H:i:s',$data[1]),
                    'average'=>gmdate('z:H:i:s',$data[2])
                ];
            }
        }


//        dump($new_result);

        $paginate = Helper::paginate($new_result,$show,$page);

        $paginate->appends(Input::toArray())->setPath('stats');

        if(!Input::get('ajax')) session(['table'=>$new_result]);

//        dump($paginate);

//        dump($paginate);

        if(Input::get('ajax')) {
            return View::make('pragmarx/tracker::full_table')->with('results',$new_result );
        }

        $title = 'Посещения пользователей за период c '.date('d.m.Y',strtotime($start)).' по '.date('d.m.Y',strtotime($end));

        return View::make('pragmarx/tracker::index')
            ->with('sessions', Tracker::sessions($session->getMinutes()))
            ->with('title', $title)
            ->with('username_column', Tracker::getConfig('authenticated_user_username_column'))
            //->with('datatables_data', $datatables_data)
            ->with('users_array',$users_all)
            ->with('sort_array',$this->sort_array)->with('results',$paginate )->with('show_array',$this->show_array);
    }

    public function excel()
    {
        $results = session('table');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setCellValue('A1', 'Статистика пользователей');
        $sheet->mergeCells('A1:F1');

        $sheet->setCellValue('A2', 'Дата');
        $sheet->setCellValue('B2', 'Пользователь');
        $sheet->setCellValue('C2', 'Разделы');
        $sheet->setCellValue('D2', 'Кол-во материалов');
        $sheet->setCellValue('E2', 'Общее время');
        $sheet->setCellValue('F2', 'Среднее время');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        $row = 3;

        foreach($results as $result){

                $sheet->setCellValue('A'.$row, $result['date']);
                if(empty($result['name']))
                    $sheet->setCellValue('B'.$row, 'Гость');
                else
                {
                    $sheet->setCellValue('B'.$row, $result['name']);
                }

                $cats = implode(" \n",$result['categories']);

                $sheet->setCellValue('C'.$row, $cats);

                $sheet->setCellValue('D'.$row, $result['count']);
                $sheet->setCellValue('E'.$row, $result['sum']);
                $sheet->setCellValue('F'.$row, $result['average']);
                $sheet->getRowDimension($row)->setRowHeight(-1);
                $row++;
        }

        $sheet->getStyle('C1:C'.$row)->getAlignment()->setWrapText(true);
        $writer = new Xlsx($spreadsheet);
	    Excel::setUseUploadTempDirectory(true);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="common.xlsx"');
        $writer->save("php://output");
        exit();

    }

    public function excelv2()
    {
        $results = session('table');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Статистика пользователей');
        $sheet->mergeCells('A1:F1');

        $sheet->setCellValue('A2', 'Дата');
        $sheet->setCellValue('B2', 'Пользователь');
        $sheet->setCellValue('C2', 'IP Адрес');
        $sheet->setCellValue('D2', 'Страна');
        $sheet->setCellValue('E2', 'Устройство');
        $sheet->setCellValue('F2', 'Браузер');
        $sheet->setCellValue('G2', 'Разделы');
        $sheet->setCellValue('H2', 'Кол-во материалов');
        $sheet->setCellValue('I2', 'Общее время');
        $sheet->setCellValue('J2', 'Среднее время');

//        $sheet->getStyle('A1')->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

        for ($i= 'A';$i<='J';$i++) {
            if($i!='G'){
                $sheet->getColumnDimension($i)->setAutoSize(true);
            }
            else {
                $sheet->getColumnDimension($i)->setWidth(40);
            }
        }

        $row = 3;

        foreach($results as $result){

                $sheet->setCellValue('A'.$row, $result['date']);
                if(empty($result['name']))
                    $sheet->setCellValue('B'.$row, 'Гость');
                else
                {
                    $sheet->setCellValue('B'.$row, $result['name']);
                }

                $sheet->setCellValue('C'.$row, $result['ip']);
                $sheet->setCellValue('D'.$row, strip_tags($result['country'] ));
                $sheet->setCellValue('E'.$row, $result['device']);
                $sheet->setCellValue('F'.$row, $result['browser']);
                $cats = implode(" \n",$result['categories']);
                $sheet->setCellValue('G'.$row, $cats);

                $sheet->setCellValue('H'.$row, $result['count']);
                $sheet->setCellValue('I'.$row, $result['sum']);
                $sheet->setCellValue('J'.$row, $result['average']);
                $sheet->getRowDimension($row)->setRowHeight(-1);
                $row++;

        }

        $sheet->getStyle('C1:C'.$row)->getAlignment()->setWrapText(true);
        $writer = new Xlsx($spreadsheet);

	    Excel::setUseUploadTempDirectory(true);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="detail.xlsx"');
        $writer->save("php://output");
        exit();
    }

    public function excel_summary()
        
    {
        $results = session('table');
        $start = session('start_period');
        $end = session('end_period');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $title = 'Сводка статистики за период c '.date('d.m.Y',strtotime($start)).' по '.date('d.m.Y',strtotime($end));

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1');

        $sheet->setCellValue('A2', 'Пользователь');
        $sheet->setCellValue('B2', 'Разделы');
        $sheet->setCellValue('C2', 'Кол-во материалов');
        $sheet->setCellValue('D2', 'Общее время');
        $sheet->setCellValue('E2', 'Среднее время');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $row = 3;

        foreach($results as $result){

            if(empty($result['name']))
                $sheet->setCellValue('A'.$row, 'Гость');
            else
            {
                $sheet->setCellValue('A'.$row, $result['name']);
            }

            $cats = implode(" \n",$result['categories']);

            $sheet->setCellValue('B'.$row, $cats);

            $sheet->setCellValue('C'.$row, $result['count']);
            $sheet->setCellValue('D'.$row, $result['sum']);
            $sheet->setCellValue('E'.$row, $result['average']);
            $sheet->getRowDimension($row)->setRowHeight(-1);
            $row++;
        }

        $sheet->getStyle('B1:B'.$row)->getAlignment()->setWrapText(true);
        $writer = new Xlsx($spreadsheet);
        Excel::setUseUploadTempDirectory(true);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="summary.xlsx"');
        $writer->save("php://output");
        exit();
    }

    public function summary_stats()
    {

        $sort = Input::get('sort');
        if(empty($sort)) $sort = 'all';

        $start = Input::get('start_date');
        $end = Input::get('end_date');


        $range = new Minutes();

        if(empty($start) && empty($end)) {
            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();

            $range->setStart($start);
            $range->setEnd($end);
        }
        elseif(!empty($start) && empty($end)) {
            $end = Carbon::now()->endOfDay();

            $range->setStart(Carbon::createFromTimestamp(strtotime($start))->startOfDay());
            $range->setEnd($end);
        }
        elseif(empty($start) && !empty($end)) {
            $start = Carbon::createFromTimestamp(strtotime($end))->startOfDay();

            $range->setStart($start);
            $range->setEnd(Carbon::createFromTimestamp(strtotime($end))->endOfDay());
        }
        else {
            $range->setStart(Carbon::createFromTimestamp(strtotime($start))->startOfDay());
            $range->setEnd(Carbon::createFromTimestamp(strtotime($end))->endOfDay());
        }

        $query = Tracker::sessions($range, false);

        $query->select([
            'id',
            'uuid',
            'user_id',
            'referer_id',
            'cookie_id',
            'language_id',
            'is_robot',
            'updated_at',
        ]);

        $users = array_unique($query->pluck('user_id')->toArray());

        $users_all = User::whereIn('id',$users)->get();


        switch ($sort) {

            case 'log': $query->where('user_id','!=',null); break;

            case 'unlog':  $query->where('user_id',null); break;

            case 'admin': {
                $users = Role::where('id',1)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
            case 'analitic': {
                $users = Role::where('id',5)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
            case 'employee': {
                $users = Role::where('id',3)->first()->users->pluck('id')->toArray();$query->whereIn('user_id',$users); break;
            }
            case 'user': {
                $users = Role::where('id',4)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
            case 'manager': {
                $users = Role::where('id',2)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
            }
        }

        $results = $query->get()->groupBy('user_id');


        $results = $results->sortKeys();

        $new_result = collect([]);

            foreach ($results as $id=>$user) {

                if($id) $name = $users_all->where('id',$id)->first()->name.' '.$users_all->where('id',$id)->first()->surname.
                    " \n".$users_all->where('id',$id)->first()->email; else $name = '';
                $data = Helper::logsInfo($user);
                $new_result[] = [
                    'name'=>$name,
                    'id'=>$id,
                    'categories'=>$data[0],
                    'count'=>Helper::logsCount($user),
                    'paths'=>$data[3],
                    'sum'=>gmdate('z:H:i:s',$data[1]),
                    'average'=>gmdate('z:H:i:s',$data[2])
                ];
            }

        if(!Input::get('ajax')) session(['table'=>$new_result]);

        $summary = true;

        if(!Input::get('ajax')) session(['start_period'=>$start]);
        if(!Input::get('ajax')) session(['end_period'=>$end]);

        $title = 'Сводка статистики за период c '.date('d.m.Y',strtotime($start)).' по '.date('d.m.Y',strtotime($end));

        return View::make('pragmarx/tracker::index')
            ->with('title', $title)->with('results',$new_result)
            ->with('summary',$summary)->with('sort_array',$this->sort_array);
    }
    
    public function log($uuid)
    {
        $session = Tracker::sessionLog($uuid);

        return View::make('pragmarx/tracker::log')
                ->with('log', Tracker::sessionLog($uuid))
                ->with('uuid', $uuid)
                ->with('title', 'log');
    }

    public function summary()
    {
        return View::make('pragmarx/tracker::summary')
                ->with('title', ''.trans('tracker::tracker.page_views_summary').'');
    }

    public function apiPageviews(Session $session)
    {
        return Tracker::pageViews($session->getMinutes())->toJson();
    }

    public function apiPageviewsByCountry(Session $session)
    {
        return Tracker::pageViewsByCountry($session->getMinutes())->toJson();
    }

    public function apiLog($uuid)
    {
        $query = Tracker::sessionLog($uuid, false);

        $query->select([
                            'id',
                            'session_id',
                            'method',
                            'path_id',
                            'query_id',
                            'route_path_id',
                            'is_ajax',
                            'is_secure',
                            'is_json',
                            'wants_json',
                            'error_id',
                            'created_at',
                        ]);

        return Datatables::of($query)
            ->edit_column('route_name', function ($row) {
                $path = $row->routePath;

                return    $row->routePath
                            ? $row->routePath->route->name.'<br>'.$row->routePath->route->action
                            : ($row->path ? $row->path->path : '');
            })

            ->edit_column('route', function ($row) {
                $route = null;

                if ($row->routePath) {
                    foreach ($row->routePath->parameters as $parameter) {
                        $route .= ($route ? '<br>' : '').$parameter->parameter.'='.$parameter->value;
                    }
                }

                return $route;
            })

            ->edit_column('query', function ($row) {
                $query = null;

                if ($row->logQuery) {
                    foreach ($row->logQuery->arguments as $argument) {
                        $query .= ($query ? '<br>' : '').$argument->argument.'='.$argument->value;
                    }
                }

                return $query;
            })

            ->edit_column('is_ajax', function ($row) {
                return    $row->is_ajax ? 'yes' : 'no';
            })

            ->edit_column('is_secure', function ($row) {
                return    $row->is_secure ? 'yes' : 'no';
            })

            ->edit_column('is_json', function ($row) {
                return    $row->is_json ? 'yes' : 'no';
            })

            ->edit_column('wants_json', function ($row) {
                return    $row->wants_json ? 'yes' : 'no';
            })

            ->edit_column('error', function ($row) {
                return    $row->error ? 'yes' : 'no';
            })

            ->make(true);
    }

    public function users(Session $session)
    {
        return View::make('pragmarx/tracker::users')
            ->with('users', Tracker::users($session->getMinutes()))
            ->with('title', ''.trans('tracker::tracker.users').'')
            ->with('username_column', Tracker::getConfig('authenticated_user_username_column'));
    }

    private function events(Session $session)
    {
        return View::make('pragmarx/tracker::events')
            ->with('events', Tracker::events($session->getMinutes()))
            ->with('title', ''.trans('tracker::tracker.events').'');
    }

    public function errors(Session $session)
    {
        return View::make('pragmarx/tracker::errors')
            ->with('error_log', Tracker::errors($session->getMinutes()))
            ->with('title', ''.trans('tracker::tracker.errors').'');
    }

    public function apiErrors(Session $session)
    {
        $query = Tracker::errors($session->getMinutes(), false);

        $query->select([
                            'id',
                            'error_id',
                            'session_id',
                            'path_id',
                            'updated_at',
                        ]);

        return Datatables::of($query)
                ->edit_column('updated_at', function ($row) {
                    return "{$row->updated_at->diffForHumans()}";
                })
                ->make(true);
    }

    public function apiEvents(Session $session)
    {
        $query = Tracker::events($session->getMinutes(), false);

        return Datatables::of($query)->make(true);
    }

    public function apiUsers(Session $session)
    {
        $username_column = Tracker::getConfig('authenticated_user_username_column');

        return Datatables::of(Tracker::users($session->getMinutes(), false))
                ->edit_column('user_id', function ($row) use ($username_column) {
                    return "{$row->user->$username_column}";
                })
                ->edit_column('updated_at', function ($row) {
                    return "{$row->updated_at->diffForHumans()}";
                })
                ->make(true);
    }

    public function apiVisits(Session $session)
    {

//        $name = '';
//
//        $sort = DefaultSession::get('sort');
//        $name = DefaultSession::get('name');
//
//        $username_column = Tracker::getConfig('authenticated_user_username_column');
//
//        $query = Tracker::sessions($session->getMinutes(), false);
//
//        $query->select([
//            'id',
//            'uuid',
//            'user_id',
//            'device_id',
//            'agent_id',
//            'client_ip',
//            'referer_id',
//            'cookie_id',
//            'geoip_id',
//            'language_id',
//            'is_robot',
//            'updated_at',
//        ]);
//
//
//        switch ($sort) {
//
//            case 'log': $query->where('user_id','!=',null); break;
//
//            case 'unlog':  $query->where('user_id',null); break;
//
//            case 'admin': {
//               $users = Role::where('id',1)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
//            }
//            case 'analitic': {
//               $users = Role::where('id',5)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
//            }
//            case 'employee': {
//                $users = Role::where('id',3)->first()->users->pluck('id')->toArray();$query->whereIn('user_id',$users); break;
//            }
//            case 'user': {
//                $users = Role::where('id',4)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
//            }
//            case 'manager': {
//                $users = Role::where('id',2)->first()->users->pluck('id')->toArray(); $query->whereIn('user_id',$users);break;
//            }
//        }
//        if($name) {
//            $users_name = User::where('surname','like','%'.$name.'%')->get()->pluck('id')->toArray();
//            $query->whereIn('user_id',$users_name);
//        }
//
//
//        return Datatables::of($query)
//                ->edit_column('id', function ($row) use ($username_column) {
//                    $uri = route('tracker.stats.log', $row->uuid);
//
//                    return '<a href="'.$uri.'">'.$row->id.'</a>';
//                })
//
//                ->add_column('country', function ($row) {
//                    $cityName = $row->geoip && $row->geoip->city ? ' - '.$row->geoip->city : '';
//
//                    $countryName = ($row->geoip ? $row->geoip->country_name : '').$cityName;
//
//                    $countryCode = strtolower($row->geoip ? $row->geoip->country_code : '');
//
//                    $flag = $countryCode
//                            ? "<span class=\"f16\"><span class=\"flag $countryCode\" alt=\"$countryName\" /></span></span>"
//                            : '';
//
//                    return "$flag $countryName";
//                })
//
//                ->add_column('user', function ($row) use ($username_column) {
//	                return $row->user ? $row->user->name." ".$row->user->surname."<br>".
//                        $row->user->email : 'Гость';
//                })
//
//                ->add_column('role', function ($row) use ($username_column) {
//                    return $row->user ? $row->user->roles()->first()->name: '';
//                })
//
//		        ->add_column('pages', function ($row) use ($username_column) {
//			       // return $row->log ? $row->log : '';
//			        if($row->log) {
//                        if(count($row->log) > 1 ) {
//                                $pages = '';
//
//
////                            dump($row->log);
////                            dd($row->log->pluck('id')->toArray());
//
////                                dd($row->log->count());
////                                dd($row->log[0]->path);
//
//                            foreach ( $row->log as $key => $log ) {
//
//                                $path = $log->path->path;
//
//                                if($key == 0 ) {
//                                  $pages .= '<div class="show-list"><a href="'.$path.'">'.$path.'</a><span> - '.date("H:i:s",strtotime($log->updated_at)).'</span><br></div>';
//                                } elseif ($key == 1) {
//                                  $pages .= '<div class="hide-list"><a href="'.$path.'">'.$path.'</a><span> - '.date("H:i:s",strtotime($log->updated_at)).'</span><br>';
//                                } else {
//                                  $pages .= '<a href="'.$path.'">'.$path.'</a><span> - '.date("H:i:s",strtotime($log->updated_at)).'</span><br>';
//                                }
//
//                            }
//                          $pages .= '</div>';
//                      } else {
//                $pages = '';
//                foreach ( $row->log as $log ) {
//
//					        $pages .= '<a href="'.$log->path->path.'">'.$log->path->path.'</a><span> - '.date("H:i:s",strtotime($log->updated_at)).'</span><br>';
//				        }
//              }
//
//                return $pages;
//
//			        } else {
//				        return '';
//			        }
//		        })
//
//                ->add_column('device', function ($row) use ($username_column) {
//                    $model = ($row->device && $row->device->model && $row->device->model !== 'unavailable' ? '['.$row->device->model.']' : '');
//
//                    $platform = ($row->device && $row->device->platform ? ' ['.trim($row->device->platform.' '.$row->device->platform_version).']' : '');
//
//                    $mobile = ($row->device && $row->device->is_mobile ? ' [mobile device]' : '');
//
//                    return $model || $platform || $mobile
//                            ? $row->device->kind.' '.$model.' '.$platform.' '.$mobile
//                            : '';
//                })
//
//                ->add_column('browser', function ($row) use ($username_column) {
//                    return $row->agent && $row->agent
//                            ? $row->agent->browser.' ('.$row->agent->browser_version.')'
//                            : '';
//                })
//
//                ->add_column('language', function ($row) use ($username_column) {
//                    return $row->language && $row->language
//                        ? $row->language->preference
//                        : '';
//                })
//
//                ->add_column('referer', function ($row) use ($username_column) {
//                    return $row->referer ? $row->referer->url : '';
//                })
//
//                ->add_column('pageViews', function ($row) use ($username_column) {
//                    return $row->page_views;
//                })
//
//                ->add_column('lastActivity', function ($row) use ($username_column) {
//                    //return $row->updated_at->diffForHumans();
//	                return date("d.m.Y",strtotime($row->updated_at));
//                })
//
//                ->make(true);
    }

    private function isAuthenticated()
    {
        return $this->authentication->check();
    }

    private function hasAdminProperty()
    {
        $user = $this->authentication->user();

        foreach ($this->adminProperties as $property) {
            $propertyCamel = camel_case($property);

            if (
                    isset($user->$property) ||
                    isset($user->$propertyCamel) ||
                    method_exists($user, $property) ||
                    method_exists($user, $propertyCamel)
            ) {
                return true;
            }
        }

        return false;
    }

    private function isAdmin()
    {
        $user = $this->authentication->user();

        foreach ($this->adminProperties as $property) {
            $propertyCamel = camel_case($property);

            if (
                (isset($user->$property) && $user->$property) ||
                (isset($user->$propertyCamel) && $user->$propertyCamel) ||
                (method_exists($user, $property) && $user->$property()) ||
                (method_exists($user, $propertyCamel) && $user->$propertyCamel())
            ) {
                return true;
            }
        }

        return false;
    }
}
