<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'api'], function() {
    Route::post('list',  'HotpepperController@list');
});

Route::group(['middleware' => 'api'], function() {
    Route::get('user',  'MypageController@hashigo_history');
});

Route::group(['middleware' => 'api'], function() {
    Route::get('user',  'MypageController@hashigo_history');
});
