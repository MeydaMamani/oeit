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

    public function stablishment(Request $request) {
        $dist = $request->id;

        $estab = DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
                    ->select('Id_Establecimiento', 'Nombre_Establecimiento')
                    ->where('Descripcion_Sector', '=', 'GOBIERNO REGIONAL')
                    ->where('Distrito', '=', $dist)
                    ->orderBy('Id_Establecimiento', 'ASC')
                    ->get();

        $data = [ "Id_Establecimiento" => "TODOS", "Nombre_Establecimiento" => "TODOS", ];
        $estab[] = $data;
        return response()->json($estab, 200);
    }

    public function ups() {
        $listUps = DB::table('LISTA_UPS')
                    ->select('Id_Ups', 'Descripcion_Ups')
                    ->groupBy('Id_Ups', 'Descripcion_Ups')
                    ->orderBy('Descripcion_Ups', 'ASC')
                    ->get();

        $data = [ "Id_Ups" => "TODOS", "Descripcion_Ups" => "TODOS", ];
        $listUps[] = $data;
        return response()->json($listUps, 200);
    }

    public function datePadronNominal() {
        // DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
        $query =DB::connection('BD_PADRON_NOMINAL')->table('NOMINAL_PADRON_NOMINAL')
                    ->select((DB::raw('MAX(FECHA_MODIFICACION_REGISTRO) AS DATE_MODIFY')))
                    ->get();

        return response()->json($query, 200);
    }
}