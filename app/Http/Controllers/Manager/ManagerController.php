<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{

    public function __construct () {
        $this->middleware('auth');
    }

    public function index () {

        $analyst  = Role::where('title', 'analyst')->first();
        $manager  = Role::where('title', 'manager')->first();
        $employee = Role::where('title', 'employee')->first();
        $user     = Role::where('title', 'user')->first();

        return view('manager.home', compact('analyst', 'manager', 'employee', 'user'));
    }

    public function list ( Role $role ) {
        $users = $role->users()->paginate();

        return view('manager.list', compact('role', 'users'));
    }

    public function user_info ( User $user ) {
        $role = $user->roles->first();

        return view('manager.user_info', compact('user', 'role'));
    }

    public function adduserform ( Role $role ) {

        return view('manager.add_form', compact('role'));
    }

    public function adduser ( Request $request, Role $role ) {
    	
    	$request->validate([
            'name' => 'required|max:255',
            'surname' => 'required',
            'tel1' => 'required',
            'tel2' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
		$user = USER::where('email',$request->input('email'))->first();
		//dd($user);
		if ( $user )  {
			return redirect()->to('/manager')->with('error', "Пользователь c e-mail: ". $request->input('email') ." уже существует!");
		}
			
        if ( $request->input('password') == $request->input('password_for_validate') ) {
            $user = User::create([
              'name'         => $request->input('name'),
              'surname'      => $request->input('surname'),
              'organization' => $request->input('organization'),
              'tel1'         => $request->input('tel1'),
              'tel2'         => $request->input('tel2'),
              'email'        => $request->input('email'),
              'password'     => Hash::make($request->input('password')),
            ]);
            $user->roles()->attach($role);
        }
        else {
            return redirect()->back()->with('error', 'Не совпадают пароли')->withInput();
        }

        return redirect()->to('/manager')->with('status', $role->name ." создан");

    }

    public function upduserform ( User $user ) {
        $role = $user->roles->first();

        return view('manager.updform', compact('user', 'role'));

    }

    public function upduser ( User $user, Request $request ) {
        if ( $request->input('password') == $request->input('password_for_validate') ) {
            $user->update([
              'name'         => $request->input('name'),
              'surname'      => $request->input('surname'),
              'organization' => $request->input('organization'),
              'tel1'         => $request->input('tel1'),
              'tel2'         => $request->input('tel2'),
              'email'        => $request->input('email'),
              'password'     => Hash::make($request->input('password')),
            ]);
        }
        else {
            return redirect()->back()->with('error', 'Не совпадают пароли');
        }

        return redirect()->back()->with('status', "$user->name обновлен");
    }

    public function deluser ( User $user ) {
        $user->roles()->detach();
        $user->delete();

        return redirect()->back()->with('status', "$user->name удален");
    }
}
