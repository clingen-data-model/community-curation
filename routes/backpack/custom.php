<?php

use App\Http\Controllers\App;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::prefix(config('backpack.base.route_prefix', 'admin'))->middleware('web', config('backpack.base.middleware_key', 'admin'))->group(function () { // custom admin routes
    Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
    Route::get('/survey-definitions', [App\Http\Controllers\Admin\SurveyDefinitionController::class, 'index']);
    Route::get('/survey-definitions/{slug}', [App\Http\Controllers\Admin\SurveyDefinitionController::class, 'show']);

    Route::crud('/user', \App\Http\Controllers\Admin\UserCrudController::class);
    Route::crud('/working-group', \App\Http\Controllers\Admin\WorkingGroupCrudController::class);
    Route::crud('/curation-group', \App\Http\Controllers\Admin\CurationGroupCrudController::class);
    Route::crud('/gene', \App\Http\Controllers\Admin\GeneCrudController::class);
    Route::crud('/volunteer-type', \App\Http\Controllers\Admin\VolunteerTypeCrudController::class);
    Route::crud('/volunteer-status', \App\Http\Controllers\Admin\VolunteerStatusCrudController::class);
    Route::crud('/volunteer', \App\Http\Controllers\Admin\VolunteerCrudController::class);
    Route::crud('/custom-survey', \App\Http\Controllers\Admin\CustomSurveyCrudController::class);
    Route::crud('/campaign', \App\Http\Controllers\Admin\CampaignCrudController::class);
    Route::crud('/goal', \App\Http\Controllers\Admin\GoalCrudController::class);
    Route::crud('/interest', \App\Http\Controllers\Admin\InterestCrudController::class);
    Route::crud('/motivation', \App\Http\Controllers\Admin\MotivationCrudController::class);
    Route::crud('/self-description', \App\Http\Controllers\Admin\SelfDescriptionCrudController::class);
    Route::crud('/upload-category', \App\Http\Controllers\Admin\UploadCategoryCrudController::class);
    Route::crud('/faq', \App\Http\Controllers\Admin\FaqCrudController::class);

    Route::crud('email', \App\Http\Controllers\Admin\EmailCrudController::class);
    // Route::crud('notification', 'NotificationCrudController');
}); // this should be the absolute last line of this file

route::redirect('/admin/login', '/login');
