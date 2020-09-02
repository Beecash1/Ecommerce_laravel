/*price range*/

 $('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});
});

$(document).ready(function(){
// alert("test");
$("#selSize").change(function(){
	var idSize = $(this).val();
	// alert(idSize);
	if(idSize ==""){
		return false;
	}
	$.ajax({
		type:'get',
		url:'/get-product-size',
		data:{idSize:idSize},
		success:function(resp){
			var arr = resp.split("#");
			$("#getPrice").html(" $ " +arr[0]);
			$("#price").val(arr[0]);
			if(arr[1]==0){
				$("#CartBtn").hide();
				$("#TextId").text("Out of Stock");
				
			}else{
				$("#CartBtn").show();
				$("#TextId").text("In Stock");
			}
			// alert(resp);
		},error:function(){
			alert("error");
		}

	});
});

});


$(document).ready(function(){

	$(".AltImage").click(function(){
		var image = $(this).attr('src');
		// alert(image);
		$(".MainImage").attr('src',image);
	});
});

// Instantiate EasyZoom instances
var $easyzoom = $('.easyzoom').easyZoom();

// Setup thumbnails example
var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

$('.thumbnails').on('click', 'a', function(e) {
	var $this = $(this);

	e.preventDefault();

	// Use EasyZoom's `swap` method
	api1.swap($this.data('standard'), $this.attr('href'));
});

// Setup toggles example
var api2 = $easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

$('.toggle').on('click', function() {
	var $this = $(this);

	if ($this.data("active") === true) {
		$this.text("Switch on").data("active", false);
		api2.teardown();
	} else {
		$this.text("Switch off").data("active", true);
		api2._init();
	}
});

$().ready(function(){
	// alert("test");

	$("#registerForm").validate({
		rules:{
			name:{
				required:true,
				minlength:2,
				

			},
			password:{
				required:true,
				minlength:6

			},
			email:{
				required:true,
				email:true,
				remote:"/check-email"
		}

		},
		messages:{
			name: {required:"Please provide your Name",
			minlength:"Please provide atleast 2 characters long"
			

			},
			password:{
				required:"Please provide your Password",
				minlength:"Please provide atleast 6 characters long"
			},
			email:{
				required:"Pleaee enter your email",
				email:"Please enter valid email",
				remote:"Email already exist"
			}

		}

	});

	//Validate Login Form
	$("#LoginForm").validate({
		rules:{
			
			password:{
				required:true,
				
			},
			email:{
				required:true,
				email:true,
				
		}

		},
		messages:{
			
			password:{
				required:"Please provide your Password"
				
			},
			email:{
				required:"Pleaee enter your email",
				email:"Please enter valid email"
				
			}

		}

	});

	$('#myPassword').passtrength({
		minChars: 4,
		passwordToggle: true,
		tooltip: true,
		eyeImg :'img/frontend_images/eye.svg'
	  });

	  //Validate Account Update
	  $("#AccountForm").validate({
		rules:{
			name:{
				required:true
				
				

			},
			address:{
				required:true
				

			},
			city:{
				required:true
				
		},
		state:{
			required:true
			
	},
	country:{
		required:true
		
},
pincode:{
	required:true
	
},
mobile:{
	required:true
	
}
		},
		messages:{
			name: {required:"Please provide your Name"
			
			

			},
			address:{
				required:"Please provide your Address"
			},
			city:{
				required:"Pleaee enter your city"
				
			},
			state:{
				required:"Pleaee enter your state"
				
			},
			country:{
				required:"Pleaee enter your country"
				
			},
			pincode:{
				required:"Pleaee enter your pincode"
				
			},
			mobile:{
				required:"Pleaee enter your mobile"
				
			}

		}

	});


	


	//check password
	$("#current_pwd").keyup(function(){
		var current_pwd = $(this).val();
		// alert(current_pwd);
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
			type:'post',
			url:'/check-user-pwd',
			data:{current_pwd:current_pwd},
			success:function(resp){
				if(resp=="false"){
					$("#chkPWD").html("<font color='red'>Current Password is incorrect</font>");

				}else if(resp=="true"){
					$("#chkPWD").html("<font color='green'>Current Password is Correct</font>");
				}
			},
			error:function(){
				alert("error");

			}

		});
	});

	$("#passwordForm").validate({
		rules:{
			/*name:{
				required:true
			},*/
			/*email:{
				required:true,
				email: true
			},*/
			current_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			new_pwd:{
				required: true,
				minlength:6,
				maxlength:20
			},
			confirm_pwd:{
				required:true,
				minlength:6,
				maxlength:20,
				equalTo:"#new_pwd"
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});

	// alert("test1");
	//checkout
	$("#exampleCheck1").click(function(){
		if(this.checked){
			// alert("test");
			$("#shipping_name").val($("#billing_name").val());
			$("#shipping_address").val($("#billing_address").val());
			$("#shipping_city").val($("#billing_city").val());
			$("#shipping_state").val($("#billing_state").val());
			$("#shipping_country").val($("#billing_country").val());
			$("#shipping_pincode").val($("#billing_pincode").val());
			$("#shipping_mobile").val($("#billing_mobile").val());
			
		}
	});
});


function selectPaymentMethod(){
	if($("#COD").is(':checked') || $("#paypal").is(':checked') )  {
		
	}else{
		alert("Please enter Payment mehtod to order");
		return false;
	}
	
}



$('.btn-number').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
    
});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    




















