<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CurationActivitiesController;
use App\Http\Controllers\Api\CurationGroupController;
use App\Http\Controllers\Api\CuratorUploadController;
use App\Http\Controllers\Api\DemographicsController;
use App\Http\Controllers\Api\NotesController;
use App\Http\Controllers\Api\TimezoneController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Controllers\Api\TrainingSessionAttendeeController;
use App\Http\Controllers\Api\TrainingSessionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserPreferenceController;
use App\Http\Controllers\Api\VolunteerController;
use App\Http\Controllers\Api\VolunteerMetricsController;
use Illuminate\Support\Facades\Route;

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

// Route::group([
//         'namespace' => 'Api',
//     ], function () {
Route::resource('curation-groups', CurationGroupController::class)->only(['index', 'show']);
Route::resource('curation-groups', CurationGroupController::class)->only(['index', 'show']);
Route::get('curation-activities', [CurationActivitiesController::class, 'index'])->name('curation-activities-index');

Route::middleware('auth:api')->group(function () {
    Route::resource('applicaitons', ApplicationController::class)
        ->only(['index', 'show']);

    Route::resource('assignments', AssignmentController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::resource('trainings', TrainingController::class)
        ->only(['store', 'update']);

    Route::resource('training-sessions', TrainingSessionController::class);
    Route::middleware('role:admin|programmer|super-admin')->group(function () {
        Route::resource('training-sessions/{id}/attendees', TrainingSessionAttendeeController::class)->only(['index', 'store', 'destroy']);
        Route::get('training-sessions/{id}/trainable-volunteers', [TrainingSessionAttendeeController::class, 'trainableVolunteers'])
            ->name('training-sessions.trainable-volunteers');
        Route::post('training-sessions/{id}/attendees/email', [TrainingSessionAttendeeController::class, 'emailAttendees'])
            ->name('training-sessions.email-attendees');
        Route::get('training-sessions/{id}/invite-preview', [TrainingSessionController::class, 'inviteEmailPreview'])
            ->name('training-sessions.show.invite-preview');
        Route::get('training-sessions/invite-preview', [TrainingSessionController::class, 'inviteEmailPreview'])
            ->name('training-sessions.invite-preview');
    });

    Route::get('volunteers/metrics', [VolunteerMetricsController::class, 'index'])
        ->name('volunteers.metrics');
    Route::get('volunteers/{id}/assignments', [AssignmentController::class, 'volunteer'])
        ->name('volunteers.show.assignments');
    Route::put('volunteers/{id}/demographics', [DemographicsController::class, 'update'])
        ->name('volunteers.show.demographics');
    Route::resource('volunteers', VolunteerController::class);

    Route::get('users/current', [UserController::class, 'currentUser'])->name('current-user')
        ->name('users.current');
    Route::put('users/{id}/preferences/{preference_name}', [UserPreferenceController::class, 'update'])
        ->name('set-user-preference');
    Route::resource('users', UserController::class);

    Route::get('curator-uploads/{id}/file', [CuratorUploadController::class, 'getFile'])
        ->name('curator-upload-file');
    Route::resource('curator-uploads', CuratorUploadController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy']);

    Route::get('impersonatable-users', [UserController::class, 'impersonatableUsers'])
        ->name('impersonatable-users');

    Route::get('timezones', [TimezoneController::class, 'index'])
        ->name('timezones');

    Route::resource('notes', NotesController::class)->except(['create', 'edit']);

    /*
     * Catch-all route for generic API read exposure
     **/

    // index
    Route::get('{model}', [ApiController::class, 'index'])
        ->name('catchall.index');

    // show
    Route::get('{model}/{id}', [ApiController::class, 'show'])
        ->name('catchall.show');
});
// });
