@extends('layouts.master')

@section('content')
<div class="col-lg-6">
	<div class="card">
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
					<div class="stat-digit">{{number_format($totalPrice*1000)}} VNĐ</div>
				</div>
			</div>
		</div>
	</div>
</div>

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



<script type="text/javascript">
	var dataProduct = <?php echo json_encode($dataGroup)?>;
	var listColor = ['#e6194b', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe', '#008080', '#e6beff', '#9a6324', '#fffac8', '#800000', '#aaffc3', '#808000', '#ffd8b1', '#000075', '#808080', '#ffffff', '#000000'];

	jQuery(document).ready(function() {
		addPieChart(dataProduct,'#flot-pie')
	});

	function getRandomColor() {
		var letters = '0123456789ABCDEF';
		var color = '#';
		for (var i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}   
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
</script>
@endsection