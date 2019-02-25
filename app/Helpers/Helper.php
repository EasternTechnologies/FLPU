<?php

namespace App\Helpers;
use PragmaRX\Tracker\Vendor\Laravel\Models\Log;
use PragmaRX\Tracker\Vendor\Laravel\Models\Path;
use App\ReportType;

class Helper
{

    static $report_types;


    public static function getMonthText($m)
    {
        switch ( $m ) {
            case 1:
                $m_name = 'январь';
                break;
            case 2:
                $m_name = 'февраль';
                break;
            case 3:
                $m_name = 'март';
                break;
            case 4:
                $m_name = 'апрель';
                break;
            case 5:
                $m_name = 'май';
                break;
            case 6:
                $m_name = 'июнь';
                break;
            case 7:
                $m_name = 'июль';
                break;
            case 8:
                $m_name = 'август';
                break;
            case 9:
                $m_name = 'сентябрь';
                break;
            case 10:
                $m_name = 'октябрь';
                break;
            case 11:
                $m_name = 'ноябрь';
                break;
            case 12:
                $m_name = 'декабрь';
                break;
            default:
                $m      = "Ошибка даты";
                $m_name = "";
                break;

        }

        return $m_name;
    }

    public static function logsInfo($sessions)
    {


        $times = [];

        $paths_id = [];

        foreach ($sessions as $session) {

            $logs = $session->log;

            $timestamps = array_reverse($logs->pluck('updated_at')->toArray());

            $paths_id = array_merge($paths_id,$logs->pluck('path_id')->toArray());

//            $paths_id[] = array_merge($paths_id,$logs->pluck('path_id')->toArray());

//            dump($paths);

//            dump($timestamps);

            if(count($timestamps)>1) {

//                dump($timestamps[1]->diffInSeconds($timestamps[0]));

                for($i = 0; $i < count($timestamps)-1; $i++){

                    $times[] = $timestamps[$i+1]->diffInSeconds($timestamps[$i]);

                }

            }

//            dump(array_reverse($logs->pluck('updated_at')->toArray()));

        }


//        dump(array_unique($paths_id));


            $paths = Path::whereIn('id',$paths_id)->select('path')->get();

//        $paths = Path::whereIn('id',$paths_id)->where(function ($query) use($cats) {
//            for ($i = 0; $i < count($cats); $i++){
//                $query->orWhere('path', 'like',  '%' . $cats[$i] .'%');
//            }
//        })->select('path')->get();

       // $paths = Path::whereIn('id',$paths_id)->where('path', 'like',  '%' . $cats[5] .'%')->select('path')->get();

        $cats_rus = [];

//        $paths = $paths->where('path', 'like',  '%login%');

        $paths = $paths->pluck('path')->toArray();
//        dump($paths->pluck('path')->toArray());
//        dump($paths);

//        echo in_array('countrycatalog',$paths->pluck('path')->toArray());

        $path = implode(" ",$paths);

//        dump(self::$report_types);

        foreach (self::$report_types as $slug=>$report_type) {
            if(strpos($path,$slug)!==false) {
                $cats_rus[] = $report_type['title'];
            }
        }

//        dump($cats_rus);

        //dump($paths->pluck('path')->toArray());

//        Path::whereIn('id',$paths_id)->get();


//        dump($times);
        $sum = array_sum($times);
//        dump($sum);

        $sum?$average=round($sum/count($times),2):$average=0;
//        dump($average);

        return [$cats_rus,$sum,$average];

    }

    public static function logsCount($sessions)
    {
        $count = 0;
        foreach ($sessions as $session) {
            $logs = $session->log;

            $count += $logs->count();
        }
        return $count;
    }
    
}


Helper::$report_types = ReportType::select(['title','slug'])->get()->keyBy('slug')->toArray();