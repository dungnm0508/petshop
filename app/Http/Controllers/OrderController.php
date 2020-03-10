<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use DateTime;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;


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
    public function test($file = null){

        $file = '../storage/file/shopee/test2.csv';

        $objFile = PHPExcel_IOFactory::identify($file);
        $objData = PHPExcel_IOFactory::createReader($objFile);

        //Chỉ đọc dữ liệu
        $objData->setReadDataOnly(true);

        // Load dữ liệu sang dạng đối tượng
        $objPHPExcel = $objData->load($file);

        //Lấy ra số trang sử dụng phương thức getSheetCount();
        // Lấy Ra tên trang sử dụng getSheetNames();

        //Chọn trang cần truy xuất
        $sheet = $objPHPExcel->setActiveSheetIndex(0);

        //Lấy ra số dòng cuối cùng
        $Totalrow = $sheet->getHighestRow();
        //Lấy ra tên cột cuối cùng
        $LastColumn = $sheet->getHighestColumn();

        //Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
        $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);

        //Tạo mảng chứa dữ liệu
        $data = [];

        //Tiến hành lặp qua từng ô dữ liệu
        for ($i = 2; $i <= $Totalrow; $i++) {
            for ($j = 0; $j < $TotalCol; $j++) {
                $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
            }
        }
        $sum = 0;
        foreach ($data as $key => $row) {
            if($key>2){
                $sum += (float)$row[11];
            }
        }
        echo number_format($sum) ;
    }
}
