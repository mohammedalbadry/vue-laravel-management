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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


Route::get('setting',"SettingController@index");
Route::get('getorder/{id}', 'EndUserController@index');


Route::group(['middleware' => 'auth:api'], function(){
    Route::apiResource('product', ProductController::class);    
    Route::apiResource('clint', ClintController::class);
    Route::apiResource('Employee', EmployeeControoler::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('order', OrderController::class); 

    Route::post('home',"HomeController@index");
    Route::post('setting',"SettingController@update");
    Route::get('categories',"APIController@category_with_products");
    Route::post('clintsearch',"APIController@clint_search");


});