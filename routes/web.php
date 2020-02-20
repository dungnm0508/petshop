<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('getDashboard');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//login logout

Route::post('login',['uses'=>'AuthController@postLogin']);

Route::get('logout',['as'=>'getLogout','uses'=>'AuthController@getLogout']);


Route::get('dashboard',['as'=>'getDashboard','uses'=>'StatisticController@getDashboard'])->middleware('auth');




//admin 

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('products',['as'=>'getProduct','uses'=>'ProductController@getProduct']);

    Route::post('insertProduct',['as'=>'postInsertProduct','uses'=>'ProductController@postInsertProduct']);

    Route::post('deleteProduct',['as'=>'postDeleteProduct','uses'=>'ProductController@postDeleteProduct']);

    Route::post('deleteOrder',['as'=>'postDeleteOrder','uses'=>'OrderController@postDeleteOrder']);

    Route::get('orders',['as'=>'getOrder','uses'=>'OrderController@getOrder']);

    Route::post('insertOrder',['as'=>'postInsertOrder','uses'=>'OrderController@postInsertOrder']);

    Route::get('warehouse',['as'=>'getWarehouse','uses'=>'WarehouseController@getWarehouse']);

    Route::post('addOrder',['as'=>'postAddOrder','uses'=>'WarehouseController@postAddOrder']);

    Route::get('deleteArchive/{id}',['as'=>'postDeleteArchive','uses'=>'WarehouseController@deleteArchive']);

    Route::get('changeStatus/{id}',['as'=>'getChangeStatus','uses'=>'WarehouseController@getChangeStatus']);

});