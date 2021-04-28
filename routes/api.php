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
  Route::post('/login', 'AuthenticationController@login');              //http://127.0.0.1:8000/api/user/login
  Route::post('/register', 'AuthenticationController@register');        //http://127.0.0.1:8000/api/user/register
  Route::post('/me', 'AuthenticationController@me');                    //http://127.0.0.1:8000/api/user/me
  Route::group(['middleware' => 'jwt.verify'], static function(){
    Route::post('/logout', 'AuthenticationController@logout');          //http://127.0.0.1:8000/api/user/logout
    Route::post('/refresh', 'AuthenticationController@refresh');        //http://127.0.0.1:8000/api/user/refresh
    Route::get('/detail', 'AuthenticationController@detail'); });       //http://127.0.0.1:8000/api/user/details
});


/*----------------------- users endpoint ------------------------------------- */
Route::group(['prefix' => 'users'], function () {
  Route::get('/', 'UserController@getAll');                         //http://127.0.0.1:8000/api/users/
  Route::get('/{id}', 'UserController@getOne');                     //http://127.0.0.1:8000/api/users/1
  Route::post('/', 'UserController@create');                        //http://127.0.0.1:8000/api/users/
  Route::put('/{id}', 'UserController@update');                     //http://127.0.0.1:8000/api/users/1
  Route::delete('/{id}', 'UserController@delete');                  //http://127.0.0.1:8000/api/users/1
});

/*----------------------- admin profiles endpoint ------------------------------------- */
Route::group(['prefix' => 'admins/profiles'], function () {
    Route::get('/', 'AdminProfilesController@getAll');              //http://127.0.0.1:8000/api/admins/profiles/
    Route::get('/{id}', 'AdminProfilesController@getOne');          //http://127.0.0.1:8000/api/admins/profiles/1
    Route::post('/', 'AdminProfilesController@create');             //http://127.0.0.1:8000/api/admins/profiles/
    Route::put('/{id}', 'AdminProfilesController@update');          //http://127.0.0.1:8000/api/admins/profiles/1
    Route::delete('/{id}', 'AdminProfilesController@delete');       //http://127.0.0.1:8000/api/admins/profiles/1
});

/*----------------------- customer profiles endpoint ------------------------------------- */
Route::group(['prefix' => 'customers/profiles'], function () {
    Route::get('/', 'CustomerProfileController@getAll');            //http://127.0.0.1:8000/api/customers/profiles/
    Route::get('/{id}', 'CustomerProfileController@getOne');        //http://127.0.0.1:8000/api/customers/profiles/1
    Route::post('/', 'CustomerProfileController@create');           //http://127.0.0.1:8000/api/customers/profiles/
    Route::put('/{id}', 'CustomerProfileController@update');        //http://127.0.0.1:8000/api/customers/profiles/1
    Route::delete('/{id}', 'CustomerProfileController@delete');     //http://127.0.0.1:8000/api/customers/profiles/1
});

/*----------------------- seller profiles endpoint ------------------------------------- */
Route::group(['prefix' => 'sellers/profiles'], function () {
    Route::get('/', 'SellerProfileController@getAll');              //http://127.0.0.1:8000/api/sellers/profiles/
    Route::get('/{id}', 'SellerProfileController@getOne');          //http://127.0.0.1:8000/api/sellers/profiles/1
    Route::post('/', 'SellerProfileController@create');             //http://127.0.0.1:8000/api/sellers/profiles/
    Route::put('/{id}', 'SellerProfileController@update');          //http://127.0.0.1:8000/api/sellers/profiles/1
    Route::delete('/{id}', 'SellerProfileController@delete');       //http://127.0.0.1:8000/api/sellers/profiles/1
});

/*----------------------- company profiles endpoint ------------------------------------- */
Route::group(['prefix' => 'companies/profiles'], function () {
    Route::get('/', 'CompanyProfileController@getAll');             //http://127.0.0.1:8000/api/companies/profiles/
    Route::get('/{id}', 'CompanyProfileController@getOne');         //http://127.0.0.1:8000/api/companies/profiles/1
    Route::post('/', 'CompanyProfileController@create');            //http://127.0.0.1:8000/api/companies/profiles/
    Route::put('/{id}', 'CompanyProfileController@update');         //http://127.0.0.1:8000/api/companies/profiles/1
    Route::delete('/{id}', 'CompanyProfileController@delete');      //http://127.0.0.1:8000/api/companies/profiles/1
});

