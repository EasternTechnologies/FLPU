<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use PragmaRX\Tracker\Vendor\Laravel\Models\Log;

class AdminController extends Controller
{

    public function users ( User $user ) {

        $sessions = \Tracker::sessions(60 * 24 * 365, FALSE)->where('user_id', $user->id)->get();
        //dd($sessions);

        foreach ( $sessions as $session ) {
            foreach ( $session->log as $log ) {
                $users[ $session->user->email ][] = $log->path->path;

            }
            $users2[ $session->user->email ] = array_count_values($users[ $session->user->email ]);
        }

        return view('admin.users', ['users' => $users2]);
    }

    public function count_visits_byroute ( $route ) {
        $data = \Tracker::logByRouteName($route)->where(function( $query )
        {
            $query->where('parameter', 'weeklyarticle')->where('value', 3);
        })->count();
        dd($data);

    }

    public function count_visits () {
        // $route = 'weekly.article'
        $logs = Log::all()->groupBy('path_id');

        foreach ( $logs as $log ) {
            //dd($log);

            $arr[ $log->first()->path->path ] = $log->count();
        }
        arsort($arr, SORT_NUMERIC);
        //dd($arr);
        return view('admin.routes', compact('arr'));
    }
}
