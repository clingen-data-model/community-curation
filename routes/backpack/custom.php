<?php

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
    CRUD::resource('/user', 'UserCrudController');
    CRUD::resource('/working-group', 'WorkingGroupCrudController');
    CRUD::resource('/expert-panel', 'ExpertPanelCrudController');
}); // this should be the absolute last line of this file

route::redirect('/admin/login', '/login');
