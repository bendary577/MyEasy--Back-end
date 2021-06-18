<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Store;
// use Auth;
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

Route::middleware('auth:api')->get('/usera', function (Request $request) {
    return Auth::user();
});

//------------------------ public apis --------------------------
Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', 'Auth\AuthenticationController@login')->name('login.api');
    Route::post('/register', 'Auth\AuthenticationController@register')->name('register.api');
});

//------------------------ protected apis ------------------------
// Route::group(['middleware' => ['auth:api', 'jwt.verify']] , function () {

    /*------------------------------ authentication endpoints ------------*/
    Route::group(['prefix' => 'user'], static function(){
        Route::post('/logout', 'Auth\AuthenticationController@logout');          //http://127.0.0.1:8000/api/user/logout
        Route::post('/refresh', 'Auth\AuthenticationController@refresh');        //http://127.0.0.1:8000/api/user/refresh
        Route::get('/me', 'Auth\AuthenticationController@details');              //http://127.0.0.1:8000/api/user/me
        Route::get('/activate/{token}', 'Auth\AuthenticationController@activate');
    });


    /*--------------------------- reset password api endpoints -------------*/
    Route::group(['prefix' => 'password'], function () {
        Route::post('reset_request', 'Auth\AuthenticationController@resetPasswordRequest');
        Route::get('find/{token}', 'Auth\AuthenticationController@findResetPasswordRequest');
        Route::post('reset', 'Auth\AuthenticationController@resetPassword');
    });

    /*----------------------- users endpoint ------------------------------------- */
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'Rest\Profiles\UserController@getAll');                         //http://127.0.0.1:8000/api/users/
        Route::get('/{id}', 'Rest\Profiles\UserController@getOne');                     //http://127.0.0.1:8000/api/users/1
        Route::post('/', 'Rest\Profiles\UserController@create');                        //http://127.0.0.1:8000/api/users/
        Route::put('/{id}', 'Rest\Profiles\UserController@update');                     //http://127.0.0.1:8000/api/users/1
        Route::delete('/{id}', 'Rest\Profiles\UserController@delete');                  //http://127.0.0.1:8000/api/users/1
    });

    /*----------------------- admin profiles endpoint ------------------------------------- */
    Route::group(['prefix' => 'admins/profiles'], function () {
        Route::get('/', 'Rest\Profiles\AdminProfileController@getAll');              //http://127.0.0.1:8000/api/admins/profiles/
        Route::get('/{id}', 'Rest\Profiles\AdminProfileController@getOne');          //http://127.0.0.1:8000/api/admins/profiles/1
        Route::post('/', 'Rest\Profiles\AdminProfileController@create');             //http://127.0.0.1:8000/api/admins/profiles/
        Route::put('/{id}', 'Rest\Profiles\AdminProfileController@update');          //http://127.0.0.1:8000/api/admins/profiles/1
        Route::delete('/{id}', 'Rest\Profiles\AdminProfileController@delete');       //http://127.0.0.1:8000/api/admins/profiles/1
    });

    /*----------------------- customer profiles endpoint ------------------------------------- */
    Route::group(['prefix' => 'customers/profiles'], function () {
        Route::get('/', 'Rest\Profiles\CustomerProfileController@getAll');            //http://127.0.0.1:8000/api/customers/profiles/
        Route::get('/{id}', 'Rest\Profiles\CustomerProfileController@getOne');        //http://127.0.0.1:8000/api/customers/profiles/1
        Route::post('/', 'Rest\Profiles\CustomerProfileController@create');           //http://127.0.0.1:8000/api/customers/profiles/
        Route::put('/{id}', 'Rest\Profiles\CustomerProfileController@update');        //http://127.0.0.1:8000/api/customers/profiles/1
        Route::delete('/{id}', 'Rest\Profiles\CustomerProfileController@delete');     //http://127.0.0.1:8000/api/customers/profiles/1
    });

    /*----------------------- seller profiles endpoint ------------------------------------- */
    Route::group(['prefix' => 'sellers/profiles'], function () {
        Route::get('/', 'Rest\Profiles\SellerProfileController@getAll');              //http://127.0.0.1:8000/api/sellers/profiles/
        Route::get('/{id}', 'Rest\Profiles\SellerProfileController@getOne');          //http://127.0.0.1:8000/api/sellers/profiles/1
        Route::post('/', 'Rest\Profiles\SellerProfileController@create');             //http://127.0.0.1:8000/api/sellers/profiles/
        Route::put('/{id}', 'Rest\Profiles\SellerProfileController@update');          //http://127.0.0.1:8000/api/sellers/profiles/1
        Route::delete('/{id}', 'Rest\Profiles\SellerProfileController@delete');       //http://127.0.0.1:8000/api/sellers/profiles/1
    });

    /*----------------------- company profiles endpoint ------------------------------------- */
    Route::group(['prefix' => 'companies/profiles'], function () {
        Route::get('/', 'Rest\Profiles\CompanyProfileController@getAll');             //http://127.0.0.1:8000/api/companies/profiles/
        Route::get('/{id}', 'Rest\Profiles\CompanyProfileController@getOne');         //http://127.0.0.1:8000/api/companies/profiles/1
        Route::post('/', 'Rest\Profiles\CompanyProfileController@create');            //http://127.0.0.1:8000/api/companies/profiles/
        Route::put('/{id}', 'Rest\Profiles\CompanyProfileController@update');         //http://127.0.0.1:8000/api/companies/profiles/1
        Route::delete('/{id}', 'Rest\Profiles\CompanyProfileController@delete');      //http://127.0.0.1:8000/api/companies/profiles/1
    });

    /* ---------------------- products endpoint --------------------- */
    Route::group(['prefix'=>'product'], function () {
        Route::get('/', 'Rest\Stores\ProductController@getAll');                      //http://127.0.0.1:8000/api/products/
        Route::get('/{id}', 'Rest\Stores\ProductController@getOne');                 //http://127.0.0.1:8000/api/products/1
        Route::get('/store/{id}', 'Rest\Stores\ProductController@get_product_store');                 //http://127.0.0.1:8000/api/products/1
        Route::post('/', 'Rest\Stores\ProductController@create');                     //http://127.0.0.1:8000/api/products/
        Route::post('/{id}', 'Rest\Stores\ProductController@update');                  //http://127.0.0.1:8000/api/products/1
        Route::post('/delete/{id}', 'Rest\Stores\ProductController@delete');               //http://127.0.0.1:8000/api/products/1
    });

    /* ---------------------- stores endpoint --------------------- */
    Route::group(['prefix' => 'store'], function () {
        Route::get('/', 'Rest\Stores\StoreController@getAll');                      //http://127.0.0.1:8000/api/stores/
        Route::get('/{id}', 'Rest\Stores\StoreController@getOne');                  //http://127.0.0.1:8000/api/stores/1
        Route::post('/', 'Rest\Stores\StoreController@create');                     //http://127.0.0.1:8000/api/stores/
        Route::post('/{id}', 'Rest\Stores\StoreController@update');                  //http://127.0.0.1:8000/api/stores/1
        Route::post('/delete/{id}', 'Rest\Stores\StoreController@delete');               //http://127.0.0.1:8000/api/stores/1
    });

    /* ---------------------- invoices endpoint --------------------- */
    Route::group(['prefix'=>'invoice'], function () {
        Route::get('/', 'Rest\Stores\InvoiceController@getAll');                    //http://127.0.0.1:8000/api/invoices/
        Route::get('/user', 'Rest\Stores\InvoiceController@get_invoice_user');                    //http://127.0.0.1:8000/api/invoices/
        Route::get('/{id}', 'Rest\Stores\InvoiceController@getOne');                //http://127.0.0.1:8000/api/invoices/1
        Route::post('/', 'Rest\Stores\InvoiceController@create');                   //http://127.0.0.1:8000/api/invoices/
        Route::post('/{id}', 'Rest\Stores\InvoiceController@update');                //http://127.0.0.1:8000/api/invoices/1
        Route::post('/delete/{id}', 'Rest\Stores\InvoiceController@delete');             //http://127.0.0.1:8000/api/invoices/1
    });

    /* ---------------------- orders endpoint --------------------- */
    Route::group(['prefix'=>'order'], function () {
        Route::get('/', 'Rest\Stores\OrderController@getAll');                      //http://127.0.0.1:8000/api/orders/
        Route::get('/{id}', 'Rest\Stores\OrderController@get_order');                  //http://127.0.0.1:8000/api/orders/1
        Route::post('/', 'Rest\Stores\OrderController@create');                     //http://127.0.0.1:8000/api/orders/
        // Route::put('/{id}', 'Rest\Stores\OrderController@update');                  //http://127.0.0.1:8000/api/orders/1
        Route::post('/delete/{id}', 'Rest\Stores\OrderController@delete');               //http://127.0.0.1:8000/api/orders/1
    });

    /* ---------------------- Categories endpoint --------------------- */
    Route::group(['prefix'=>'category'], function () {
        Route::get('/', 'Rest\Stores\CategoryController@getAll');                      //http://127.0.0.1:8000/api/Categories/
        Route::get('/{id}', 'Rest\Stores\CategoryController@getOne');                  //http://127.0.0.1:8000/api/Categories/1
        Route::get('with//stores', 'Rest\Stores\CategoryController@category_store');                  //http://127.0.0.1:8000/api/Categories/1
        Route::post('/', 'Rest\Stores\CategoryController@create');                     //http://127.0.0.1:8000/api/Categories/
        Route::post('/{id}', 'Rest\Stores\CategoryController@update');                  //http://127.0.0.1:8000/api/Categories/1
        Route::post('/delete/{id}', 'Rest\Stores\CategoryController@delete');               //http://127.0.0.1:8000/api/categories/1
    });

    /* ---------------------- Carts endpoint --------------------- */
    Route::group(['prefix'=>'cart'], function () {
        Route::get('/', 'Rest\Accounts\CartController@getAll');                       //http://127.0.0.1:8000/api/Cart/
        Route::post('/', 'Rest\Accounts\CartController@create');                      //http://127.0.0.1:8000/api/Cart/
        Route::post('/increase', 'Rest\Accounts\CartController@increase');            //http://127.0.0.1:8000/api/Cart/
        Route::post('/decrease', 'Rest\Accounts\CartController@decrease');            //http://127.0.0.1:8000/api/Cart/
        // Route::put('/{id}', 'Rest\Accounts\CartController@update');                //http://127.0.0.1:8000/api/Cart/1
        Route::post('/delete/{id}', 'Rest\Accounts\CartController@destroy');          //http://127.0.0.1:8000/api/Cart/1
    });

    Route::group(['prefix'=>'rating'], function () {
        Route::get('/user', 'Rest\Accounts\RatingController@get_user_rating');         //http://127.0.0.1:8000/api/Cart/
        Route::get('/product/{id}', 'Rest\Accounts\RatingController@get_product_rating');   //http://127.0.0.1:8000/api/Cart/
        Route::post('/', 'Rest\Accounts\RatingController@create');                      //http://127.0.0.1:8000/api/Cart/
        Route::post('/edit', 'Rest\Accounts\RatingController@update');                      //http://127.0.0.1:8000/api/Cart/
        Route::post('/delete/{id}', 'Rest\Accounts\RatingController@delete');                      //http://127.0.0.1:8000/api/Cart/
    });

    Route::group(['prefix'=>'complaint'], function () {
        Route::get('/', 'Rest\Accounts\ComplaintController@getAll');         //http://127.0.0.1:8000/api/Cart/
        Route::get('/{id}', 'Rest\Accounts\ComplaintController@getOne');         //http://127.0.0.1:8000/api/Cart/
        Route::get('/user', 'Rest\Accounts\ComplaintController@get_user_complaint');         //http://127.0.0.1:8000/api/Cart/
        Route::post('/', 'Rest\Accounts\ComplaintController@create');         //http://127.0.0.1:8000/api/Cart/
        Route::post('/edit/{id}', 'Rest\Accounts\ComplaintController@update');         //http://127.0.0.1:8000/api/Cart/
        Route::post('/delete/{id}', 'Rest\Accounts\ComplaintController@delete');         //http://127.0.0.1:8000/api/Cart/
    });

    Route::post('/search', 'Search\SearchController@search');
// });



//http://127.0.0.1:8000/api/user/login
/*----------------------- authentication endpoints ------------------------------------- */
Route::group([ 'middleware' => ['api'],'prefix' => 'user'], static function() {
    Route::post('/login', 'Auth\AuthenticationController@login');              //http://127.0.0.1:8000/api/user/login
    Route::post('/register', 'Auth\AuthenticationController@register');        //http://127.0.0.1:8000/api/user/register
    Route::post('/me', 'Auth\AuthenticationController@me');                    //http://127.0.0.1:8000/api/user/me
});

Route::group(['middleware' => 'auth:api' /*jwt.verify*/], static function(){
    Route::post('/logout', 'Auth\AuthenticationController@logout');          //http://127.0.0.1:8000/api/user/logout
    Route::post('/refresh', 'Auth\AuthenticationController@refresh');        //http://127.0.0.1:8000/api/user/refresh
    Route::get('/details', 'Auth\AuthenticationController@detail');           //http://127.0.0.1:8000/api/user/details
});


Route::post('/test', function(Request $request){
    //
});