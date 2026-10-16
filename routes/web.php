<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\ShipmentController;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [DashboardController::class, 'index']
)->name('dashboard');


/*
|--------------------------------------------------------------------------
| Analysis
|--------------------------------------------------------------------------
*/

Route::get(
    '/analysis',
    [AnalysisController::class, 'index']
)->name('analysis');

Route::post(
    '/analysis',
    [AnalysisController::class, 'analyze']
)->name('analysis.run');


/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::get(
    '/reports',
    [ReportController::class, 'index']
)->name('reports');

Route::post(
    '/reports',
    [ReportController::class, 'store']
)->name('reports.store');


/*
|--------------------------------------------------------------------------
| Distribution
|--------------------------------------------------------------------------
*/

Route::post(
    '/distribution/recommend',
    [DistributionController::class, 'recommend']
)->name('distribution.recommend');

Route::post(
    '/distribution/{prediction}/send',
    [
        DistributionController::class,
        'sendRecommendation',
    ]
)->name('distribution.send');


/*
|--------------------------------------------------------------------------
| Shipments
|--------------------------------------------------------------------------
*/

Route::get(
    '/shipments/{shipment}',
    [ShipmentController::class, 'show']
)->name('shipments.show');

Route::post(
    '/shipments/{shipment}/confirm-sender',
    [
        ShipmentController::class,
        'confirmSender',
    ]
)->name('shipments.confirm.sender');

Route::post(
    '/shipments/{shipment}/confirm-receiver',
    [
        ShipmentController::class,
        'confirmReceiver',
    ]
)->name('shipments.confirm.receiver');
