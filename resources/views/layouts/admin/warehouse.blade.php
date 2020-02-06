@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('app/assets/css/lib/datatable/dataTables.bootstrap.min.css')}}">
<style type="text/css">
	.card-submit{
		text-align: center;
	}
</style>
@endsection

@section('page', 'Quản lý kho')
@section('content')
<div class="animated fadeIn" >

	<div class="row">
		<div class="col-lg-12">
			<div class="card ">
				<div class="card-header">
					<strong>Thêm</strong> Đơn nhập
				</div>
				<div class="card-body card-block " >
					<form action="{{route('postAddOrder')}}" method="post" id="form-add-order">
					@csrf
					<div class="card-body card-block">

						<div class="form-group row row-product">
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="select" class=" form-control-label">Sản Phẩm</label>
								</div>
								<div class="col-12 col-md-9">
									<select name="selectProduct" id="selectProduct" class="form-control">
										@foreach($products as $product)
										<option value="{{$product->id}}">{{$product->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="company" class=" form-control-label">Số lượng</label>
								</div>
								<div class="col-12 col-md-9">
									<input type="number"  id="quatity" name="quatity" placeholder="Nhập số lượng nhập" class="form-control quatity" required>
								</div>

							</div>
						</div>

						<div class="form-group row">
							<div class="col-lg-6">
								<div class="col col-md-3">
									<label for="vat" class=" form-control-label">Đơn vị</label>
								</div>
								<div class="col-12 col-md-9">
									<!-- <input type="text" id="distribute" placeholder="Shopee" class="form-control"> -->
									<select name="selectUnit" id="selectUnit" class="form-control">
										<option value="bao">Bao</option>
										<option value="tui">Túi</option>
										<option value="cai">Cái</option>
										<option value="goi">Gói</option>
										<option value="Khác">Khác</option>
									</select>
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="card-submit">
						<button type="submit" class="btn btn-primary btn-sm" id="btn-submit">
							<i class="fa fa-dot-circle-o"></i> Thêm
						</button>
					</div>
					<!-- <button type="reset" class="btn btn-danger btn-sm" id="btn-reset">
						<i class="fa fa-ban"></i> Reset
					</button> -->
				</div>
					
				</form>
				
				
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
								<th>Số lượng nhập</th>
								<th>Đơn vị</th>
								<th>Trạng thái</th>
								<th>Xóa</th>
							</tr>
						</thead>
						<tbody>
							@foreach($orders as $order)
							<tr>
								<th>{{$order->name}}</th>
								<td>{{$order->quatity}}</td>
								<td>{{$order->unit}}</td>
								<td>{{$order->status}}</td>
								<td><button class="btn btn-danger">Xóa</button></td>
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

@endsection