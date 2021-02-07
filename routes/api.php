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

/* Invoice */
Route::apiResource('/company', 'CompanyController');
Route::resource('/Customer', 'CustomerController');
Route::resource('/invoice', 'InvoiceController');
Route::resource('/item', 'ItemController');
Route::resource('/seller', 'SellerController');
Route::resource('/store', 'StoreController');
