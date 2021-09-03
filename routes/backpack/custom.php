<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::get('/survey-definitions', 'SurveyDefinitionController@index');
    Route::get('/survey-definitions/{slug}', 'SurveyDefinitionController@show');

    Route::crud('/user', 'UserCrudController');
    Route::crud('/working-group', 'WorkingGroupCrudController');
    Route::crud('/curation-group', 'CurationGroupCrudController');
    Route::crud('/gene', 'GeneCrudController');
    Route::crud('/volunteer-type', 'VolunteerTypeCrudController');
    Route::crud('/volunteer-status', 'VolunteerStatusCrudController');
    Route::crud('/volunteer', 'VolunteerCrudController');
    Route::crud('/custom-survey', 'CustomSurveyCrudController');
    Route::crud('/campaign', 'CampaignCrudController');
    Route::crud('/goal', 'GoalCrudController');
    Route::crud('/interest', 'InterestCrudController');
    Route::crud('/motivation', 'MotivationCrudController');
    Route::crud('/self-description', 'SelfDescriptionCrudController');
    Route::crud('/upload-category', 'UploadCategoryCrudController');
    Route::crud('/faq', 'FaqCrudController');

    Route::crud('email', 'EmailCrudController');
    // Route::crud('notification', 'NotificationCrudController');
}); // this should be the absolute last line of this file

route::redirect('/admin/login', '/login');
