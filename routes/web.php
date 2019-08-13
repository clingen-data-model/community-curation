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

Auth::routes();

Route::redirect('/', '/volunteers');
Route::redirect('/home', '/volunteers');

Route::group(['middleware' => ['auth']], function () {
    Route::impersonate();

    Route::resource('volunteers', 'VolunteerController')->only(['show', 'index']);
});

Route::get('apply/thank-you', function (Request $request) {
    return view('application-thank-you');
});
Route::get('apply/{responseId?}', 'ApplicationController@show')->name('application.show');
Route::post('apply/{responseId?}', 'ApplicationController@store')->name('application.store');
