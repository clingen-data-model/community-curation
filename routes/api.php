<?php

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
    ], function () {
        Route::resource('curation-groups', 'CurationGroupController')->only(['index', 'show']);
        Route::resource('curation-groups', 'CurationGroupController')->only(['index', 'show']);
        Route::get('curation-activities', 'CurationActivitiesController@index')->name('curation-activities-index');

        Route::group([
            'middleware' => 'auth:api',
        ], function () {
            Route::resource('applicaitons', 'ApplicationController')
                ->only(['index', 'show']);

            Route::resource('assignments', 'AssignmentController')
                ->only(['index', 'store', 'show', 'update', 'destroy']);

            Route::resource('trainings', 'TrainingController')
                ->only(['store', 'update']);

            Route::resource('training-sessions', 'TrainingSessionController');
            Route::group(['middleware' => ['role:admin|programmer']], function () {
                Route::resource('training-sessions/{id}/attendees', 'TrainingSessionAttendeeController')->only(['index', 'store', 'destroy']);
                Route::get('training-sessions/{id}/trainable-volunteers', 'TrainingSessionAttendeeController@trainableVolunteers');
                Route::post('training-sessions/{id}/attendees/email', 'TrainingSessionAttendeeController@emailAttendees');
                Route::get('training-sessions/{id}/invite-preview', 'TrainingSessionController@inviteEmailPreview');
            });

            Route::get('volunteers/metrics', 'VolunteerMetricsController@index');
            Route::get('volunteers/{id}/assignments', 'AssignmentController@volunteer');
            Route::put('volunteers/{id}/demographics', 'DemographicsController@update');
            Route::resource('volunteers', 'VolunteerController');

            Route::get('users/current', 'UserController@currentUser')->name('current-user');
            Route::put('users/{id}/preferences/{preference_name}', 'UserPreferenceController@update')->name('set-user-preference');
            Route::resource('users', 'UserController');

            Route::get('curator-uploads/{id}/file', 'CuratorUploadController@getFile')->name('curator-upload-file');
            Route::resource('curator-uploads', 'CuratorUploadController')->only(['index', 'show', 'store', 'update', 'destroy']);

            Route::get('impersonatable-users', 'UserController@impersonatableUsers');

            Route::get('timezones', 'TimezoneController@index');

            Route::resource('notes', 'NotesController')->except(['create', 'edit']);

            /*
             * Catch-all route for generic API read exposure
             **/

            // index
            Route::get('{model}', 'ApiController@index');

            // show
            Route::get('{model}/{id}', 'ApiController@show');
        });
    });
