<?php

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


Auth::routes();

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('category')->name('category.')->group(function(){
        Route::get('/', 'CategoryController@index')->name('index');
        Route::post('/', 'CategoryController@store')->name('store');
        Route::get('/{category}/edit', 'CategoryController@edit')->name('edit');
        Route::put('/{category}', 'CategoryController@update')->name('update');
        Route::delete('/{category}', 'CategoryController@destroy')->name('destroy');
    });

    Route::prefix('product')->name('product.')->group(function(){
        Route::get('/', 'ProductController@index')->name('index');
        Route::get('/create', 'ProductController@create')->name('create');
        Route::post('/create', 'ProductController@store')->name('store');
        Route::get('/{product}/edit', 'ProductController@edit')->name('edit');
        Route::put('/{product}', 'ProductController@update')->name('update');
        Route::delete('/{product}', 'ProductController@destroy')->name('destroy');
        Route::get('/bulk', 'ProductController@massUploadForm')->name('bulk');
        Route::post('/bulk', 'ProductController@massUpload')->name('saveBulk');
    });
});


Route::name('front.')->group(function(){
    Route::get('/', 'Ecommerce\FrontController@index')->name('index');
    
    Route::get('/category/{slug}', 'Ecommerce\FrontController@categoryProduct')->name('category');
    
    Route::group(['prefix' => 'product', 'namespace' => 'Ecommerce'], function(){
        Route::get('/', 'FrontController@product')->name('product');
        Route::get('/{slug}', 'FrontController@show')->name('show_product');
    });

    Route::group(['prefix' => 'cart', 'namespace' => 'Ecommerce'], function(){
        Route::post('/', 'CartController@addToCart')->name('cart');
        Route::get('/', 'CartController@listCart')->name('list_cart');
        Route::post('/update', 'CartController@updateCart')->name('update_cart');
    });

    Route::group(['prefix' => 'checkout', 'namespace' => 'Ecommerce'], function(){
        Route::get('/', 'CartController@checkout')->name('checkout');
        Route::post('/', 'CartController@processCheckout')->name('store_checkout');
        Route::get('/{invoice}', 'CartController@checkoutFinish')->name('finish_checkout');
    });
    Route::get('/api/city', 'Ecommerce\CartController@getCity');
    Route::get('/api/district', 'Ecommerce\CartController@getDistrict');
});



Route::group(['prefix' => 'member', 'namespace' => 'Ecommerce'], function() {
    Route::get('/login', 'LoginController@loginForm')->name('customer.login');
    Route::get('/verify/{token}', 'FrontController@verifyCustomerRegistration')->name('customer.verify');
    Route::post('/login', 'LoginController@login')->name('customer.post_login');

    Route::group(['middleware' => 'customer'], function() {
        Route::get('dashboard', 'LoginController@dashboard')->name('customer.dashboard');
        Route::get('logout', 'LoginController@logout')->name('customer.logout');
        
        Route::get('orders', 'OrderController@index')->name('customer.orders');
        Route::get('orders/{invoice}', 'OrderController@view')->name('customer.view_order');

        Route::get('payment', 'OrderController@paymentForm')->name('customer.paymentForm');
        Route::post('payment', 'OrderController@storePayment')->name('customer.savePayment');
    });
});