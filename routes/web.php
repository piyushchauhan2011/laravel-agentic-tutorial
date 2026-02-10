<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ApplicationController as WebApplicationController;
use App\Http\Controllers\Web\CandidateController as WebCandidateController;
use App\Http\Controllers\Web\DatabaseLearningController as WebDatabaseLearningController;
use App\Http\Controllers\Web\PipelineController as WebPipelineController;
use App\Http\Controllers\Web\PipelineMetricsController as WebPipelineMetricsController;
use App\Http\Controllers\Web\PositionController as WebPositionController;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'positionsCount' => Position::count(),
        'candidatesCount' => Candidate::count(),
        'applicationsCount' => Application::count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/learn/database', WebDatabaseLearningController::class)->name('learn.database');
    Route::get('/metrics/pipeline', WebPipelineMetricsController::class)->name('web.metrics.pipeline');

    Route::get('/positions', [WebPositionController::class, 'index'])->name('positions.index');
    Route::post('/positions', [WebPositionController::class, 'store'])->name('positions.store');
    Route::get('/candidates', [WebCandidateController::class, 'index'])->name('candidates.index');
    Route::post('/candidates', [WebCandidateController::class, 'store'])->name('candidates.store');
    Route::get('/applications', [WebApplicationController::class, 'index'])->name('applications.index');
    Route::post('/applications', [WebApplicationController::class, 'store'])->name('applications.store');
    Route::get('/pipelines', [WebPipelineController::class, 'index'])->name('pipelines.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
