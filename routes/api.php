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

<<<<<<< HEAD

/* ---------------------- items endpoint --------------------- */
Route::get('items', 'ItemController@index');
Route::get('items/{id}', 'ItemController@show');
Route::post('items', 'ItemController@store');
Route::put('items/{id}', 'ItemController@update');
Route::delete('items/{id}','ItemController@destroy');

=======
/* Bill To PDF */
Route::get('pdf', 'PdfController@download');
>>>>>>> 65ed7ea2ba9bdf2ed83e10b001be3968820c4d78
