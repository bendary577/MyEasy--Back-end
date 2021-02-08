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


/* ------------------- localhost:8080/users/getusers----------------- */
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
    Route::get('/', 'ItemController@getAll');            //http://127.0.0.1:8000/api/items/getitems
    Route::get('/{id}', 'ItemController@getOne');        //http://127.0.0.1:8000/api/items/getitem
    Route::post('/', 'ItemController@create');           //http://127.0.0.1:8000/api/items/createitem
    Route::put('/{id}', 'ItemController@update');        //http://127.0.0.1:8000/api/items/updateitem
    Route::delete('/{id}', 'ItemController@delete');     //http://127.0.0.1:8000/api/items/deleteitem
  });






/* ---------------------- pdf --------------------- */
Route::get('pdf', 'PdfController@download');