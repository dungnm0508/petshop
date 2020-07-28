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
    	}else{
            $timestamp = strtotime($request->dataPost['timeCreate']);
        }
        $dt = new DateTime("@$timeStamp");
        $order->time_created = $dt->format('Y-m-d');
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
        $rangeDate = $this->getRangeDate($file_name);
        $dataShopee =  $this->exportData($file);
        $dataShopeeReport = $this->getDataShopee($dataShopee);
        // $path = $request->file('fileShopee')->path();
        // $file = file_get_contents($request->file('fileShopee')->path());
        
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $order = new Order;
        $order->distribution = 2;
        $order->total_price = $dataShopeeReport['totalPrice'];
        $order->time_created = $rangeDate['endDate'];
        $order->file_name = $file_name;
        $productData = ['rangeDate'=>$rangeDate,'productData'=>$dataShopeeReport['productData']];
        $order->product_data= json_encode($productData);
        $order->created_at = new Datetime;
        $order->save();
        return $dataShopeeReport;

    }
    public function getRangeDate($strFile){
        $re = '/\d+/m';
        $str = $strFile;

        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

        $strStartDate = $matches[0][0];
        $strEndDate = $matches[1][0];



        return [
            'startDate'=>$this->splitStrDate($strStartDate),
            'endDate'=>$this->splitStrDate($strEndDate),
        ];
    }
    public function splitStrDate($str){
        $arrStrYear = str_split($str,4);
        $year = $arrStrYear[0];
        $arrStrMonth =  str_split($arrStrYear[1],2);
        $month = $arrStrMonth[0];
        $day = $arrStrMonth[1];

        return "$year-$month-$day";

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
        for ($i = 1; $i <= $Totalrow; $i++) {
            for ($j = 0; $j < $TotalCol; $j++) {
                $data[$i - 1][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();;
            }
        }
        return $data;


    }
    public function getDataShopee($data){
          $products = array();
          $sumAmount = 0;
          $listProducts = Product::all();
          foreach ($data as $key => $element) {
            if($key == 0){
                continue;
            }
            if($element[18] == 0){
                continue;
            }
            if(preg_match('/[\[\]\'^]/', $element[0])){
                preg_match_all("^\[(.*?)\]^",$element[0],$fields, PREG_PATTERN_ORDER);
                $productName =  trim(str_replace($fields[0][0],'',$element[0]));
                $product = [];
                $product['name'] = $productName;
                $product['category'] = $this->convert_name($productName);
                $product['countSales'] =  $element[18];
                $product['countOrder'] =  $element[17];

                $products[] = $product;
            }else{
                $product = [];
                $product['name'] = $element[0];
                $product['category'] = $this->convert_name($element[0]);
                $product['countSales'] =  $element[18];
                $product['countOrder'] =  $element[17];
                $products[] = $product;

            }
            $amount = floatval(str_replace(".", "", $element[19]));
            $sumAmount += $amount;

        }
         $returnData = [
            'totalPrice' => $sumAmount,
            'productData'=> $products
        ];
        return $returnData;


    }
    // public function getDataShopee($data){
    //    $sum = 0;
    //    $countColumn = 0;
    //    $keyTotalPrice = 0;
    //    foreach ($data as $key => $row) {
    //         $countColumn = count($row);
    //         if($key == 3){
    //             foreach ($row as $key1 => $value) {
    //                 if($value == 'Tổng tiền đã thanh toán'){
    //                     $keyTotalPrice = $key1;
    //                 }
    //             }
    //         }
    //         if($key>3){
    //             $sum += (float)$row[$keyTotalPrice];
    //         }
    //     }


    //     $startDate = $data[0][$countColumn - 2];
    //     $endDate = $data[0][$countColumn - 1];

    //     $countOrder = (count($data) - 3);
    //     $returnData = [
    //         'totalPrice' => $sum,
    //         'startDate' => $startDate,
    //         'endDate' => $endDate,
    //         'countOrder'=> $countOrder
    //     ];
    //     return $returnData;
    // }
    public function test(){
        $data = $this->exportData('../storage/file/shopee/[export_report]parentskudetail20200501-20200531.xlsx');

        $products = array();
        $sumAmount = 0;
        $listProducts = Product::all();
            foreach ($data as $key => $element) {
                if($key == 0){
                    continue;
                }
                if(preg_match('/[\[\]\'^]/', $element[0])){
                    preg_match_all("^\[(.*?)\]^",$element[0],$fields, PREG_PATTERN_ORDER);
                    $productName =  trim(str_replace($fields[0][0],'',$element[0]));
                    $product = [];
                    $product['name'] = $productName;
                    $product['category'] = $this->convert_name($productName);
                    $product['countSales'] =  $element[18];
                    $products[] = $product;
                }else{
                    $product = [];
                    $product['name'] = $element[0];
                    $product['category'] = $this->convert_name($element[0]);
                    $product['countSales'] =  $element[18];
                    $products[] = $product;
                    
                }
                $amount = floatval(str_replace(".", "", $element[19]));
                $sumAmount += $amount;
                
            }
            var_dump(number_format($sumAmount));

        var_dump($products);die;
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
    public function convert_name($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
        $str = preg_replace("/( )/", '-', $str);
        return $str;
    }
}
