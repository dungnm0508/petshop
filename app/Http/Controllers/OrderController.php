<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use DateTime;

class OrderController extends Controller
{
    public function getOrder(){
    	return view('layouts/admin/order');
    }
}
