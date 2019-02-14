<?php

namespace App\Http\Controllers\Cabinet;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//$roles = Role::all();
	   // dd($roles);
	   $user = Auth::user();
	   
	   if(empty($user->roles)) {
	   	 return view('cabinet.home',['layouts' => 'layouts.app']);
	   }
	   
	   foreach($user->roles as $role){
	   	
		$role_title = $role->title;
		   
	   }
	   
	   if(empty($role_title)) {
	   	 $layouts = 'layouts.app';
	   }
	   else {
	   	if($role_title == 'admin'):
		   $layouts = 'layouts.admin';
	   else:
		   $layouts = 'layouts.app';
	   endif;
	   }
	
      return view('cabinet.home',['layouts' => $layouts]);
    }
}
