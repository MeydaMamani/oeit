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

    public function departmentAll() {
        $dep = DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
                    ->select('Departamento')
                    ->groupBy('Departamento')
                    ->orderBy('Departamento', 'ASC')
                    ->get();

        return response()->json($dep, 200);
    }

    public function provAll(Request $request) {
        $prov = $request->id;
        $provinces_all = DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
                    ->select('Departamento', 'Provincia') ->where('Departamento', $prov)
                    ->groupBy('Departamento') ->groupBy('Provincia')
                    ->orderBy('Departamento', 'ASC') ->orderBy('Provincia')
                    ->get();

        return response()->json($provinces_all, 200);
    }

    public function distAll(Request $request) {
        $dist = $request->id;
        $distritcs_all = DB::connection('BDHIS_MINSA')->table('MAESTRO_HIS_ESTABLECIMIENTO')
                    ->select('Departamento', 'Provincia', 'Distrito') ->where('Provincia', $dist)
                    ->groupBy('Departamento') ->groupBy('Provincia') ->groupBy('Distrito')
                    ->orderBy('Departamento', 'ASC') ->orderBy('Provincia') ->groupBy('Distrito')
                    ->get();

        return response()->json($distritcs_all, 200);
    }
}