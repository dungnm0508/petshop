@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('app/assets/css/lib/datatable/dataTables.bootstrap.min.css')}}">
<style type="text/css">
	/*.row-product div{
		padding:0px;
	}*/
	.minus-product{
		position: absolute;
		right: 10px;
	}
	.plus-product,.minus-product{
		cursor: pointer;
	}
	.card-body .form-group:last-child{
		margin-bottom: 0px;
	}
	.card-footer{
		text-align: center;
	}
	.tooltip{
		opacity: 1;
	}
	#myTab{
		margin-bottom: 20px;
	}
	#panel-order-shopee{
		margin-bottom: 20px;
	}
	/*auto complete css*/
	.autocomplete {
		/*the container must be positioned relative:*/
		position: relative;
		/*display: inline-block;*/
	}
	/*.autocomplete input {
		border: 1px solid transparent;
		background-color: #f1f1f1;
		padding: 10px;
		font-size: 16px;
	}*/
	/*.autocomplete input[type=text] {
		background-color: #f1f1f1;
		width: 100%;
	}*/
	
	.autocomplete-items {
		position: absolute;
		border: 1px solid #d4d4d4;
		border-bottom: none;
		border-top: none;
		z-index: 99;
		/*position the autocomplete items to be the same width as the container:*/
		top: 100%;
		left: 0;
		right: 0;
	}
	.autocomplete-items div {
		padding: 10px;
		cursor: pointer;
		background-color: #fff;
		border-bottom: 1px solid #d4d4d4;
	}
	.autocomplete-items div:hover {
		/*when hovering an item:*/
		background-color: #e9e9e9;
	}
	.autocomplete-active {
		/*when navigating through the items using the arrow keys:*/
		background-color: DodgerBlue !important;
		color: #ffffff;
	}
</style>
@endsection

@section('page', 'Đơn hàng')
@section('content')

