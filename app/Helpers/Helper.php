<?php

namespace App\Helpers;
use PragmaRX\Tracker\Vendor\Laravel\Models\Log;
use PragmaRX\Tracker\Vendor\Laravel\Models\Path;
use App\ReportType;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

            $logs = $session->log->where('method','GET'); //only get QUERY

            $timestamps = array_reverse($logs->pluck('updated_at')->toArray());

            $paths_id = array_merge($paths_id,$logs->pluck('path_id')->toArray());

            $paths_id = array_unique($paths_id);

            if(count($timestamps)>1) {

                for($i = 0; $i < count($timestamps)-1; $i++){

                    $times[] = $timestamps[$i+1]->diffInSeconds($timestamps[$i]);

                }

            }

        }

        $paths = Path::whereIn('id',$paths_id)->select('path')->get();

        $cats_rus = [];

        $paths = $paths->pluck('path')->toArray();

        $path = implode(" ",$paths);

        foreach (self::$report_types as $slug=>$report_type) {
            if(strpos($path,$slug)!==false) {
                $cats_rus[] = $report_type['title'];
            }
        }

//        dump($times);
        $sum = array_sum($times);
//        dump($sum);
        $sum?$average=round($sum/count($times),2):$average=0;
//        dump($average);

        return [$cats_rus,$sum,$average,$paths];

    }

    public static function logsCount($sessions)
    {
        $count = 0;
        foreach ($sessions as $session) {
            $logs = $session->log->where('method','GET'); //only get QUERY

            $count += $logs->count();
        }
        return $count;
    }

    public static function paginate($items, $perPage = 40, $page = null, $options = [])
    {

       	$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public static function device($row)
    {
        $model = ($row->device && $row->device->model && $row->device->model !== 'unavailable' ? '['.$row->device->model.']' : '');

        $platform = ($row->device && $row->device->platform ? ' ['.trim($row->device->platform.' '.$row->device->platform_version).']' : '');

        $mobile = ($row->device && $row->device->is_mobile ? ' [mobile device]' : '');

        return $model || $platform || $mobile
            ? $row->device->kind.' '.$model.' '.$platform.' '.$mobile
            : '';
    }

    public static function country($row)
    {
        $cityName = $row->geoip && $row->geoip->city ? ' - '.$row->geoip->city : '';

        $countryName = ($row->geoip ? $row->geoip->country_name : '').$cityName;

        $countryCode = strtolower($row->geoip ? $row->geoip->country_code : '');

        $flag = $countryCode
            ? "<span class=\"f16\"><span class=\"flag $countryCode\" alt=\"$countryName\" /></span></span>"
            : '';

        return "$flag $countryName";
    }

}


Helper::$report_types = ReportType::select(['title','slug'])->get()->keyBy('slug')->toArray();