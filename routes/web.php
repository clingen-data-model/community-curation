<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::redirect('/', '/volunteers');
Route::redirect('/home', '/volunteers');

Route::group(['middleware' => ['auth']], function () {
    Route::impersonate();

    Route::resource('volunteers', 'VolunteerController')->only(['show', 'index']);

    Route::get('surveys-by-id/{surveyId}/responses/{responseId}', function ($surveyId, $responseId) {
        $surveySlug = class_survey()::find($surveyId)->slug;
        return redirect(route('surveys.responses.show', [$surveySlug, $responseId]));
    });

    Route::resource('attestations', 'AttestationController')
        ->only('show', 'edit', 'update');

    Route::get('assignments-report', 'AssignmentReportController@index')->name('assignment-report');
});

Route::get('apply/thank-you', function (Request $request) {
    return view('application-thank-you');
});
Route::get('apply/{responseId?}', 'ApplicationController@show')->name('application.show');
Route::post('apply/{responseId?}', 'ApplicationController@store')->name('application.store');
