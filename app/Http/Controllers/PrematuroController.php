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

        $dni = $request->dni;
        $query = DB::table('dbo.CONSOLIDADO_PREMATURO')
                ->where('CNV_O_DNI', '92775364')
                ->where('CORTE_PADRON', '202204')
                ->where('CORTE_PADRON', '2022-4')
                ->where('[BAJO_PESO_PREMATURO]', 'SI')
                ->orderBy('NOMBRE_PROV', 'ASC')
                ->get();
        return response()->json($query, 200);
    }
}

// $users = DB::table('users')
//                 ->where('votes', '=', 100)
//                 ->where('age', '>', 35)
//                 ->get();