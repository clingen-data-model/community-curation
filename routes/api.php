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
            Route::get('volunteers/{id}/assignments', 'AssignmentController@volunteer');
            Route::resource('volunteers', 'VolunteerController');
            Route::resource('assignments', 'AssignmentController')
                ->only(['index', 'store','show', 'update']);
            Route::resource('trainings', 'TrainingController')
                ->only(['update']);

            Route::get('users/current', 'UserController@currentUser')->name("current-user");

            /**
             * Stub method to allow full testing of assignment UI before attestation surveys built
             */
            Route::put('/dev/sign-attestation/{id}', function () {
                $attestation = \App\Attestation::findOrFail((int)request()->id);
                $attestation->update(['signed_at' => \Carbon\Carbon::now()]);
                return $attestation;
            });

            /**
             * Catch-all route for generic API read exposure
             **/
    
            // index
            Route::get('{model}', 'ApiController@index');
    
            // show
            Route::get('{model}/{id}', 'ApiController@show');
        });
    });
