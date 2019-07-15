<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function postLogin(Request $request){
    	$credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('getDashboard');
        }else{
        	return redirect()->route('login')->with(['status'=>'Log in Fail! Please try again!']);
        }
    }
    public function getLogout(){
    	Auth::logout();
    	return redirect()->route('login');
    }
}
