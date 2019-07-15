<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function getDashboard(){
    	return view('layouts/admin/dashboard');
    }
}
