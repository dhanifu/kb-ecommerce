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
    
    Route::prefix('product')->group(function(){
        Route::get('/', 'Ecommerce\FrontController@product')->name('product');
        Route::get('/{slug}', 'Ecommerce\FrontController@show')->name('show_product');
    });

    Route::prefix('cart')->group(function(){
        Route::post('/', 'Ecommerce\CartController@addToCart')->name('cart');
        Route::get('/', 'Ecommerce\CartController@listCart')->name('list_cart');
        Route::post('/update', 'Ecommerce\CartController@updateCart')->name('update_cart');
    });

    Route::prefix('checkout')->group(function(){
        Route::get('/', 'Ecommerce\CartController@checkout')->name('checkout');
        Route::post('/', 'Ecommerce\CartController@processCheckout')->name('store_checkout');
        Route::get('/{invoice}', 'Ecommerce\CartController@checkoutFinish')->name('finish_checkout');
    });
    Route::get('city', 'Ecommerce\CartController@getCity');
    Route::get('district', 'Ecommerce\CartController@getDistrict');
});