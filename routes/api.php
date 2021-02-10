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
Route::group([ 'middleware' => ['api'],'prefix' => 'user'], static function ($router) {
  Route::post('/login', 'AuthenticationController@login');
  Route::post('/register', 'AuthenticationController@register');
  Route::post('/me', 'AuthenticationController@me');
  Route::group(['middleware' => 'jwt.verify'], static function( $router){
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


/* ---------------------- items endpoint --------------------- */
Route::group(['prefix'=>'products'], function () {
  Route::get('/', 'ProductController@getAll');            //http://127.0.0.1:8000/api/products/getitems
  Route::get('/{id}', 'ProductController@getOne');        //http://127.0.0.1:8000/api/products/getitem
  Route::post('/', 'ProductController@create');           //http://127.0.0.1:8000/api/products/createitem
  Route::put('/{id}', 'ProductController@update');        //http://127.0.0.1:8000/api/products/updateitem
  Route::delete('/{id}', 'ProductController@delete');     //http://127.0.0.1:8000/api/products/deleteitem
});


/* ---------------------- pdf --------------------- */
Route::get('pdf', 'PdfController@download');

/* */
Route::get('sendmail', 'OrderController@sent');
