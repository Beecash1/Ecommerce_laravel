<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use Auth;
use Session;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\User;
use App\Country;
use App\DeliveryAddress;
use DB;
use Illuminate\Support\Str;
use App\Coupon;
use App\Banner;
use App\Order;
use App\OrdersProduct;
// use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function addProduct(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if(empty($data['category_id'])){
                return redirect()->back()->with('flash_message_error','Under Category is missing');

            }
            $product = new Product;
            $product->category_id= $data['category_id'];
            $product->product_name=$data['product_name'];
            $product->product_code=$data['product_code'];
            $product->product_color=$data['product_color'];
            $product->description=$data['description'];
            $product->care=$data['care'];
            $product->price=$data['product_price'];

            //for image
            if($request->hasFile('image')){
                 $image_tmp = $request->file('image'); 
                 if($image_tmp->isValid()){
                     
                    $extension = $image_tmp->getClientoriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path ='img/backend_images/products/large/'.$filename;
                    $medium_image_path ='img/backend_images/products/medium/'.$filename;
                    $small_image_path ='img/backend_images/products/small/'.$filename;

                    //REsize images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                    $product->image =$filename;
                 }
                 
            }
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status =1;
            }
            $product->status=$status;
            
            $product->save();
            return redirect()->back()->with('flash_message_success','Product has been added successfully');
        }

        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .="<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function viewProduct(){
        $products = Product::get();
        // $products = json_decode(json_encode($products));
       foreach($products as $key => $val){
           $category_name = Category::where(['id'=>$val->category_id])->first();
           $products[$key]->category_name = $category_name->name;

       }
        // echo "<pre>"; print_r($products);die;
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function editProduct(Request $request, $id = null){

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;

            if($request->hasFile('image')){
                $image_tmp = $request->file('image'); 
                if($image_tmp->isValid()){
                    
                   $extension = $image_tmp->getClientoriginalExtension();
                   $filename = rand(111,99999).'.'.$extension;
                   $large_image_path ='img/backend_images/products/large/'.$filename;
                   $medium_image_path ='img/backend_images/products/medium/'.$filename;
                   $small_image_path ='img/backend_images/products/small/'.$filename;

                   //REsize images
                   Image::make($image_tmp)->save($large_image_path);
                   Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                   Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                   
                }
                
           }else{
                $filename = $data['current_image'];
           }
           if(empty($data['care'])){
               $data['care']='';
           }

           if(empty($data['status'])){
            $status = 0;
        }else{
            $status =1;
        }


            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],
            'product_name'=>$data['product_name'],'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],
            'description'=>$data['description'],'care'=>$data['care'],'price'=>$data['product_price'],'image'=>$filename,'status'=>$status]);
            return redirect()->back()->with('flash_message_success','Product has updated successfully');
        }

        $productDetails = Product::where(['id'=>$id])->first();


        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach($categories as $cat){

            if($cat->id == $productDetails->category_id){
                $selected = "selected";
            }
            else{
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                if($sub_cat->id == $productDetails->category_id){
                    $selected = "selected";
                }
                else{
                    $selected = "";
                }

                $categories_dropdown .="<option value = '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }

        return view('admin.products.edit_products')->with(compact('productDetails','categories_dropdown'));
    }

    public function deleteProductImage($id= null){
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Products Image has been deleted');

    }

    public function deleteProduct($id= null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product has been successfullty deleted');
    }
     
    public function addAttribute(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;



        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    $aatrCountSKU = ProductsAttribute::where('sku',$val)->count();
                    if($aatrCountSKU>0){
                        return redirect('admin/add-attribute/'.$id)->with('flash_message_error','SKU already exists! Please add another SKU');  
                    }

                    //prevent Size

                    $attrCOuntSize = ProductsAttribute::where(['product_id'=>$id,'size'=>$data['size'][$key]])->count();
                    if($attrCOuntSize>0){
                        return redirect('admin/add-attribute/'.$id)->with('flash_message_error','Size already exists! Please add another Size'); 
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                    


                }
            }
            return redirect('admin/add-attribute/'.$id)->with('flash_message_success','Product attributes has been added successffully');

        }
        return view ('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function deleteAttribute($id =null){
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Attributes deleted successfully');
    }

    public function listingProduct($url = null){
        $banners = Banner::where('status','1')->get();
        // $banners = json_decode(json_encode($banners));
        // echo "<pre>"; print_r($banners);die;

        // echo $url;die;
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        $categoryDetails = Category::where(['url'=>$url])->first();
        if($categoryDetails->parent_id==0){
            //if url is main category
            $subcategories = Category::where(['parent_id'=>$categoryDetails->id])->get();
           
            foreach($subcategories as $subcat){
                $cat_ids[] = $subcat->id;
            }
            // echo $cat_ids;die;
            $productsAll = Product::whereIn('category_id',$cat_ids)->where('status',1)->get();

        }else{
            //if url is sub category
            $productsAll = Product::where(['category_id'=>$categoryDetails->id])->where('status',1)->get();
        }
        
        
        return view('products.listing')->with(compact('productsAll','categories','categoryDetails','banners'));


    }

    public function product($id=null){
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        $alternateImage = ProductsImage::where(['product_id'=>$id])->get();

        $TotalStock = ProductsAttribute::where('product_id',$id)->sum('stock');
        // echo $TotalStock;die;
       
        $productDetails = Product::with('attributes')->where('id',$id)->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails);die;

        $relatedProducts = Product::where('id','!=',$id)->where(['category_id'=>$productDetails->category_id])->get();
        //   $relatedProducts = json_decode(json_encode($relatedProducts));
        // echo "<pre>"; print_r($relatedProducts);die;


        
        return view('products.detail')->with(compact('productDetails','categories','alternateImage','TotalStock','relatedProducts'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        // echo "<pre>";print_r($data);die;
        $proArr = explode("-",$data['idSize']);
        // echo $proArr[0]; echo $proArr[1];die;
        $proAttr =ProductsAttribute::where(['product_id'=>$proArr[0], 'size'=>$proArr[1]])->first();

        echo $proAttr->price;
        echo "#";
        echo $proAttr->stock;

    }

    public function addImages(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;



        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // echo "<pre>"; print_r($data['product_id']); die;
            if ($request->hasFile('image')) {
                $files = $request->file('image');
                foreach($files as $file){
                    // Upload Images after Resize
                    $image = new ProductsImage;
                    $extension = $file->getClientOriginalExtension();
                    $fileName = rand(111,99999).'.'.$extension;
                    $large_image_path ='img/backend_images/products/large/'.$fileName;
                    $medium_image_path ='img/backend_images/products/medium/'.$fileName;
                    $small_image_path ='img/backend_images/products/small/'.$fileName;
                    Image::make($file)->save($large_image_path);
                    Image::make($file)->resize(600, 600)->save($medium_image_path);
                    Image::make($file)->resize(300, 300)->save($small_image_path);
                    $image->image = $fileName;  
                    $image->product_id = $productDetails->id;
                    
                    $image->save();
                }   
            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success','Multiple image has been addedd successfully');
         
        }

        $productsImage = ProductsImage::Where(['product_id'=>$id])->get();
        //       $productsImage = json_decode(json_encode($productsImage));
        //  echo "<pre>"; print_r($productsImage); die;

        return view ('admin.products.add_images')->with(compact('productDetails','productsImage'));
    }

    public function deleteMultipleImage($id=null){
        ProductsImage::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Multiple image has been deleted successfully');
    }

    public function editAttribute(Request $request, $id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data);die;
            // $as = $data['idAttr'];
            // echo "<pre>"; print_r($as);die;     
            foreach($data['idAttr'] as $key => $attr){
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);

            }
            return redirect()->back()->with('flash_message_success','Attributes has been succesfully update');
        }

    }

    public function addtoCart(Request $request,$id=null){
        Session::forget('CouponAmount');
        Session::forget('CouponCode');


        $data = $request->all();
        // echo "<pre>"; print_r($data);die; 
       

        if(empty(Auth::user()->email)){
            $data['user_email'] = '';    
        }else{
            $data['user_email'] = Auth::user()->email;
        }

        $session_id =Session::get('session_id');
        if(empty($session_id)){
            $session_id = Str::random(40);
            Session::put('session_id',$session_id);
        }
       

        $sizeArr = explode("-",$data['size']);

        $contProducts =  DB::table('cart')->where(['product_id'=>$data['product_id'],
        'product_color'=>$data['product_color'],
        'size'=>$sizeArr[1],'session_id'=>$session_id])->count();

        // echo $contProducts;die;
        if($contProducts>0){
            return redirect()->back()->with('flash_message_error','Product already exist!'); 

        }else{
            $getsku = ProductsAttribute::select('sku')->where(['product_id'=>$data['product_id'],'size'=>$sizeArr[1]])->first();
            DB::table('cart')->insert(['product_id'=>$data['product_id'],'product_name'=>$data['product_name'],
            'product_code'=>$getsku->sku,'product_color'=>$data['product_color'],'price'=>$data['price'],
            'size'=>$sizeArr[1],'user_email'=>$data['user_email'],'quantity'=>$data['quantity'],'session_id'=>$session_id]);

        }
        
      

        return redirect('/cart')->with('flash_message_success','Product has been added to Cart');

    }

    public function cart($id=null){
       

        if(Auth::check()){
            $user_email = Auth::user()->email;
            $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();     
        }else{
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();    
        }
       
        
        foreach($userCart as $key => $cart){
            // echo $cart->product_id;
            $productDetails = Product::where('id',$cart->product_id)->first();
             $userCart[$key]->image=$productDetails->image;
        }

        // echo "<pre>";print_r($userCart);die;


        return view('products.cart')->with(compact('userCart'));
    }

    public function deleteCart($id=null){
        Session::forget('CouponAmount');
        Session::forget('CouponCode');


        DB::table('cart')->where('id',$id)->delete();
        return redirect()->back()->with('flash_message_success','Items Successfully removed');
    }

    public function updatecartQuantity($id=null,$quantity=null){

        Session::forget('CouponAmount');
        Session::forget('CouponCode');


        $getstock = DB::table('cart')->where('id',$id)->first();
        $getAttrstock = ProductsAttribute::where(['sku'=>$getstock->product_code])->first();
        // echo $getstock->quantity;
        // echo "--";
        // echo $getAttrstock->stock;die;
        $updated_quantity = $getstock->quantity+$quantity;
        if($getAttrstock->stock >= $updated_quantity){
            DB::table('cart')->where('id',$id)->increment('quantity',$quantity);
        }else{
            return redirect()->back()->with('flash_message_error','required stock is not available');
        }
        
        return redirect()->back()->with('flash_message_success','quantity has been succesfully updated');
    }

    public function applyCoupon(Request $request){


        Session::forget('CouponAmount');
        Session::forget('CouponCode');


        $data = $request->all();
        // echo "<pre>"; print_r($data);die;
        $countCoupon = Coupon::where('coupon_code',$data['coupon_code'])->count();
        // echo "<pre>"; print_r($countCoupon);die;

        if($countCoupon == 0){
            return redirect()->back()->with('flash_message_error','Such Coupon Code not found');
        }else{
            $couponDetails = Coupon::where('coupon_code',$data['coupon_code'])->first();
            

            //if coupon is inactive
             $couponStatus = $couponDetails->status;
            if($couponStatus == "0"){
                return redirect()->back()->with('flash_message_error','Coupon code is inactive');
            }
               
                //if coupon is expired
                $couponExpiryDate = $couponDetails->expiry_date;
                $current_date = date('Y-m-d');
                if( $couponExpiryDate < $current_date ){

                    return redirect()->back()->with('flash_message_error','Coupon code is expired');
                }
                //coupon is valid for Discount

                //Get cart Total Amount
                $session_id =Session::get('session_id'); 
                $userCart = DB::table('cart')->where(['session_id'=> $session_id])->get();



                // if(Auth::check()){
                //     $user_email = Auth::user()->email;
                //     $userCart = DB::table('cart')->where(['user_email'=>$user_email])->get();
        
                // }else{
                //     $session_id =Session::get('session_id');
                //     $userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();
        
                // }
                
                $total_amount=0;
                foreach($userCart as $item){
                    $total_amount = $total_amount + ($item->price * $item->quantity   );
                

                }



                //check if amount type is Fixed or Percentage
                if($couponDetails->amount_type =="Fixed"){
                    $couponAmount = $couponDetails->amount;
                } else{
                    $couponAmount = $total_amount * ($couponDetails->amount/100);
                }
                // echo $couponAmount;die;

                //Add Coupon Code and Amount in Session
                Session::put('CouponAmount',$couponAmount);
                Session::put('CouponCode',$data['coupon_code']);

                return redirect()->back()->with('flash_message_success','Coupon code successfully applied');
                
            
        }
    }

    public function checkout(Request $request){
        $user_id = Auth::user()->id;
        $user_email=Auth::user()->email;
        $userDetails = User::find($user_id);
        // $countries = Country::get();

        //Check if Shipping address exists

        $shippingCount = DeliveryAddress::where('user_id',$user_id)->count();
        $shippingDetails = array();
     
        if($shippingCount>0){
            $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();
        }

        //Update cart table with user email
        $session_id = Session::get('session_id');
        // echo "<pre>";print_r($session_id);die;
        DB::table('cart')->where(['session_id'=>$session_id])->update(['user_email'=>$user_email]);

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";print_r($data);die;

            //Return to Checkout page if  any of the field is empty
            // if(empty($data['biiling_name']) || empty($data['billing_address']) || empty($data['billing_city']) ||
            // empty($data['billing_state']) ||empty($data['billing_country']) ||empty($data['billing_pincode']) ||
            // empty($data['billing_mobile']) ||empty($data['shipping_name']) || empty($data['shipping_address']) ||
            // empty($data['shipping_city']) ||empty($data['shipping_state']) ||empty($data['shipping_country']) ||
            // empty($data['shipping_pincode']) ||empty($data['shipping_mobile'])  ){

            //     return redirect()->back()->with('flash_message_error','Please fill all the fields to checkout');

            // }
                User::where('id',$user_id)->update(['name'=>$data['billing_name'],'address'=>$data['billing_address'],
                'city'=>$data['billing_city'],'state'=>$data['billing_state'],'country'=>$data['billing_country'],
                'pincode'=>$data['billing_pincode'],'mobile'=>$data['billing_mobile'],'vat'=>$data['billing_vat']]);

         
                if(($shippingCount>0)){
                    //update useShipping Address
                    DeliveryAddress::where('user_id',$user_id)->update(['name'=>$data['shipping_name'],'address'=>$data['shipping_address'],
                    'city'=>$data['shipping_city'],'state'=>$data['shipping_state'],'country'=>$data['shipping_country'],
                    'pincode'=>$data['shipping_pincode'],'mobile'=>$data['shipping_mobile'],'vat'=>$data['shipping_vat']]);
                }else{
                    //add New shipping address
                    $shipping = new DeliveryAddress;
                    $shipping->user_id=$user_id;
                    $shipping->user_email=$user_email;
                    $shipping->name = $data['shipping_name'];
                    $shipping->address = $data['shipping_address'];
                    $shipping->city = $data['shipping_city'];
                    $shipping->state = $data['shipping_state'];
                    $shipping->country = $data['shipping_country'];
                    $shipping->pincode = $data['shipping_pincode'];
                    $shipping->mobile = $data['shipping_mobile'];
                    $shipping->vat = $data['shipping_vat'];
                    $shipping->save();
                }
                // echo "redirect to order REvview page";die;
                return redirect()->action('ProductsController@orderReview');

           
        }
        return view('products.checkout')->with(compact('userDetails','shippingDetails'));
    }


    public function orderReview(Request $request){
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id',$user_id)->first();
        $shippingDetails = DeliveryAddress::where('user_id',$user_id)->first();

        $userCart = DB::table('cart')->where(['user_email'=>$user_email])->get();
        foreach($userCart as $key => $product){
            $productDetails = Product::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }

        // echo "<pre>";print_r($userCart);die;
        return view('products.order_review')->with(compact('userDetails','shippingDetails','userCart'));
    }

    public function placeOrder(Request $request){
        if($request->isMethod('post')){
            $data= $request->all();
            // echo "<pre>";print_r($data);die;
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;

            $shippingDetails = DeliveryAddress::where(['user_email'=>$user_email])->first();
            // $shippingDetails=json_decode(json_encode($shippingDetails));
            // echo "<pre>";print_r($shippingDetails);die;

            if(empty(Session::get('CouponCode'))){
              $coupon_code="";
            }else{
                $coupon_code =Session::get('CouponCode');
            }

            if(empty(Session::get('CouponAmount'))){
                $coupon_amount="";
            }else{
                $coupon_amount =Session::get('CouponAmount');
            }
            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name=$shippingDetails->name;
            $order->address=$shippingDetails->address;
            $order->city=$shippingDetails->city;
            $order->state=$shippingDetails->state;
            $order->pincode=$shippingDetails->pincode;
            $order->country=$shippingDetails->country;
            $order->mobile=$shippingDetails->mobile;
            // $order->shipping_charges=;
            $order->coupon_code=$coupon_code;
            $order->coupon_amount=$coupon_amount;
            $order->order_status="New";
            $order->payment_method=$data['payment_method'];
            $order->grand_total=$data['grand_total'];
            $order->save();

            $order_id = DB::getPdo()->lastInsertId();

            $cartProducts = DB::table('cart')->where(['user_email'=>$user_email])->get();
            foreach($cartProducts as $pro){
                $cartPro = new OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_color = $pro->product_color;
                $cartPro->product_size = $pro->size;
                $cartPro->product_price = $pro->price;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();

                Session::put('order_id',$order_id);
                Session::put('grand_total',$data['grand_total']);
                 return redirect('/thanks');

            

            }

        }
    }

    public function thanks(){
        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email',$user_email)->delete();         
        return view('products.thanks');
    }

    public function userOrder(){
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id',$user_id)->get();
        // $orders = json_decode(json_encode($orders));
        // echo "<pre>";print_r($orders);die;
        return view('orders.users_orders')->with(compact('orders'));
    }

    public function userOrderDetails($order_id){

        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id',$order_id)->first();
        return view('orders.users_order_details')->with(compact('orderDetails'));
    }

    public function adminOrder(){
        $orderDetails=Order::with('orders')->get();
        // $orderDetails = json_decode(json_encode($orderDetails));
        // echo "<pre>";print_r($orderDetails);die;
        return view ('admin.user.user_order')->with(compact('orderDetails'));
    }
}
