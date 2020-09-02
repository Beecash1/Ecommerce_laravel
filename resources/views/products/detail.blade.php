@extends('layouts.frontLayout.front_design')
@section('content')



<section>
		<div class="container-fluid">
			<div class="row">
			@if(Session::has('flash_message_error'))
        <div class="alert alert-error alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ session('flash_message_error') }}</strong>
</div>
        @endif  
        @if(Session::has('flash_message_success'))
        <div class="alert alert-success alert-block" style="background-color: red;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ session('flash_message_success') }}</strong>
</div>
        @endif 
				<div class="col-sm-3">
                <div class="left-sidebar">
						<h2>Category</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
							<div class="panel panel-default">
                                @foreach($categories as $cat)
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordian" href="#{{$cat->id}}">
											<span class="badge pull-right"><i class="fa fa-plus"></i></span>
											{{$cat->name}}
										</a>
									</h4>
								</div>
								<div id="{{$cat->id}}" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
                                            @foreach($cat->categories as $subcat)
											<li><a href="{{asset('/products/'.$subcat->url)}}">{{$subcat->name}} </a></li>
											@endforeach
										</ul>
									</div>
                                </div>
                                @endforeach
							</div>
							

							
						</div><!--/category-products-->
					
					
						
				
					
					
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="product-details"><!--product-details-->
						<div class="col-sm-5">
							<div class="view-product">
							<div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
							<a href="{{asset( 'img/backend_images/products/large/'.$productDetails->image) }}">
								<img style="width:300px;" class="MainImage" src="{{asset( 'img/backend_images/products/small/'.$productDetails->image) }}" alt="" />
								</a>	
							</div>
							</div>
							<div id="similar-product" class="carousel slide" data-ride="carousel">
								
								  <!-- Wrapper for slides -->
								    <div class="carousel-inner">
										<div class="item active thumbnails">
										<a href="{{asset( 'img/backend_images/products/large/'.$productDetails->image) }}" data-standard="{{asset( 'img/backend_images/products/small/'.$productDetails->image) }}">
								<img  class="AltImage" style="width:80px;" class="MainImage" src="{{asset( 'img/backend_images/products/small/'.$productDetails->image) }}" alt="" />
								</a>	
											@foreach($alternateImage as $atImage)
											<a href="{{asset( 'img/backend_images/products/large/'.$atImage->image) }}" data-standard="{{asset( 'img/backend_images/products/small/'.$atImage->image) }}">
										 <img class="AltImage" src="{{asset( 'img/backend_images/products/small/'.$atImage->image) }}" alt="" style="width:80px;">
										</a>	 
										 @endforeach
										</div>
										
										
									</div>

								  <!-- Controls -->
								  <a class="left item-control" href="#similar-product" data-slide="prev">
									<i class="fa fa-angle-left"></i>
								  </a>
								  <a class="right item-control" href="#similar-product" data-slide="next">
									<i class="fa fa-angle-right"></i>
								  </a>
							</div>

						</div>

						<div class="col-sm-7">
							<form method="post" name="addtocartForm" id="addtocartForm"  action="{{url('add-cart')}}" >{{csrf_field()}}
							<input type="hidden" name="product_id" value="{{$productDetails->id}}">
							<input type="hidden" name="product_name" value="{{$productDetails->product_name}}">
							<input type="hidden" name="product_code" value="{{$productDetails->product_code}}">
							<input type="hidden" name="product_color" value="{{$productDetails->product_color}}">
							<input type="hidden" name="price" id="price" value="{{$productDetails->price}}">
							<div class="product-information"><!--/product-information-->
								<img src="images/product-details/new.jpg" class="newarrival" alt="" />
								<h2> {{$productDetails->product_name}}</h2>
                                <p>Product Code: {{$productDetails->product_code}}</p>
                                <p> 
									
								<select id="selSize" name="size" style="width:150px;" required>
                                    <option value="">Select Size</option>
                                    @foreach($productDetails->attributes as $att)
                                    <option value="{{$productDetails->id}}-{{$att->size}}">{{$att->size}}</option>
                                    @endforeach
                                </select> </p>
								<img src="images/product-details/rating.png" alt="" />
								<span>
									<span id="getPrice">$ {{$productDetails->price}}</span>
									<label>Quantity:</label>
									<input type="text" name="quantity" value="1" />
									@if($TotalStock>0)
									<button type="submit" id="CartBtn" class="btn btn-fefault cart">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
									@endif
								</span>
								<p><b>Availability:</b> <span id="TextId">@if($TotalStock>0)In Stock @else Out of Stock @endif</span> </p>
								<p><b>Condition:</b> New</p>
								<p><b>Description:</b> {{$productDetails->description}}</p>
								
								<a href=""><img src="images/product-details/share.png" class="share img-responsive"  alt="" /></a>
							</div><!--/product-information-->
							</form>
						</div>
					</div><!--/product-details-->
					
					<div class="category-tab shop-details-tab"><!--category-tab-->
						<div class="col-sm-12">
							<ul class="nav nav-tabs">
								<li><a href="#details" data-toggle="tab">Details</a></li>
								<li><a href="#companyprofile" data-toggle="tab">Medical and Care</a></li>
								<li><a href="#tag" data-toggle="tab">Stocl</a></li>
								
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade" id="details" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<p>{{$productDetails->description}}</p>
											</div>
										</div>
									</div>
								</div>
								
								
								
							</div>
							
							<div class="tab-pane fade" id="companyprofile" >
								<div class="col-sm-3">
									<div class="product-image-wrapper">
										<div class="single-products">
											<div class="productinfo text-center">
												<P>{{$productDetails->care}}</P>
											</div>
										</div>
									</div>
								</div>
								
							</div>
							
							
							
							
							
						</div>
					</div><!--/category-tab-->
					
					<div class="recommended_items"><!--recommended_items-->
						<h2 class="title text-center">recommended items</h2>
						
						<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
							<?php $count=1; ?>
							@foreach($relatedProducts->chunk(3) as $chunk)
							<div <?php if($count==1){ ?> class="item active" <?php } else { ?> class="item" <?php } ?>>
							@foreach($chunk as $item)	
							<div class="col-sm-3">
										<div class="product-image-wrapper">
											<div class="single-products">
												<div class="productinfo text-center">
													<img style="width:200;" src="{{asset( 'img/backend_images/products/small/'.$item->image) }}" alt="" />
													<h2>$ {{ $item->price }}</h2>
													<p>{{ $item->product_name }}</p>
													<a href="{{ url('/product/'.$item->id) }}"><button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button></a>
												</div>
											</div>
										</div>
									</div>
									@endforeach
								</div>
								<?php $count++; ?>
								@endforeach
							</div>
							 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
								<i class="fa fa-angle-left"></i>
							  </a>
							  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
								<i class="fa fa-angle-right"></i>
							  </a>			
						</div>
					</div><!--/recommended_items-->
					
				</div>
			</div>
		</div>
    </section>
    
    @endsection