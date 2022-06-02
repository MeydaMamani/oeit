<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FedController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () { return view('index'); });
// Route::get('/dashboard', 'dashboard@index');
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/prematuro', [FedController::class, 'index']);
Route::post('/prematuro/list', [FedController::class, 'listByMonth']);
Route::get('/prematuro/print', [FedController::class, 'printPrematuro']);

Route::get('prov/', [MainController::class, 'province']);
Route::get('distr/', [MainController::class, 'district']);

Route::get('/tmz', [FedController::class, 'indexTmz']);
Route::post('/tmz/list', [FedController::class, 'listByMonthTmz']);

Route::get('/4meses', [FedController::class, 'indexSuple']);
Route::post('/4meses/list', [FedController::class, 'listByMonthTmz']);
