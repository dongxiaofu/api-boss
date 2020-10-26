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

Route::get('/hello', function () {
    return view('welcome');
});

Route::get('/foo', function () {
    return 'Hello World ' . date('Y-m-d H:i:s', time());
});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
