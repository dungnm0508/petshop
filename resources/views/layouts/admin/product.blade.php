@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="{{asset('app/assets/css/lib/datatable/dataTables.bootstrap.min.css')}}">
<style type="text/css">
	.bootstrap-tagsinput .tag{
		margin-right: 2px;
		color: #fff;
		background-color: #477aca;
		padding: 2px;
		border-radius: 1px;
		padding-left: 10px;
		padding-right: 5px;
	}
	.bootstrap-tagsinput{
		display: block;
	}
	.card .card-footer{
		text-align: center;
	}
</style>

@endsection
@section('page', 'Sản phẩm')
@section('content')

<div class="animated fadeIn" >

	<div class="row">
		<div class="col-lg-12">
			<div class="card ">
				<div class="card-header">
					<strong>Add</strong> Product
				</div>
				<div class="card-body card-block " >
					<form action="" method="post" >
						<meta name="csrf-token" content="{{ csrf_token() }}" />
						<div class="row">
							<div class="form-group col-lg-4">
								<label for="exampleInputName2" class="pr-1  form-control-label">Tên</label>
								<input type="text" id="nameProduct"  required class="form-control">
							</div>
							<div class="form-group col-lg-4">
								<label for="exampleInputEmail2" class="px-1  form-control-label">Loại</label>
								<input type="text" data-role="tagsinput" id="productType" class="form-control"/>
							</div>
							<div class="form-group col-lg-4">
								<label for="exampleInputEmail2" class="px-1  form-control-label">Giá</label>
								<input type="text" id="priceProduct" required class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-4">
								<label for="exampleInputEmail2" class="px-1  form-control-label">Khối lượng</label>
								<input type="text" id="weightProduct"  required class="form-control">
							</div>
							<div class="form-group col-lg-8">
								<label for="exampleInputEmail2" class="px-1  form-control-label">Mô tả</label>
								<textarea name="textarea-input" id="textareaInput" rows="1" placeholder="Nội dung..." class="form-control"></textarea>
							</div>
						</div>
						
					</form>
				</div>
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
					<strong class="card-title">Data Table</strong>
				</div>
				<div class="card-body">
					<table id="bootstrap-data-table" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Tên sản phẩm</th>
								<th>Loại sản phẩm</th>
								<th>Giá</th>
								<th>Khối lượng</th>
								<th>Xóa</th>
								
							</tr>
						</thead>
						<tbody>
							@foreach($products as $product)
							<tr data-id="{{$product->id}}">
								<td class="nameCol">{{$product->name}}</td>
								<td class="typeCol">{{$product->type_product}}</td>
								<td class="priceCol">{{$product->price}}</td>
								<td class="weightCol">{{$product->weight}}</td>
								<td><button class="btn btn-success btnDeleteProduct" data-id="{{$product->id}}">Xóa</button></td>
							</tr>
							@endforeach
						</tbody>
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
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var host = location.origin;
		
		$('#btn-submit').on('click',function(){
			if(checkEmptyInput()){
				var valName = $('#nameProduct').val();
				var valPrice = $('#priceProduct').val();
				var valWeight = $('#weightProduct').val();
				var valType = $('#productType').val();
				var valDes = $('#textareaInput').val();

				var dataPost= {
					name:valName,
					price:valPrice,
					type:valType,
					weight:valWeight,
					description:valDes,
				};


				jQuery.ajax({
					url: host+'/admin/insertProduct',
					method:'post',
					data: {
						_token: CSRF_TOKEN,
						dataPost:dataPost
					},
					dataType: 'JSON',
					success:function(res){
						console.log(res.message);
						setTimeout(function() {
							location.reload()
						},500);
					}

				});
			}else{
				var messageText = "Vui lòng không để trống trường!";
				messageResponce(messageText,'error');
			}
		});

		jQuery('.btnDeleteProduct').on('click',function(){
			var productId = jQuery('.btnDeleteProduct').attr('data-id');
			var txt;
			var r = confirm("Bạn chắc chắn muốn xóa!");
			if (r == true) {
				jQuery.ajax({
					url: host+'/admin/deleteProduct',
					method:'post',
					data: {
						_token: CSRF_TOKEN,
						productId:productId
					},
					dataType: 'JSON',
					success:function(res){
						console.log(res.message);
						setTimeout(function() {
							location.reload()
						},500);
					}

				});
			}
			
		});
		jQuery('#btn-reset').on('click',function(){
			$('#nameProduct').val('');
			$('#priceProduct').val('');
			$('#weightProduct').val('');
			$('.bootstrap-tagsinput .label-info').each(function(key,value){
				$(value).remove()
			});
			$('#textareaInput').val('');
		});
	});

	function checkEmptyInput(){
		var valName = $('#nameProduct').val();
		var valPrice = $('#priceProduct').val();
		var valWeight = $('#weightProduct').val();

		if(!valName || !valPrice || !valWeight ){
			return false;
		}else{
			return true;
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
<script type="text/javascript" src="{{asset('js/vuejs/product.js')}}"></script>
@endsection