<div class="animated fadeIn">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
					<div class="card-header"><strong>Đơn Hàng</strong><small> Form</small></div>

				<form action="" autocomplete="off" method="post" id="form-add-order" enctype="multipart/form-data">
					@csrf
					<div class="card-body card-block">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active show" id="default-tab" data-toggle="tab" href="#panel-order-default" role="tab" aria-controls="home" aria-selected="true">Đơn lẻ</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " id="shopee-tab" data-toggle="tab" href="#panel-order-shopee" role="tab" aria-controls="profile" aria-selected="false">Shopee</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="panel-order-default tab-pane fade show active" role="tabpanel" id="panel-order-default">
								<div class="form-group row row-product">
									<div class="col-lg-6">
										<div class="col col-md-3">
											<label for="select" class=" form-control-label">Sản Phẩm</label>
										</div>
										<div class="col-12 col-md-9">
											<div class="autocomplete">
												<input id="myInput" type="text" name="myCountry" placeholder="Sản phẩm" data-key='0' class="form-control productInput">
											</div>
											<!-- <select name="selectProduct" id="selectProduct" class="form-control">
												@foreach($products as $product)
												<option value="{{$product->name}}">{{$product->name}}</option>
												@endforeach
											</select> -->
										</div>
									</div>
									<div class="col-lg-6">
										<div class="col col-md-3">
											<label for="company" class=" form-control-label">Số lượng</label>
										</div>
										<div class="col-12 col-md-9">
											<input type="number"  id="quantity" placeholder="Nhập số lượng bán" class="form-control quantity" >
										</div>

									</div>
									<span class="fa fa-minus-square-o minus-product" onclick="cancelEl(event)" data-toggle="tooltip" title="Xóa sản phẩm"></span>
								</div>
								<span class="fa fa-plus-square-o plus-product" data-toggle="tooltip" title="Thêm sản phẩm"></span>
								<div class="form-group row">
									<div class="col-lg-6">
										<div class="col col-md-3">
											<label for="vat" class=" form-control-label">Tổng Tiền (x1000 VNĐ)</label>
										</div>
										<div class="col-12 col-md-9">
											<input type="number" id="totalPrice" placeholder="148" class="form-control" >
										</div>
									</div>
									<div class="col-lg-6">
										<div class="col col-md-3">
											<label for="vat" class=" form-control-label">Ngày lập đơn</label>
										</div>
										<div class="col-12 col-md-9">
											<input type="date" id="timeCreate" placeholder="19/9/2019" class="form-control">
										</div>
									</div>
						</div>
							</div>	
							<div class="panel-order-shopee fade tab-pane " role="tabpanel" id="panel-order-shopee">
								<div class="row form-group">
									<div class="col-lg-6">
										<div class="col col-md-3"><label for="file-input" class=" form-control-label">File input</label></div>
										<div class="col-12 col-md-9"><input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  id="file-shopee" name="fileShopee" class="form-control-file"></div>
									</div>
									
								</div>
							</div>	
							
						</div>
								
						
							
					</div>

					
					
				</form>
				<div class="card-footer">
					<button type="submit" class="btn btn-primary btn-sm" id="btn-submit">
						<i class="fa fa-dot-circle-o"></i> Thêm
					</button>
					<button type="reset" class="btn btn-danger btn-sm" id="btn-reset">
						<i class="fa fa-ban"></i> Reset
					</button>

				</div>
				
			</div>
		</div>
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<strong class="card-title">Danh sách đơn</strong>
				</div>
				<div class="card-body">
					<table id="bootstrap-data-table" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Sản phẩm</th>
								<th>Nguồn bán</th>
								<th>Thành tiền</th>
								<th>Thời gian tạo</th>
								<th>Xóa</th>
							</tr>
						</thead>
						<?php 
						 ?>
						<tbody>
							@foreach($orders as $order)
							<tr data-id="{{$order->id}}">
								<td >
									<?php 
									$product_data = json_decode($order->product_data,true);
									if($order->distribution == 1){
										if(count($product_data)>1){
											foreach ($product_data as $value) {
												echo '<strong>'.$value['productName'].'</strong> - sl: '.$value['quantity'].'<br>';
											}
										}else{
											echo '<strong>'.$product_data[0]['productName'].'</strong> - sl: '.$product_data[0]['quantity'];
										}
									}else{
										foreach ($product_data['productData'] as $key => $value) {
											echo ' <strong>'.$value['name'].'</strong> - sl: '.$value['countSales'].'<br>';
										}
										echo $product_data['rangeDate']['startDate'].' Đến ngày: '.$product_data['rangeDate']['endDate'];
									}
									
									?>
								 	
								 </td>
								<td >
									<?php if($order->distribution == 1){
										echo 'Đơn buôn';
									}else{
										echo 'Shopee';
									} ?>
								</td>
								<td ><?php echo number_format($order->total_price); ?></td>
								<td >
									
									<?php 
										$timeStamp = strtotime($order->time_created);
										$dt = new DateTime("@$timeStamp");
										echo $dt->format('d-m-Y');
									?>



								</td>
								<td style="text-align: center;"><button class="btn btn-success btnDeleteOrder" data-id="{{$order->id}}">Xóa</button></td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Sản phẩm</th>
								<th>Nguồn bán</th>
								<th>Thành tiền</th>
								<th>Thời gian tạo</th>
								<th></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script type="text/javascript">
	var listArrProductsName = '<?php echo json_encode($listProductName) ?>';
	listProductName = JSON.parse(listArrProductsName);
	
</script>

<script src="{{asset('app/assets/js/lib/data-table/datatables.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/jszip.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/pdfmake.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/vfs_fonts.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/buttons.html5.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/buttons.print.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/buttons.colVis.min.js')}}"></script>
<script src="{{asset('app/assets/js/lib/data-table/datatables-init.js')}}"></script>
<script type="text/javascript" src="{{asset('js/order.js')}}"></script>

@endsection