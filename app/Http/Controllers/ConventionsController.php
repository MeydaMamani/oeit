<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;

class ConventionsController extends Controller
{
    public function index(Request $request) {
        return view('diresaIndicators/ConventionsManagement/index');
    }
}