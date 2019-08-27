<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product;
use DateTime;

class ProductController extends Controller
{
      public function getProduct(){
         $products =  Product::all();
         return view('layouts/admin/product',compact('products'));
      }
      public function postInsertProduct(Request $request){
         $product = new Product;
         $product->name = $request->dataPost['name'];
         $product->type_product = $request->dataPost['type'];
         $product->price = $request->dataPost['price'];
         $product->weight = $request->dataPost['weight'];
         $product->description = $request->dataPost['description'];
         $product->created_at = new Datetime;
         $product->save();
         return ['message'=>'Thêm sản phẩm thành công!'];
      }
      public function postDeleteProduct(Request $request){
         $product = Product::find($request->productId);
         $product->delete();
         return ['message'=>'Xóa sản phẩm thành công!'];
      }
      public function getProductTest(){
         $products =  Product::all();
         echo 123;die;




         
         return $products;
      }
}

?>
