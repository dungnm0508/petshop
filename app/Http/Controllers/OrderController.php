<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use DateTime;

class OrderController extends Controller
{
    public function getOrder(){
    	$orders = Order::all();
    	$products = Product::all();
    	return view('layouts/admin/order',compact('orders','products'));
    }
    public function postInsertOrder(Request $request){
    	date_default_timezone_set("Asia/Ho_Chi_Minh");
    	$order = new Order;
    	$order->distribution = $request->dataPost['distribute'];
    	$order->code = $request->dataPost['code'];
    	$order->total_price = $request->dataPost['price'];
    	if(empty($request->dataPost['timeCreate'])){
    		$timestamp = time();
    		$dt = new DateTime("@$timestamp");  
    		$date =  $dt->format('Y-m-d');
    		$order->time_created = $date;
    	}else{
    		$order->time_created = $request->dataPost['timeCreate'];
    	}
    	$order->product_data= json_encode($request->dataPost['productData']);
    	$order->created_at = new Datetime;
    	$order->save();
    	return ['message'=>'Thêm đơn hàng thành công!'];
    }
    public function postDeleteOrder(Request $request){
    	$order = Order::find($request->productId);
    	$order->delete();
    	return ['message'=>'Xóa sản phẩm thành công!'];
    }
}
