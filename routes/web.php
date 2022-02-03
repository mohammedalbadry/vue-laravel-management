<?php

use Illuminate\Support\Facades\Route;

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
/*
route::get('test', function(){
    $arr =
    [
        {"id":5,"quantity":1},
        {"id":4,"quantity":2}
    ]
})*/
Route::get('{any}', function () { 
    return view('index');
})->where('any', '.*'); 



Route::get('/home', 'HomeController@index')->name('home');

