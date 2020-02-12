@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{asset('app/assets/css/lib/datatable/dataTables.bootstrap.min.css')}}">
<style type="text/css">
	.card-submit{
		text-align: center;
	}
	@import url('https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');
body {
    font-family: 'Roboto', sans-serif;
  }






/* Styling Checkbox Starts */
.checkbox-label {
    display: inline-block;
    position: relative;
    margin: auto;
    cursor: pointer;
    font-size: 22px;
    line-height: 24px;
    height: 24px;
    width: 24px;
    clear: both;
    float: left;
}

.checkbox-label input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkbox-label .checkbox-custom {
    position: absolute;
    top: 0px;
    left: 0px;
    height: 24px;
    width: 24px;
    background-color: transparent;
    border-radius: 5px;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    -ms-transition: all 0.3s ease-out;
    -o-transition: all 0.3s ease-out;
    border: 2px solid #FFFFFF;
}


.checkbox-label input:checked ~ .checkbox-custom {
    background-color: #FFFFFF;
    border-radius: 5px;
    -webkit-transform: rotate(0deg) scale(1);
    -ms-transform: rotate(0deg) scale(1);
    transform: rotate(0deg) scale(1);
    opacity:1;
    border: 2px solid #FFFFFF;
}


.checkbox-label .checkbox-custom::after {
    position: absolute;
    content: "";
    left: 12px;
    top: 12px;
    height: 0px;
    width: 0px;
    border-radius: 5px;
    border: solid #009BFF;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(0deg) scale(0);
    -ms-transform: rotate(0deg) scale(0);
    transform: rotate(0deg) scale(0);
    opacity:1;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    -ms-transition: all 0.3s ease-out;
    -o-transition: all 0.3s ease-out;
}


.checkbox-label input:checked ~ .checkbox-custom::after {
  -webkit-transform: rotate(45deg) scale(1);
  -ms-transform: rotate(45deg) scale(1);
  transform: rotate(45deg) scale(1);
  opacity:1;
  left: 8px;
  top: 3px;
  width: 6px;
  height: 12px;
  border: solid #009BFF;
  border-width: 0 2px 2px 0;
  background-color: transparent;
  border-radius: 0;
}



/* For Ripple Effect */
.checkbox-label .checkbox-custom::before {
    position: absolute;
    content: "";
    left: 10px;
    top: 10px;
    width: 0px;
    height: 0px;
    border-radius: 5px;
    border: 2px solid #FFFFFF;
    -webkit-transform: scale(0);
    -ms-transform: scale(0);
    transform: scale(0);    
}

.checkbox-label input:checked ~ .checkbox-custom::before {
    left: -3px;
    top: -3px;
    width: 24px;
    height: 24px;
    border-radius: 5px;
    -webkit-transform: scale(3);
    -ms-transform: scale(3);
    transform: scale(3);
    opacity:0;
    z-index: 999;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    -ms-transition: all 0.3s ease-out;
    -o-transition: all 0.3s ease-out;
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
										<option value="Bao">Bao</option>
										<option value="Túi">Túi</option>
										<option value="Cái">Cái</option>
										<option value="Gói">Gói</option>
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
								<td>
									@if($order->status == 0)
										
										<i class="fa fa-times-circle" style="color: #dc3545;" ></i> Chưa kiểm hàng
										<div class="form-group">
											<div class="checkbox-container">

												<label class="checkbox-label">
													<input type="checkbox" data-id='{{$order->id}}'>
													<span class="checkbox-custom rectangular"></span>
												</label>
												<div class="input-title" style="float: left;margin-left: 10px;">Đã kiểm duyệt</div>
											</div>

										</div>
										
										

									@else
										<i class="fa fa-check-circle-o " style="color:#218838;"></i> Đã kiểm hàng
									@endif

								</td>
								<td>
									<form action="deleteArchive/{{$order->id}}" method="get">
										@csrf
										<button class="btn btn-danger">Xóa</button>
									</form>
								</td>
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
	$(document).ready(function(){
		$('.checkbox-label input[type="checkbox"]').click(function(e){
			console.log(e.target);
		});
	});
</script>
@endsection