@extends('layouts.frontLayout.front_design')
@section('content')


<section id="form" style="margin-top:20px;"><!--form-->

		<div class="container">
                <div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Check Out</li>
				</ol>
			</div>
        @if(Session::has('flash_message_error'))
        <div class="alert alert-error alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ session('flash_message_error') }}</strong>
</div>
        @endif  
        @if(Session::has('flash_message_success'))
        <div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ session('flash_message_success') }}</strong>
</div>
        @endif
        <form action="{{url('/checkout')}}" method="post" >{{csrf_field()}}
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Bill To</h2>
                            <div class="form-group"> 
                            <input type="text" name="billing_name" id="billing_name" @if(!empty($userDetails->name)) value="{{$userDetails->name}}" @endif placeholder="Billing Name" class="form-control"/>
                            </div>
                            <div class="form-group"> 
                            <input type="text" name="billing_address" id="billing_address" @if(!empty($userDetails->naaddressme))value="{{$userDetails->address}}"@endif placeholder="Billing Address" class="form-control"/>
                            </div>

                            <div class="form-group"> 
                            <input type="text" name="billing_city" id="billing_city" @if(!empty($userDetails->city))value="{{$userDetails->city}}" @endif placeholder="Billing City" class="form-control"/>
                            </div>
                            <div class="form-group"> 
                            <input type="text" name="billing_state" id="billing_state" @if(!empty($userDetails->state))value="{{$userDetails->state}}" @endif placeholder="Billing State" class="form-control"/>
                            </div>
                           
                            <div class="form-group"> 
                            <input type="text" name="billing_country" id="billing_country" @if(!empty($userDetails->country))value="{{$userDetails->country}}"@endif placeholder="Billing Country" class="form-control"/>
                            </div>

                            <div class="form-group"> 
                            <input type="text" name="billing_pincode" id="billing_pincode" @if(!empty($userDetails->pincode))value="{{$userDetails->pincode}}"@endif placeholder="Billing Pincode" class="form-control"/>
                            </div>

                            <div class="form-group"> 
                            <input type="text" name="billing_mobile" id="billing_mobile" @if(!empty($userDetails->mobile))value="{{$userDetails->mobile}}" @endif       placeholder="Billing Mobile" class="form-control"/>
                            
</div>
<div class="form-group"> 
                            <input type="text" name="billing_vat" id="billing_vat" @if(!empty($userDetails->vat))value="{{$userDetails->vat}}" @endif       placeholder="Vat Number" class="form-control"/>
                            
</div>

                <div class="form-group"> 
                <label for="">Shipping Method</label>
                <select name="Payment Method" id="">Payment Method
                <option value="DHL"> <img src="img/dhl.png" width="24px;"height="24px;" >  DHL</option>
               
                <option value="FedEx">FedEx</option>
                <option value="FedEx">Aramex</option>
                <option value="FedEx">TNT</option>
                <option value="FedEx">Local Courier</option>
                <option value="FedEx">Air Cargo</option>
                <option value="FedEx">Sea Cargo</option>
                </select>           
                </div>

               

<div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Shipping Address same as Billing Address</label>
  </div>
							
					
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2 ></h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>Ship To</h2>
					
                        <div class="form-group"> 
                            <input  type="text"  name="shipping_name" @if(!empty($shippingDetails->name))value="{{$shippingDetails->name}}" @endif id="shipping_name" placeholder="Shipping Name" class="form-control"/>
                            </div>
                            <div class="form-group"> 
                            <input type="text" name="shipping_address" @if(!empty($shippingDetails->address)) value="{{$shippingDetails->address}}" @endif id="shipping_address" placeholder="Shipping Address" class="form-control"/>
                            </div>

                            <div class="form-group"> 
                            <input type="text" name="shipping_city" @if(!empty($shippingDetails->city)) value="{{$shippingDetails->city}}" @endif id="shipping_city" placeholder="Shipping City" class="form-control"/>
                            </div>
                            <div class="form-group"> 
                            <input type="text" name="shipping_state" @if(!empty($shippingDetails->state)) value="{{$shippingDetails->state}}" @endif id="shipping_state" placeholder="Shipping State" class="form-control"/>
                            </div>
                            <div class="form-group"> 
                            <input type="text" name="shipping_country" @if(!empty($shippingDetails->country)) value="{{$shippingDetails->country}}" @endif id="shipping_country" placeholder="Shipping Country" class="form-control"/>
                            </div>
                            <div class="form-group"> 
                            <input type="text" name="shipping_pincode" @if(!empty($shippingDetails->pincode)) value="{{$shippingDetails->pincode}}"  @endif id="shipping_pincode" placeholder="Shipping Pincode" class="form-control"/>
                            </div>
                            <div class="form-group"> 
			<input type="text" name="shipping_mobile" @if(!empty($shippingDetails->mobile)) value="{{$shippingDetails->mobile}}" @endif id="shipping_mobile" placeholder="Shipping Mobile" class="form-control"/>
</div>

<div class="form-group"> 
			<input type="text" name="shipping_vat" @if(!empty($shippingDetails->vat)) value="{{$shippingDetails->vat}}" @endif id="shipping_vat" placeholder="Vat Number" class="form-control"/>
</div>
							<button type="submit" class="btn btn-default">Checkout</button>
					
					</div><!--/sign up form-->
				</div>
            </div>
</form>
		</div>
    </section><!--/form-->


    
    

    @endsection