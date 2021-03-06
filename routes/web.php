<?php

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
    Route::get('required-info', 'RequiredInfoController@edit')
        ->name('reqired-info.edit');

    Route::put('required-info', 'RequiredInfoController@update')
        ->name('required-info.update');

    Route::post('required-info', 'RequiredInfoController@bypass')
        ->name('required-info.bypass');
});

Route::group(['middleware' => ['auth', 'required-info']], function () {
    Route::impersonate();

    Route::resource('volunteers', 'VolunteerController')->only(['show', 'index']);

    Route::group(['middleware' => ['can:list trainings']], function () {
        Route::resource('trainings', 'TrainingController')->only(['show', 'index']);
        Route::resource('training-sessions', 'TrainingController')->only(['show', 'index']);
    });

    Route::get('surveys-by-id/{surveyId}/responses/{responseId}', 'SurveyByIdController@show')
        ->name('surveys.by-id.response');

    Route::resource('attestations', 'AttestationController')
        ->only('show', 'edit', 'update');

    Route::group(['middleware' => ['can:run reports']], function () {
        Route::get('reports', 'ReportController@index')
            ->name('report-index');

        Route::get('assignments-report', 'AssignmentReportController@index')
            ->name('assignment-report');
    });
    Route::get('applications-report', 'ApplicationReportController@index')
        ->name('appication-report');

    Route::get('volunteer-followup/{survey}/{responseId?}', 'VolunteerFollowupController@show')
        ->name('volunteer-followup.show');

    Route::post('volunteer-followup/{survey}/{responseId?}', 'VolunteerFollowupController@store')
        ->name('volunteer-followup.store');

    Route::get('volunteer-three-month/{responseId?}', 'VolunteerFollowupController@threeMonth')
        ->name('volunteer-three-month.show');

    Route::get('volunteer-six-month/{responseId?}', 'VolunteerFollowupController@sixMonth')
        ->name('volunteer-six-month.show');

    Route::get('genes/{symbol}/protocol', 'GeneProtocolController@show')
        ->name('gene.download-protocol');

    Route::resource('curation-groups', 'CurationGroupController')
        ->only(['index', 'show'])
        ->middleware('can:list curation-groups');

    Route::resource('curation-activities', 'CurationActivityController')
        ->only(['index', 'show'])
        ->middleware('can:list curation-activities');
});

Route::get('faq', 'FaqController@index')
    ->name('faq');

Route::get('apply/thank-you', 'ThankYouController@show')
    ->name('than-you');

Route::get('apply/{responseId?}', 'ApplicationController@show')
    ->name('application.show');

Route::post('apply/{responseId?}', 'ApplicationController@store')
    ->name('application.store');
