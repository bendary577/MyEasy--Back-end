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
                    //http://127.0.0.1:8000/api/user/login
/*----------------------- authentication endpoints ------------------------------------- */
Route::group([ 'middleware' => ['api'],'prefix' => 'user'], static function() {
  Route::post('/login', 'AuthenticationController@login');
  Route::post('/register', 'AuthenticationController@register');
  Route::post('/me', 'AuthenticationController@me');
  Route::group(['middleware' => 'jwt.verify'], static function(){
    Route::post('/logout', 'AuthenticationController@logout');
    Route::post('/refresh', 'AuthenticationController@refresh');
    Route::get('/detail', 'AuthenticationController@detail'); });
});


/*----------------------- users endpoint ------------------------------------- */
Route::group(['prefix' => 'users'], function () {
  Route::get('/', 'UserController@getAll')->name('getusers');
  Route::get('/{id}', 'UserController@getOne')->name('getuser');
  Route::post('/', 'UserController@create')->name('createuser');
  Route::put('/{id}', 'UserController@update')->name('updateuser');
  Route::delete('/{id}', 'UserController@delete')->name('deleteuser');
});


/* ---------------------- products endpoint --------------------- */
Route::group(['prefix'=>'products'], function () {
  Route::get('/', 'ProductController@getAll');            //http://127.0.0.1:8000/api/products/
  Route::get('/{id}', 'ProductController@getOne');        //http://127.0.0.1:8000/api/products/1
  Route::post('/', 'ProductController@create');           //http://127.0.0.1:8000/api/products/
  Route::put('/{id}', 'ProductController@update');        //http://127.0.0.1:8000/api/products/1
  Route::delete('/{id}', 'ProductController@delete');     //http://127.0.0.1:8000/api/products/1
});

/* ---------------------- stores endpoint --------------------- */
Route::group(['prefix'=>'stores'], function () {
    Route::get('/', 'StoreController@getAll');            //http://127.0.0.1:8000/api/stores/
    Route::get('/{id}', 'StoreController@getOne');        //http://127.0.0.1:8000/api/stores/1
    Route::post('/', 'StoreController@create');           //http://127.0.0.1:8000/api/stores/
    Route::put('/{id}', 'StoreController@update');        //http://127.0.0.1:8000/api/stores/1
    Route::delete('/{id}', 'StoreController@delete');     //http://127.0.0.1:8000/api/stores/1
});

/* ---------------------- invoices endpoint --------------------- */
Route::group(['prefix'=>'invoices'], function () {
    Route::get('/', 'InvoiceController@getAll');            //http://127.0.0.1:8000/api/invoices/
    Route::get('/{id}', 'InvoiceController@getOne');        //http://127.0.0.1:8000/api/invoices/1
    Route::post('/', 'InvoiceController@create');           //http://127.0.0.1:8000/api/invoices/
    Route::put('/{id}', 'InvoiceController@update');        //http://127.0.0.1:8000/api/invoices/1
    Route::delete('/{id}', 'InvoiceController@delete');     //http://127.0.0.1:8000/api/invoices/1
});


/* ---------------------- orders endpoint --------------------- */
Route::group(['prefix'=>'orders'], function () {
    Route::get('/', 'OrderController@getAll');            //http://127.0.0.1:8000/api/orders/
    Route::get('/{id}', 'OrderController@getOne');        //http://127.0.0.1:8000/api/orders/
    Route::post('/', 'OrderController@create');           //http://127.0.0.1:8000/api/orders/
    Route::put('/{id}', 'OrderController@update');        //http://127.0.0.1:8000/api/orders/
    Route::delete('/{id}', 'OrderController@delete');     //http://127.0.0.1:8000/api/orders/
});





/* ---------------------- pdf --------------------- */
Route::get('pdf', 'PdfController@download');

/* */
Route::get('sendmail', 'EmailOrderController@sent');
