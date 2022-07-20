<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TracingController;

Route::get('/', function () { return view('index'); });

Route::post('prov/', [MainController::class, 'province']);
Route::post('distr/', [MainController::class, 'district']);
Route::post('stablishment/', [MainController::class, 'stablishment']);
Route::post('ups/', [MainController::class, 'ups']);
Route::post('pn/', [MainController::class, 'datePadronNominal']);

Route::post('department/', [MainController::class, 'departmentAll']);
Route::post('provinces/', [MainController::class, 'provAll']);
Route::post('districts/', [MainController::class, 'distAll']);

Route::get('/metals', [TracingController::class, 'indexHeavyMetals']);
Route::post('/metals/list', [TracingController::class, 'listHeavyMetals']);
Route::post('/metals/listDni', [TracingController::class, 'listHeavyMetalsDni']);
Route::get('/metals/print', [TracingController::class, 'printHeavyMetals']);
Route::get('/metals/printXdni', [TracingController::class, 'printHeavyMetalsDni']);

Route::get('/homologation', [TracingController::class, 'indexHomologation']);
Route::put('/homologation/put', [TracingController::class, 'updateHomologation']);
Route::post('/homologation/month', [TracingController::class, 'searchXMonth']);
Route::get('/homologation/printPdf', [TracingController::class, 'downloadPdf']);
Route::get('/homologation/printExcel', [TracingController::class, 'downloaExcel']);

Route::get('/user', [TracingController::class, 'indexNewUser']);
Route::post('/user/create', [TracingController::class, 'createUser']);