<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Actions\SixMonthFollowupExport;
use App\Http\Controllers\FaqController;
use App\Actions\ThreeMonthFollowupExport;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\SurveyByIdController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AttestationController;
use App\Http\Controllers\GeneProtocolController;
use App\Http\Controllers\RequiredInfoController;
use App\Http\Controllers\CurationGroupController;
use App\Http\Controllers\AssignmentReportController;
use App\Http\Controllers\CurationActivityController;
use App\Http\Controllers\ApplicationReportController;
use App\Http\Controllers\CustomApplicationController;
use App\Http\Controllers\VolunteerFollowupController;

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
    Route::get('required-info', [RequiredInfoController::class, 'edit'])
        ->name('reqired-info.edit');

    Route::put('required-info', [RequiredInfoController::class, 'update'])
        ->name('required-info.update');

    Route::post('required-info', [RequiredInfoController::class, 'bypass'])
        ->name('required-info.bypass');
});

Route::group(['middleware' => ['auth', 'required-info']], function () {
    Route::impersonate();

    Route::resource('volunteers', VolunteerController::class)->only(['show', 'index']);

    Route::group(['middleware' => ['can:list trainings']], function () {
        Route::resource('trainings', TrainingController::class)->only(['show', 'index']);
        Route::resource('training-sessions', TrainingController::class)->only(['show', 'index']);
    });

    Route::get('surveys-by-id/{surveyId}/responses/{responseId}', [SurveyByIdController::class, 'show'])
        ->name('surveys.by-id.response');

    Route::resource('attestations', AttestationController::class)
        ->only('show', 'edit', 'update');

    Route::group(['middleware' => ['can:run reports']], function () {
        Route::get('reports', [ReportController::class, 'index'])
            ->name('report-index');

        Route::get('assignments-report', [AssignmentReportController::class, 'index'])
            ->name('assignment-report');

        Route::get('reports/three-month-followup', ThreeMonthFollowupExport::class);
        Route::get('reports/six-month-followup', SixMonthFollowupExport::class);

    });
    Route::get('applications-report', [ApplicationReportController::class, 'index'])
        ->name('appication-report');


    Route::get('volunteer-followup/{survey}/{responseId?}', [VolunteerFollowupController::class, 'show'])
        ->name('volunteer-followup.show');

    Route::post('volunteer-followup/{survey}/{responseId?}', [VolunteerFollowupController::class, 'store'])
        ->name('volunteer-followup.store');

    Route::get('volunteer-three-month/{responseId?}', [VolunteerFollowupController::class, 'threeMonth'])
        ->name('volunteer-three-month.show');

    Route::get('volunteer-six-month/{responseId?}', [VolunteerFollowupController::class, 'sixMonth'])
        ->name('volunteer-six-month.show');

    Route::get('genes/{symbol}/protocol', [GeneProtocolController::class, 'show'])
        ->name('gene.download-protocol');

    Route::resource('curation-groups', CurationGroupController::class)
        ->only(['index', 'show'])
        ->middleware('can:list curation-groups');

    Route::resource('curation-activities', CurationActivityController::class)
        ->only(['index', 'show'])
        ->middleware('can:list curation-activities');
});

Route::get('faq', [FaqController::class, 'index'])
    ->name('faq');

Route::get('apply/thank-you', [ThankYouController::class, 'show'])
    ->name('thank-you');

Route::get('apply/group/thank-you', [ThankYouController::class, 'show'])
    ->name('group.thank-you');

Route::get('apply/group/{name}', [CustomApplicationController::class, 'show'])
    ->name('application.custom.show');

Route::post('apply/group/{responseId?}', [CustomApplicationController::class, 'store'])
    ->name('application.custom.store');

Route::get('apply/{responseId?}', [ApplicationController::class, 'show'])
    ->name('application.show');

Route::post('apply/{responseId?}', [ApplicationController::class, 'store'])
    ->name('application.store');


Route::get('certificate', function (Request $request) {
    $user = App\User::where(['first_name' => 'marwa'])->first();
    $type = $request->type;
    $date = \Carbon\Carbon::now();
    if ($request->html) {
        return \Illuminate\Support\Facades\View::make('certificate', ['name' => '$user->name', 'type' => $type, 'date' => $date, 'curationActivity' => 'GENE FARTS'])->render();
    }
    
    $upload = (app()->make(App\Actions\TrainingCertificateGenerate::class))
            ->handle($user, $type, $date);

    if (file_exists($upload->file_path)) {
        return response(file_get_contents($upload->file_path), 200, ['Content-Type' => 'application/pdf'] );
    }
    return $upload->file_path.' does not exist';
    //   return \Storage::download();
});