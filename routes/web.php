<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/','IndexController@index');

Route::get('/products/{url}','ProductsController@listingProduct');
Route::get('/product/{id}','ProductsController@product');

Route::get('/get-product-size','ProductsController@getProductPrice');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

Route::match(['get','post'],'/admin','AdminController@login');

//Add to cart
Route::match(['get','post'],'/add-cart','ProductsController@addtoCart');

//Delete Cart
Route::get('/delete-cart/{id}','ProductsController@deleteCart');
//update cart
Route::get('/cart/update-cart/{id}/{quantity}','ProductsController@updatecartQuantity');

//Check Coupon
Route::post('/cart/apply-coupon','ProductsController@applyCoupon');


Route::get('/cart','ProductsController@cart');

//For User Login and register
Route::get('/login-register','UsersController@userLoginRegister');


Route::post('/user-register','UsersController@register');

//Check Email
Route::match(['get','post'],'/check-email','UsersController@checkEmail');
//user logout
Route::get('/user-logout','UsersController@logout');

//User Login
Route::post('/user-login','UsersController@login');





Route::group(['middleware' => ['frontlogins']], function(){

    //User Account
    Route::match(['get','post'],'/account','UsersController@account');

    //Check Password
    Route::post('/check-user-pwd','UsersController@password');

    //Updating Password
    Route::post('/update-user-pwd','UsersController@changePassword');

    //Checkout Page
    Route::match(['get','post'],'/checkout','ProductsController@checkout');

    //Order Review
    Route::match(['get','post'],'/order-review', 'ProductsController@orderReview');

    //Place Order
    Route::match(['get','post'],'/place-order','ProductsController@placeOrder');

    //Thanks page
    Route::get('/thanks','ProductsController@thanks');

    Route::get('/orders','ProductsController@userOrder');

    Route::get('/orders/{id}','ProductsController@userOrderDetails');


});

Route::group(['middleware' => ['auth']], function(){

    Route::get('/admin/dashboard','AdminController@dashboard');

    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
    Route::get('/admin/view-categories','CategoryController@viewCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');

    //Route for products
    Route::match(['get','post'],'/admin/add-product','ProductsController@addProduct');
    Route::get('/admin/view-product','ProductsController@viewProduct');
    Route::match(['get','post'],'/admin/edit-product/{id}','ProductsController@editProduct');
    Route::get('/admin/delete-productImage/{id}','ProductsController@deleteProductImage');
    Route::get('/admin/delete-product/{id}','ProductsController@deleteProduct');

    //Route for Products Attributes
    Route::match(['get','post'],'/admin/add-attribute/{id}','ProductsController@addAttribute');
    Route::match(['get','post'],'/admin/edit-attribute/{id}','ProductsController@editAttribute');
    Route::get('/admin/delete-attribute/{id}','ProductsController@deleteAttribute');
    
    //ROute for adding multiple images
    Route::match(['get','post'],'/admin/add-images/{id}','ProductsController@addImages');
    Route::get('/admin/delete-multipleImage/{id}','ProductsController@deleteMultipleImage');

    //Route for Coupons
    Route::match(['get','post'],'/admin/add-coupon','CouponsController@addCoupon');
    Route::get('/admin/view-coupon','CouponsController@viewCoupon');
    Route::match(['get','post'],'/admin/edit-coupon/{id}','CouponsController@editCoupon');
    Route::get('/admin/delete-coupon/{id}','CouponsController@deleteCoupon');

    //Route for Banner
    Route::match(['get','post'],'/admin/add-banner','BannerController@addBanner' );
    Route::get('/admin/view-banner','BannerController@viewBanner');
    Route::get('/admin/delete-banner/{id}','BannerController@deleteBanner');
    Route::match(['get','post'],'/admin/edit-banner/{id}','BannerController@editBanner');

    //Route for order
    Route::get('/admin/view-order','ProductsController@adminOrder');

});

Route::get('/wholesale-login','WholesaleController@wholesaleLogin');



Route::post('/wholesale-register','WholesaleController@register');
Route::get('/wholesale-board','WholesaleController@dashboard');
Route::post('/wholesale-signin','WholesaleController@login');


Route::get('/logout','AdminController@logout');

Route::get('/contact','ProductsController@contact');