@extends('layouts.master')

@section('content')
<?php 

usort($dataGroup, function($a, $b) {
	return $b['count'] - $a['count'];
});

?>
<div class="col-lg-6">
	<div class="card" style="min-height: 500px">
		<div class="card-body">
			<h4 class="mb-3">Biểu Đồ Số Lượng Sản Phẩm</h4>
			<div class="flot-container">
				<div id="flot-pie" class="flot-pie-container">
				</div>
			</div>
		</div>
	</div><!-- /# card -->
</div><!-- /# column -->
<div class="col-lg-6">
	<div class="card">
		<div class="card-body">
			<div class="stat-widget-one">
				<div class="stat-icon dib"><i class="ti-money text-success border-success"></i></div>
				<div class="stat-content dib">
					<div class="stat-text">Tổng Thu</div>
					<div class="stat-digit">{{number_format($totalPrice)}} VNĐ</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="card">
		<div class="card-body">
			<div class="stat-widget-one">
				<div class="stat-icon dib"><i class="ti-user text-primary border-primary"></i></div>
				<div class="stat-content dib">
					<div class="stat-text">Tổng Đơn</div>
					<div class="stat-digit">{{$countOrder}}</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="card">
		<div class="card-body">
			<div class="stat-widget-one">
				<div class="stat-icon dib"><i class="ti-cup text-warning border-warning"></i></div>
				<div class="stat-lcontent dib">
					<div class="stat-text">Sản phẩm bán chạy nhất (trừ xúc xích)</div>
					<div class="stat-digit"><?php 
					if(!empty($dataGroup)){
						if($dataGroup[0]['product_name'] == 'Xúc Xích'){
							echo $dataGroup[1]['product_name'];
						}else{
							
							echo $dataGroup[0]['product_name'];
							
							
						}
					}
					
					 ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="card">
		<div class="card-body">
			<div class="stat-widget-one">
				<div class="stat-icon dib"><i class="ti-money text-success border-success"></i></div>
				<div class="stat-content dib">
					<div class="stat-text">Tổng Thu Shopee</div>
					<div class="stat-digit">{{number_format($priceOrderShopee)}} VNĐ</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="card">
		<div class="card-header">
			<strong class="card-title">Thống kê số lượng bán</strong>
		</div>
		<div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th scope="col">Tên sản phẩm</th>
						<th scope="col" style="text-align: center;">Số lượng bán</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($dataGroup as $product)
					<tr> 
						<th><?php echo $product['product_name']; ?></th>
						<td style="text-align: center;"><?php echo $product['count']; ?></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="card">
		<div class="card-body">
			<h4 class="mb-3">Biểu đồ doanh thu theo tháng </h4>
			<canvas id="singelBarChart"></canvas>
		</div>
	</div>
</div><!-- /# column -->

@endsection

@section('script')

<script src="{{asset('app/assets/js/lib/flot-chart/jquery.flot.js')}}"></script>
<script src="{{asset('app/assets/js/lib/flot-chart/jquery.flot.pie.js')}}"></script>
<!-- <script src="{{asset('app/assets/js/lib/flot-chart/jquery.flot.time.js')}}"></script> -->
<!-- <script src="{{asset('app/assets/js/lib/flot-chart/jquery.flot.stack.js')}}"></script> -->
<!-- <script src="{{asset('app/assets/js/lib/flot-chart/jquery.flot.resize.js')}}"></script> -->
<!-- <script src="{{asset('app/assets/js/lib/flot-chart/jquery.flot.crosshair.js')}}"></script> -->
<!-- <script src="{{asset('app/assets/js/lib/flot-chart/curvedLines.js')}}"></script> -->
<script src="{{asset('app/assets/js/lib/flot-chart/flot-tooltip/jquery.flot.tooltip.min.js')}}"></script>
<!-- <script src="{{asset('app/assets/js/lib/flot-chart/flot-chart-init.js')}}"></script> -->
<script src="{{asset('app/assets/js/lib/chart-js/Chart.bundle.js')}}"></script>
<!-- <script src="{{asset('app/assets/js/lib/chart-js/chartjs-init.js')}}"></script> -->


<script type="text/javascript">
	var dataProduct = <?php echo json_encode($dataGroup)?>;
	var revenueOfMoth = <?php echo json_encode($revenueOfMoth)?>;
	var d = new Date();
	var monthNames = ["January", "February", "March", "April", "May", "June",
	"July", "August", "September", "October", "November", "December"
	];
	var indexMonth = d.getMonth();
	var listColor = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];

	jQuery(document).ready(function() {
		if(dataProduct.length > 0){
			addPieChart(dataProduct,'#flot-pie');

		}
		addBarChart();
	});

	
	function addPieChart(input,el){


		var data = [];
		jQuery.each(input,function(key,item){
			data.push({
				label: item.product_name,
				data: item.count,
				color: listColor[key]
			});

		});
		data.sort(function(a, b) {
			return parseFloat(a.data) - parseFloat(b.data);
		}).reverse();

		var plotObj = jQuery.plot( jQuery(el), data, {
			series: {
				pie: {
					show: true,
					radius: 1,
					label: {
						show: false,

					}
				}
			},
			grid: {
				hoverable: true
			},
			tooltip: {
				show: true,
				content: "%p.0%, %s, n=%n", 
				shifts: {
					x: 20,
					y: 0
				},
				defaultTheme: false
			}
		} );
	}
	function addBarChart(){
		var ctx = document.getElementById( "singelBarChart" );
		var labels = [];
		var dataRevenue = [];
		for (var i =0; i <=indexMonth; i++) {
			labels.push(monthNames[i]);
			if(typeof(revenueOfMoth[(i+1)]) == 'undefined'){
				dataRevenue.push(0);
			}else{
				dataRevenue.push(revenueOfMoth[(i+1)]);
			}
			
		}
		ctx.height = 150;
		var chartData = {
			labels: labels,
			datasets: [
			{
				label: "Doanh thu theo tháng ",
				data: dataRevenue,
				borderColor: "rgba(0, 123, 255, 0.9)",
				borderWidth: "0",
				backgroundColor: "rgba(0, 123, 255, 0.5)"
			}
			]
		};
		var myChart = new Chart( ctx, {
			type: 'bar',
			data: chartData,
			options: {
				scales: {
					yAxes: [ {
						ticks: {
							beginAtZero: true
						}
					} ]
				}
			}
		} );
	}
</script>
@endsection