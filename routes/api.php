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

Route::group([
        'namespace' => 'Api',
        // 'middleware' => 'auth:api'
    ], function () {

        Route::resource('volunteers', 'VolunteerController');

        /** 
         * Catch-all route for generic API read exposure
         **/

        // index
        Route::get('{model}', 'ApiController@index');

        // show
        Route::get('{model}/{id}', 'ApiController@show');
    });
