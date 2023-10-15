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

    Route::crud('/user', 'App\Http\Controllers\Admin\UserCrudController');
    Route::crud('/working-group', 'App\Http\Controllers\Admin\WorkingGroupCrudController');
    Route::crud('/curation-group', 'App\Http\Controllers\Admin\CurationGroupCrudController');
    Route::crud('/gene', 'App\Http\Controllers\Admin\GeneCrudController');
    Route::crud('/volunteer-type', 'App\Http\Controllers\Admin\VolunteerTypeCrudController');
    Route::crud('/volunteer-status', 'App\Http\Controllers\Admin\VolunteerStatusCrudController');
    Route::crud('/volunteer', 'App\Http\Controllers\Admin\VolunteerCrudController');
    Route::crud('/custom-survey', 'App\Http\Controllers\Admin\CustomSurveyCrudController');
    Route::crud('/campaign', 'App\Http\Controllers\Admin\CampaignCrudController');
    Route::crud('/goal', 'App\Http\Controllers\Admin\GoalCrudController');
    Route::crud('/interest', 'App\Http\Controllers\Admin\InterestCrudController');
    Route::crud('/motivation', 'App\Http\Controllers\Admin\MotivationCrudController');
    Route::crud('/self-description', 'App\Http\Controllers\Admin\SelfDescriptionCrudController');
    Route::crud('/upload-category', 'App\Http\Controllers\Admin\UploadCategoryCrudController');
    Route::crud('/faq', 'App\Http\Controllers\Admin\FaqCrudController');

    Route::crud('email', 'App\Http\Controllers\Admin\EmailCrudController');
    // Route::crud('notification', 'NotificationCrudController');
}); // this should be the absolute last line of this file

route::redirect('/admin/login', '/login');
