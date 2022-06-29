<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConventionsController;

Route::get('/conventions', [ConventionsController::class, 'index']);