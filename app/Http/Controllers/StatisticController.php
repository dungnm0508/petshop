<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StatisticController extends Controller
{
    public function getDashboard(){
    	$orders = Order::all();
    	$products = Product::all();
    	$totalPrice = 0;	
    	$months = Order::whereYear('time_created',date("Y"))->get()->groupBy(function($d) {
    		return Carbon::parse($d->time_created)->format('m');
    	});
    	$revenueOfMoth = [];
    	foreach ($months as $key => $month) {
    		$revenue = 0;
    		foreach ($month as  $order) {
    			$revenue += $order['total_price'];
    		}

    		$revenueOfMoth[intval($key)] = $revenue;
    	}

    	$array_data = [];
    	foreach ($orders as $key => $order) {
    		$totalPrice += $order->total_price;
    		$product_data = json_decode($order->product_data,true);
    		foreach ($product_data as $value) {
    			$data = [];
    			$data['name'] = $value['productName'];
    			$data['quantity'] = $value['quantity'];
    			$array_data[] = $data;
    		}

    	}


    	$arr = array();

    	foreach ($array_data as $key => $item) {
    		$arr[$item['name']][$key] = $item;
    	}

    	$dataGroup = [];
    	foreach ($arr as $key => $value) {
    		$countItem = 0;
    		foreach ($value as $item) {
    			$countItem += $item['quantity'];
    		}
    		$data = [
    			'product_name'=>$key,
    			'count'=>$countItem
    		];
    		$dataGroup[] = $data;

    	}



    	return view('layouts/admin/dashboard',compact('dataGroup','totalPrice','orders','revenueOfMoth'));
    }
}
