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
    ], function () {
        Route::resource('expert-panels', 'ExpertPanelController')->only(['index', 'show']);
        
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            Route::resource('assignments', 'AssignmentController')
                ->only(['index', 'store','show', 'update']);

            Route::resource('trainings', 'TrainingController')
                ->only(['update']);

                
            Route::get('volunteers/{id}/assignments', 'AssignmentController@volunteer');
            Route::get('volunteers/{id}/attestations', 'AttestationController@volunteer');
            Route::resource('volunteers', 'VolunteerController');

            Route::get('users/current', 'UserController@currentUser')->name("current-user");

            Route::get('curator-uploads/{id}/file', 'CuratorUploadController@getFile')->name('curator-upload-file');
            Route::resource('curator-uploads', 'CuratorUploadController')->only(['index', 'show', 'store', 'update', 'destroy']);


            /**
             * Catch-all route for generic API read exposure
             **/
    
            // index
            Route::get('{model}', 'ApiController@index');
    
            // show
            Route::get('{model}/{id}', 'ApiController@show');
        });
    });
