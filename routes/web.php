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

require __DIR__.'/auth.php';

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
    Route::get('balance', 'App\Http\Controllers\BalanceController@execute');
    Route::get('forecast', 'App\Http\Controllers\ForecastController@index')->name('forecast');
    Route::get('coins', 'App\Http\Controllers\CoinController@index')->name('coins');
    Route::get('coingain', 'App\Http\Controllers\CoinController@getCoinGain');
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

