<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* User */
Route::get('register', 'UserController@register');

/* Invoice */
Route::resource('/company', 'CompanyController');
Route::resource('/Customer', 'CustomerController');
Route::resource('/item', 'ItemController');
Route::resource('/seller', 'SellerController');
Route::resource('/store', 'StoreController');
Route::get('/invoice', 'InvoiceController@index');
Route::get('/invoice/{id}', 'InvoiceController@show');



/* ------------------- localhost:8080/api/users/getusers----------------- */
/**-----------------------users endpoint ------------------------------------- */
Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@getAll')->name('getusers');
    Route::get('/{id}', 'UserController@get')->name('getuser');
    Route::post('/', 'UserController@create')->name('createuser');
    Route::put('/{id}', 'UserController@update')->name('updateuser');
    Route::delete('/{id}', 'UserController@delete')->name('deleteuser');
  });


/* ---------------------- items endpoint --------------------- */
  Route::group(['prefix'=>'items'], function () {
    Route::get('/', 'ItemController@getAll')->name('getitems');
    Route::get('/{id}', 'ItemController@getOne')->name('getitem');
    Route::post('/', 'ItemController@create')->name('createitem');
    Route::put('/{id}', 'ItemController@update')->name('updateitem');
    Route::delete('/{id}', 'ItemController@delete')->name('deleteitem');
  });



  /* Bill To PDF */
Route::get('pdf', 'PdfController@download');