<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FedController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ConventionsController;

Route::get('/conventions', [ConventionsController::class, 'index']);
Route::post('/conventions/list', [ConventionsController::class, 'listVaccineBcgHvb']);

Route::post('prov/', [MainController::class, 'province']);
Route::post('distr/', [MainController::class, 'district']);
Route::post('pn/', [MainController::class, 'datePadronNominal']);

Route::get('/', function () { return view('index'); });
// Route::get('/dashboard', 'dashboard@index');
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/premature', [FedController::class, 'index']);
Route::post('/premature/list', [FedController::class, 'listPremature']);
Route::get('/premature/print', [FedController::class, 'printPremature']);

Route::get('/tmz', [FedController::class, 'indexTmz']);
Route::post('/tmz/list', [FedController::class, 'listTmzNeonatal']);
Route::get('/tmz/print', [FedController::class, 'printTmz']);

Route::get('/supplementation', [FedController::class, 'indexSuple']);
Route::post('/supplementation/list', [FedController::class, 'listSuple']);
Route::get('/supplementation/print', [FedController::class, 'printSuple']);

Route::get('/iniOport', [FedController::class, 'indexIniOport']);
Route::post('/iniOport/list', [FedController::class, 'listIniOportuno']);
Route::get('/iniOport/print', [FedController::class, 'printIniOportuno']);

Route::get('/cred', [FedController::class, 'indexCredMes']);
Route::post('/cred/list', [FedController::class, 'listCredMes']);
Route::get('/cred/print', [FedController::class, 'printCredMes']);

Route::get('/childPackage', [FedController::class, 'indexChildPackage']);
Route::get('/childPackage/print', [FedController::class, 'printchildPackage']);


Route::get('/bateria', [FedController::class, 'indexBateria']);
Route::post('/bateria/list', [FedController::class, 'listBateria']);
Route::get('/bateria/print', [FedController::class, 'printBateria']);

Route::get('/tratamiento', [FedController::class, 'indexTratamiento']);
Route::post('/tratamiento/listSos', [FedController::class, 'listSospecha']);
Route::get('/tratamiento/printSos', [FedController::class, 'printSospecha']);
Route::post('/tratamiento/listTrat', [FedController::class, 'listTratamiento']);
Route::get('/tratamiento/printSos', [FedController::class, 'printSospecha']);

Route::get('/professionals', [FedController::class, 'indexProfesion']);
Route::post('/professionals/list', [FedController::class, 'listSuple']);
Route::get('/professionals/print', [FedController::class, 'printSuple']);

