$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
		$('.plus-product').click(function(){
			if($('.row-product').length == 1){
				$('.row-product').clone().insertAfter($('.row-product'));
			}else{
				var key = $('.row-product').length -1;
				$($('.row-product')[0]).clone().insertAfter($('.row-product')[key]);
			}
			addActionAutoComplete()
		});

		var CSRF_TOKEN = $('[name="_token"]').val();
		var host = location.origin;

		$('#btn-submit').click(function(e){
			var tabActive = $('.nav-link.active');
			if(tabActive.attr('id') == 'shopee-tab'){
				if($('#file-shopee').val() == ''){
					messageResponce('Có gì đó sai! Vui lòng kiểm tra lại','error');
				}else{
					var fileShopee = $('#file-shopee').val();
					var formData = new FormData($('#form-add-order')[0]);
					jQuery.ajax({
						url: host+'/admin/importDataShopee',
						headers: { "X-CSRF-Token": CSRF_TOKEN },
						method: 'post',
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success:function(res){
							if(res.message){
								messageResponce(res.message,'error');
							}else{
								location.reload();
							}
						}

					});
					
				}
			}else{
				if(!checkEmtyData()){
					messageResponce('Có gì đó sai! Vui lòng kiểm tra lại','error');
				}else{


					var selectProducts = $('[name="myCountry"]');
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

					var valPrice = $('#totalPrice').val();
					var valDistribute = 1;
					var valTimeCreate = $('#timeCreate').val();


					var dataPost= {
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
			}
			
		});
		$('.btnDeleteOrder').click(function(e){
			var productId = jQuery(e.target).attr('data-id');
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
		// add event autocomplete
		addActionAutoComplete();
		

	});
	function addActionAutoComplete(){
		var eleProductRow = $('.productInput');
		$.each(eleProductRow,function(key,value){
			autocomplete(value, listProductName);
		})
	}

	function cancelEl(e){
		if($('.row-product').length > 1){
			$(e.target).closest('.row-product').remove();
		}
	}
	function checkEmtyData(){
		var valPrice = $('#totalPrice').val();
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

		if(!$('.row-product #quantity').val() || !valPrice || !isEmptyVal){
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

// auto complele
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
  	var a, b, i, val = this.value;
  	/*close any already open lists of autocompleted values*/
  	closeAllLists();
  	if (!val) { return false;}
  	currentFocus = -1;
  	/*create a DIV element that will contain the items (values):*/
  	a = document.createElement("DIV");
  	a.setAttribute("id", this.id + "autocomplete-list");
  	a.setAttribute("class", "autocomplete-items");
  	/*append the DIV element as a child of the autocomplete container:*/
  	this.parentNode.appendChild(a);
  	/*for each item in the array...*/
  	for (i = 0; i < arr.length; i++) {
  		/*check if the item starts with the same letters as the text field value:*/
  		if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
  			/*create a DIV element for each matching element:*/
  			b = document.createElement("DIV");
  			/*make the matching letters bold:*/
  			b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
  			b.innerHTML += arr[i].substr(val.length);
  			/*insert a input field that will hold the current array item's value:*/
  			b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
  			/*execute a function when someone clicks on the item value (DIV element):*/
  			b.addEventListener("click", function(e) {
  				/*insert the value for the autocomplete text field:*/
  				inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
  			a.appendChild(b);
  		}
  	}
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
  	var x = document.getElementById(this.id + "autocomplete-list");
  	if (x) x = x.getElementsByTagName("div");
  	if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
    } else if (e.keyCode == 13) {
    	/*If the ENTER key is pressed, prevent the form from being submitted,*/
    	e.preventDefault();
    	if (currentFocus > -1) {
    		/*and simulate a click on the "active" item:*/
    		if (x) x[currentFocus].click();
    	}
    }
});
  function addActive(x) {
  	/*a function to classify an item as "active":*/
  	if (!x) return false;
  	/*start by removing the "active" class on all items:*/
  	removeActive(x);
  	if (currentFocus >= x.length) currentFocus = 0;
  	if (currentFocus < 0) currentFocus = (x.length - 1);
  	/*add class "autocomplete-active":*/
  	x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
  	/*a function to remove the "active" class from all autocomplete items:*/
  	for (var i = 0; i < x.length; i++) {
  		x[i].classList.remove("autocomplete-active");
  	}
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
    	if (elmnt != x[i] && elmnt != inp) {
    		x[i].parentNode.removeChild(x[i]);
    	}
    }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
	closeAllLists(e.target);
});
}

