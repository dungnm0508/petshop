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
    	$orders = Order::all()->sortBy('time_created');
        // foreach ($orders as $key => $order) {
        //     $order->time_created = strtotime($order->time_created);
        //     $order->save();
        // }
    	$products = Product::all();
        $listProductName = [];
        foreach ($products as $key => $product) {
            $listProductName[] = $product->name;
        }
        return view('layouts/admin/order',compact('orders','products','listProductName'));
    }
    public function postInsertOrder(Request $request){
    	date_default_timezone_set("Asia/Ho_Chi_Minh");
    	$order = new Order;
    	$order->distribution = $request->dataPost['distribute'];
    	$order->total_price = $request->dataPost['price']*1000;
    	if(empty($request->dataPost['timeCreate'])){
    		$timestamp = time();
    		$order->time_created = $timestamp;
    	}else{
    		$order->time_created = strtotime($request->dataPost['timeCreate']);
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
    public function postImportDataShopee(Request $request){
        $file = $request->file('fileShopee');
        $file_name = $request->file('fileShopee')->getClientOriginalName();
        if (Order::where('file_name', '=',$file_name)->exists()) {
            return ['message'=>'File Đã được import! vui lòng chọn file khác'];
        }
        // $path = $request->file('fileShopee')->path();
        // $file = file_get_contents($request->file('fileShopee')->path());
        $dataShopee =  $this->exportData($file);
        $dataShopeeReport = $this->getDataShopee($dataShopee);
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $order = new Order;
        $order->distribution = 2;
        $order->total_price = $dataShopeeReport['totalPrice'];
        $timestamp = strtotime($dataShopeeReport['endDate']);
        $dt = new DateTime("@$timestamp");  
        $date =  $dt->format('Y-m-d');
        $order->time_created = $date;
        $order->file_name = $file_name;
        $order->product_data= json_encode($dataShopeeReport);
        $order->created_at = new Datetime;
        $order->save();
        return $dataShopeeReport;

    }
    public function exportData($file = null){
        if(!$file){
            $file = '../storage/file/shopee/test2.csv';
        }

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
        return $data;


    }
    public function getDataShopee($data){
       $sum = 0;
       $countColumn = 0;
       $keyTotalPrice = 0;
       foreach ($data as $key => $row) {
            $countColumn = count($row);
            if($key == 3){
                foreach ($row as $key1 => $value) {
                    if($value == 'Tổng tiền đã thanh toán'){
                        $keyTotalPrice = $key1;
                    }
                }
            }
            if($key>3){
                $sum += (float)$row[$keyTotalPrice];
            }
        }


        $startDate = $data[0][$countColumn - 2];
        $endDate = $data[0][$countColumn - 1];

        $countOrder = (count($data) - 3);
        $returnData = [
            'totalPrice' => $sum,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'countOrder'=> $countOrder
        ];
        return $returnData;
    }
    public function test(){
        $data = $this->exportData('../storage/file/shopee/test5.xls');
        $groupData = array();
        foreach ($data as $element) {
            if($element[3] == 'Hoàn thành'){
                $groupData[$element[14]][] = $element[25];
            }
        }
        $listCountProduct = [];
        foreach ($groupData as $productName => $list) {
            $countProduct = 0;
            foreach ($list as  $value) {
                 $countProduct += $value;
            }
            $listCountProduct[$productName] = $countProduct;
        }
        var_dump($listCountProduct);
       
        
    }
}
