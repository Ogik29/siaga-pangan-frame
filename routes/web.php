<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ReportController;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/analysis', [AnalysisController::class, 'index'])
    ->name('analysis');

Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports');
