<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function province() {
        // $invoices = DB::connection('BDHIS_MINSA')->table('maestro_his_establecimiento')->get();
        $provinces = DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
                    ->select('Codigo_Red', 'Red')
                    ->where('Descripcion_Sector', '=', 'GOBIERNO REGIONAL')
                    ->where('Departamento', '=', 'PASCO')
                    ->where('Codigo_Red', '<>', '00')
                    ->groupBy('Codigo_Red', 'Red')
                    ->orderBy('Codigo_Red', 'ASC')
                    ->get();

        $data = [ "Codigo_Red" => "TODOS", "Red" => "TODOS", ];
        $provinces[] = $data;
        return response()->json($provinces, 200);
    }

    public function district(Request $request) {
        $red = $request->id;
        $districts = DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
                    ->select('Codigo_Red', 'Red', 'Distrito')
                    ->where('Descripcion_Sector', '=', 'GOBIERNO REGIONAL')
                    ->where('Departamento', '=', 'PASCO')
                    ->where('Codigo_Red', $red)
                    ->groupBy('Codigo_Red', 'Red', 'Distrito')
                    ->orderBy('Distrito', 'ASC')
                    ->get();

        $data = [ "Codigo_Red" => "TODOS", "Red" => "TODOS", "Distrito" => "TODOS", ];
        $districts[] = $data;
        return response()->json($districts, 200);
    }
}