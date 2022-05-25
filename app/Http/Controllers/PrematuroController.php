<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrematuroController extends Controller
{
    public function index(Request $request) {
        return view('fed/niño/prematuro');
    }

    public function searchDni(Request $request){

        // $dni = $request->dni;
        $query = DB::table('dbo.CONSOLIDADO_PREMATURO')
                ->where('CORTE_PADRON', '202205')
                ->where('PERIODO_MEDICION', '2022-5')
                ->where('BAJO_PESO_PREMATURO', 'SI')
                ->orderBy('NOMBRE_PROV', 'ASC')
                ->orderBy('NOMBRE_DIST', 'ASC')
                ->orderBy('NOMBRE_EESS', 'ASC')
                ->get();
        return response()->json($query, 200);
    }
}