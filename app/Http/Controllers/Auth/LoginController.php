<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct () {
        $this->middleware('guest')->except('logout');
    }

    public function logout ( Request $request ) {

	    $userIdAll = str_replace(Auth::user()->id.',','',Redis::get('user:all:id'));

	    Redis::set('user:all:id', $userIdAll);

	    $this->guard()->logout();

	    $request->session()->invalidate();

	    return $this->loggedOut($request) ?: redirect('/');

    }

	protected function authenticated ( Request $request, $user ) {

	    if ($this->getIdUser($user->id))
	    {
		    $this->guard()->logout();

		    $request->session()->invalidate();

		    return $this->loggedOut($request) ?: redirect('/login')->with('status_access', 'Пользователь авторизован. Вход под темже именем не возможен.');

	    } else {

	    	$userIdAll = Redis::get('user:all:id').$user->id.',';

		    Redis::set('user:all:id', $userIdAll);
	    }

        if ( $user->isadmin() ) {
            return redirect()->to('/report');
        }
        if ( $user->isanalyst() ) {
            return redirect()->to('/report');
        }
        if ( $user->ismanager() ) {
            return redirect()->to('/manager');
        }

    }

    protected function getIdUser($userId){

	    $userIdAll = explode(',', Redis::get('user:all:id'));

	    if(array_search($userId, $userIdAll) !== false ) {

		    return true;

	    } else {

		    return false;

	    }



    }
}
