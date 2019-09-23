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
</style>
@endsection

@section('page', 'Đơn hàng')
@section('content')
<div class="animated fadeIn">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
					<div class="card-header"><strong>Đơn Hàng</strong><small> Form</small></div>

				<form action="" method="get" id="form-add-order">
					<meta name="csrf-token" content="{{ csrf_token() }}" />
					<div class="card-body card-block">

						<div class="form-group row row-product">
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="select" class=" form-control-label">Sản Phẩm</label>
								</div>
								<div class="col-12 col-md-9">
									<select name="selectProduct" id="selectProduct" class="form-control">
										@foreach($products as $product)
										<option value="{{$product->name}}">{{$product->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="company" class=" form-control-label">Số lượng</label>
								</div>
								<div class="col-12 col-md-9">
									<input type="number"  id="quantity" placeholder="Nhập số lượng bán" class="form-control quantity" required>
								</div>

							</div>
							<span class="fa fa-minus-square-o minus-product" onclick="cancelEl(event)" data-toggle="tooltip" title="Xóa sản phẩm"></span>
						</div>
						<span class="fa fa-plus-square-o plus-product" data-toggle="tooltip" title="Thêm sản phẩm"></span>

						<div class="form-group row">
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="vat" class=" form-control-label">Phân phối</label>
								</div>
								<div class="col-12 col-md-9">
									<!-- <input type="text" id="distribute" placeholder="Shopee" class="form-control"> -->
									<select name="selectDistribute" id="selectDistribute" class="form-control">
										<option value="shopee">Shopee</option>
										<option value="Nông Nghiệp">Nông Nghiệp</option>
										<option value="Sơn Tây">Sơn Tây</option>
										<option value="Facebook">Facebook</option>
										<option value="Khác">Khác</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="vat" class=" form-control-label">Mã đơn hàng (optional)</label>
								</div>
								<div class="col-12 col-md-9">
									<input type="text" id="codeOrder" placeholder="DE1234567890" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="vat" class=" form-control-label">Thành Tiền (x1000 VNĐ)</label>
								</div>
								<div class="col-12 col-md-9">
									<input type="number" id="totalPrice" placeholder="148" class="form-control" required>
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
								<th>Mã đơn hàng</th>
								<th>Thời gian tạo</th>
								<th>Xóa</th>
							</tr>
						</thead>
						<tbody>
							@foreach($orders as $order)
							<tr data-id="{{$order->id}}">
								<td >
									<?php 
									$product_data = json_decode($order->product_data,true);
									if(count($product_data)>1){
										foreach ($product_data as $value) {
											echo '<strong>'.$value['productName'].'</strong> - sl: '.$value['quantity'].'<br>';
										}
									}else{
										echo '<strong>'.$product_data[0]['productName'].'</strong> - số lượng: '.$product_data[0]['quantity'];
									}
									?>
								 	
								 </td>
								<td >{{$order->distribution}}</td>
								<td >{{$order->total_price}}</td>
								<td >{{$order->code}}</td>
								<td >
									
									<?php 
									if(empty($order->time_created)){
										$timestamp =  strtotime($order->created_at); 
										$dt = new DateTime("@$timestamp");
										echo $dt->format('d-m-Y');
									}else{
										$timestamp =  strtotime($order->time_created); 
										$dt = new DateTime("@$timestamp");
										echo $dt->format('d-m-Y');
									}

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
								<th>Mã đơn hàng</th>
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
<script type="text/javascript">
	
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
		$('.plus-product').click(function(){
			if($('.row-product').length == 1){
				$('.row-product').clone().insertAfter($('.row-product'));
			}else{
				var key = $('.row-product').length -1;
				$($('.row-product')[0]).clone().insertAfter($('.row-product')[key]);
			}
		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var host = location.origin;
		$('#btn-submit').click(function(e){
			if(!checkEmtyData()){
				messageResponce('Có gì đó sai! Vui lòng kiểm tra lại','error');
			}else{

				var selectProducts = $('[name="selectProduct"]');
				var productData = [];
				if(selectProducts.length >1){

					selectProducts.each(function(key,item){
						dataJson = {};
						dataJson.productName = $(item).val();
						dataJson.quantity = $(item).closest('.row-product').find('.quantity').val();
						productData.push(dataJson);
					});
				}else{
					data= {};
					data.productName = selectProducts.val();
					data.quantity = $('#quantity').val();
					productData.push(data);
				}

				var valCode = $('#codeOrder').val();
				var valPrice = $('#totalPrice').val();
				var valDistribute = $('#selectDistribute').val();
				var valTimeCreate = $('#timeCreate').val();
				

				var dataPost= {
					code:valCode,
					price:valPrice,
					distribute:valDistribute,
					timeCreate:valTimeCreate,
					productData:productData,
				};

				jQuery.ajax({
					url: host+'/admin/insertOrder',
					method:'post',
					data: {
						_token: CSRF_TOKEN,
						dataPost:dataPost
					},
					dataType: 'JSON',
					success:function(res){
						setTimeout(function() {
							location.reload()
						},500);
					}

				});
			}
		});
		$('.btnDeleteOrder').click(function(){
			var productId = jQuery('.btnDeleteOrder').attr('data-id');
			console.log(productId);
			var r = confirm("Bạn chắc chắn muốn xóa!");
			if (r == true) {
				jQuery.ajax({
					url: host+'/admin/deleteOrder',
					method:'post',
					data: {
						_token: CSRF_TOKEN,
						productId:productId
					},
					dataType: 'JSON',
					success:function(res){
						setTimeout(function() {
							location.reload()
						},500);
					}

				});
			}
		});

	});
	function cancelEl(e){
		if($('.row-product').length > 1){
			$(e.target).closest('.row-product').remove();
		}
	}
	function checkEmtyData(){
		var valCode = $('#codeOrder').val();
		var valPrice = $('#totalPrice').val();
		var valDistribute = $('#selectDistribute').val();
		var isEmptyVal = true;

		if($('.row-product').length > 1){
			$('.row-product').each(function(key,item){
				if(!$(item).find('.quantity').val()){
					isEmptyVal = false;
				}
			});

		}else{
			if(!$('.row-product #quantity').val()){
				isEmptyVal = false;
			}
		}

		if(valDistribute == 'shopee'){
			if(!$('.row-product #quantity').val() || !valCode || !valPrice || !isEmptyVal){
				return false;
			}else{
				return true;
			}
		}else{
			if(!$('.row-product #quantity').val() || !valPrice || !isEmptyVal){
				return false;
			}else{
				return true;
			}
		}
	}
	
function messageResponce(message,type){
	jQuery( document ).ready(function() {
		if(type == 'error'){
			$('.alert-danger').fadeIn();
			$('.alert-danger .message-text').text(message);
			setTimeout(function(){
				$('.alert-danger').fadeOut();
			},2000)
		}else if(type == 'info'){
			$('.alert-warning').fadeIn();
			$('.alert-warning .message-text').text(message);
			setTimeout(function(){
				$('.alert-warning').fadeOut();
			},2000)
		}else if(type == 'success'){
			$('.alert-success').fadeIn();
			$('.alert-success .message-text').text(message);
			setTimeout(function(){
				$('.alert-success').fadeOut();
			},2000)
		}
	});
}


	
</script>
@endsection