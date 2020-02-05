<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PragmaRX\Tracker\Vendor\Laravel\Middlewares\Tracker;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionDataCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$bag = ( new \Symfony\Component\HttpFoundation\Session\Session )->getMetadataBag();

        $max = config('session.lifetime') * 60; // min to hours conversion
        if(isset($_SESSION['pragmarx/phpsession'])){
            $last_activity = DB::table('tracker_sessions')->where('uuid',$_SESSION['pragmarx/phpsession']['tracker_session']['uuid'])->first()->updated_at;
            $last_activity_timestamp = Carbon::parse($last_activity)->timestamp;

            if (($max < (time()-$last_activity_timestamp))) {
                if( isset( Auth::user()->id ) ){
                    $userId = Redis::get('user:all:' . Auth::user()->id);

                    Redis::del('user:all:' . $userId, $userId, 'EX', 1);
                }
                $request->session()->invalidate();
                $request->session()->flush();
                session_destroy();
                //Auth::logout();
                Auth::guard()->logout();
                return  redirect('/');
            }

        }


        return $next($request);
    }
}
