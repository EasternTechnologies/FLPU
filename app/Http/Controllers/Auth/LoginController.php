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

	    $userId = Redis::get('user:all:'.Auth::user()->id);

	    Redis::del('user:all:'.$userId, $userId, 'EX', 1);

	    $this->guard()->logout();

	    $request->session()->invalidate();
        //$request->session()->flush();
        session_destroy();

	    return $this->loggedOut($request) ?: redirect('/');

    }

	protected function authenticated ( Request $request, $user ) {

	    if ( $this->getIdUser($user->id) )
	    {
		    $this->guard()->logout();

		    $request->session()->invalidate();

		    return $this->loggedOut($request) ?: redirect('/login')->with('status_access', 'Пользователь авторизован. Вход под тем же именем не возможен.');

	    } else {

	    	Redis::set('user:all:'.$user->id, $user->id, 'EX', 1);
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

	    $userIdNow = Redis::get('user:all:'.$userId);

	    if( $userIdNow == $userId ) {

		    return true;

	    } else {

		    return false;

	    }



    }


	protected function validateLogin(Request $request)
	{
		$this->validate($request, [
			$this->username() => 'required|string',
			'password' => 'required|string',
			'g-recaptcha-response' => 'required|recaptcha',

		]);
	}

}
