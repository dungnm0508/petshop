<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Warehouse;
use Illuminate\Support\Facades\DB;
use DateTime;

class WarehouseController extends Controller
{
    public function getWarehouse(){
    	$products = Product::all();
    	$orders = DB::table('warehouse')->join('product','warehouse.product_id','=','product.id')->select('warehouse.*','product.name')->get();
    	return view('layouts/admin/warehouse',compact('products','orders'));
    }
    public function postAddOrder(Request $request){
    	$order = new Warehouse;
    	$order->product_id = $request->selectProduct;
    	$order->quatity = $request->quatity;
    	$order->unit = $request->selectUnit;
    	$order->status = 0;
    	$order->created_at = new Datetime;
    	$order->save();
    	return redirect()->route('getWarehouse');
    }
}
