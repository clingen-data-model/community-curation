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

    Route::group(['middleware' => ['can:list trainings']], function () {
        Route::resource('trainings', 'TrainingController')->only(['show', 'index']);
        Route::resource('training-sessions', 'TrainingController')->only(['show', 'index']);
    });

    Route::get('surveys-by-id/{surveyId}/responses/{responseId}', 'SurveyByIdController@show');

    Route::resource('attestations', 'AttestationController')
        ->only('show', 'edit', 'update');

    Route::get('reports', 'ReportController@index')->name('report-index');
    Route::get('assignments-report', 'AssignmentReportController@index')->name('assignment-report');
    Route::get('applications-report', 'ApplicationReportController@index')->name('appication-report');

    Route::get('volunteer-followup/{survey}/{responseId?}', 'VolunteerFollowupController@show')->name('volunteer-followup.show');
    Route::post('volunteer-followup/{survey}/{responseId?}', 'VolunteerFollowupController@store')->name('volunteer-followup.store');

    Route::get('volunteer-six-month/{responseId?}', 'VolunteerFollowupController@sixMonth')->name('volunteer-six-month.show');
    Route::get('volunteer-three-month/{responseId?}', 'VolunteerFollowupController@threeMonth')->name('volunteer-six-month.show');

    Route::get('genes/{symbol}/protocol', 'GeneProtocolController@show')->name('gene.download-protocol');
});

Route::get('apply/thank-you', 'ThankYouController@show');
Route::get('apply/{responseId?}', 'ApplicationController@show')->name('application.show');
Route::post('apply/{responseId?}', 'ApplicationController@store')->name('application.store');
