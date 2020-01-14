<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('tax', 'TaxJarController@Tax');
Route::get('epay', 'TaxJarController@EPay');



Route::get('tax_for_order', 'TaxController@getTaxForOrder');