/* ---------------------- products endpoint --------------------- */
Route::group(['prefix'=>'products'], function () {
  Route::get('/', 'ProductController@getAll');                      //http://127.0.0.1:8000/api/products/
  Route::get('/{id}', 'ProductController@getOne');                  //http://127.0.0.1:8000/api/products/1
  Route::post('/', 'ProductController@create');                     //http://127.0.0.1:8000/api/products/
  Route::put('/{id}', 'ProductController@update');                  //http://127.0.0.1:8000/api/products/1
  Route::delete('/{id}', 'ProductController@delete');               //http://127.0.0.1:8000/api/products/1
});

/* ---------------------- stores endpoint --------------------- */
Route::group(['prefix'=>'stores'], function () {
    Route::get('/', 'StoreController@getAll');                      //http://127.0.0.1:8000/api/stores/
    Route::get('/{id}', 'StoreController@getOne');                  //http://127.0.0.1:8000/api/stores/1
    Route::post('/', 'StoreController@create');                     //http://127.0.0.1:8000/api/stores/
    Route::put('/{id}', 'StoreController@update');                  //http://127.0.0.1:8000/api/stores/1
    Route::delete('/{id}', 'StoreController@delete');               //http://127.0.0.1:8000/api/stores/1
});

/* ---------------------- invoices endpoint --------------------- */
Route::group(['prefix'=>'invoices'], function () {
    Route::get('/', 'InvoiceController@getAll');                    //http://127.0.0.1:8000/api/invoices/
    Route::get('/{id}', 'InvoiceController@getOne');                //http://127.0.0.1:8000/api/invoices/1
    Route::post('/', 'InvoiceController@create');                   //http://127.0.0.1:8000/api/invoices/
    Route::put('/{id}', 'InvoiceController@update');                //http://127.0.0.1:8000/api/invoices/1
    Route::delete('/{id}', 'InvoiceController@delete');             //http://127.0.0.1:8000/api/invoices/1
});


/* ---------------------- orders endpoint --------------------- */
Route::group(['prefix'=>'orders'], function () {
    Route::get('/', 'OrderController@getAll');                      //http://127.0.0.1:8000/api/orders/
    Route::get('/{id}', 'OrderController@getOne');                  //http://127.0.0.1:8000/api/orders/1
    Route::post('/', 'OrderController@create');                     //http://127.0.0.1:8000/api/orders/
    Route::put('/{id}', 'OrderController@update');                  //http://127.0.0.1:8000/api/orders/1
    Route::delete('/{id}', 'OrderController@delete');               //http://127.0.0.1:8000/api/orders/1
});

/* ---------------------- Categories endpoint --------------------- */
Route::group(['prefix'=>'category'], function () {
    Route::get('/', 'CategoryController@getAll');                      //http://127.0.0.1:8000/api/Categories/
    Route::get('/{id}', 'CategoryController@getOne');                  //http://127.0.0.1:8000/api/Categories/1
    Route::post('/', 'CategoryController@create');                     //http://127.0.0.1:8000/api/Categories/
    Route::put('/{id}', 'CategoryController@update');                  //http://127.0.0.1:8000/api/Categories/1
    Route::delete('/{id}', 'CategoryController@delete');               //http://127.0.0.1:8000/api/categories/1
});

/* ---------------------- Caets endpoint --------------------- */
Route::group(['prefix'=>'cart'], function () {
    Route::get('/', 'CartController@getAll');                      //http://127.0.0.1:8000/api/Cart/
    Route::post('/', 'CartController@create');                     //http://127.0.0.1:8000/api/Cart/
    Route::put('/{id}', 'CartController@update');                  //http://127.0.0.1:8000/api/Cart/1
    Route::delete('/{id}', 'CartController@delete');               //http://127.0.0.1:8000/api/Cart/1
});

/* ---------------------- pdf --------------------- */
Route::get('pdf', 'PdfController@download');

/* */
Route::get('sendmail', 'EmailOrderController@sent');
