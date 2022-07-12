<?php

namespace App\Http\Controllers;

use App\Exports\PrematureExport;
use App\Exports\TmzNeonatalExport;
use App\Exports\SixEightMonthExport;
use App\Exports\BateriaExport;
use App\Exports\SospechaVioExport;
use App\Exports\UsersNewExport;
use App\Exports\CredMonthlyExport;
use App\Exports\ChildPackageExport;
use App\Exports\ProfessionalsExport;
use App\Exports\SisCovidExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;

class FedController extends Controller
{
    public function index(Request $request) {
        return view('fed/kids/Premature/index');
    }

    public function listPremature(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL ALCIDES CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes) ->where('BAJO_PESO_PREMATURO', 'SI')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_PREMATURO')
                    ->select('DEN_PREMATURO.NOMBRE_PROV','DEN_PREMATURO.NOMBRE_DIST','DEN_PREMATURO.DENOMINADOR', 'NUM_PREMATURO.NUMERADOR')
                    // (DB::raw('round((cast(NUM_PREMATURO.NUMERADOR as float) / cast(DEN_PREMATURO.DENOMINADOR as float) * 100), 1) AS AVANCE')))
                    ->leftJoin('NUM_PREMATURO', 'DEN_PREMATURO.NOMBRE_DIST', '=', 'NUM_PREMATURO.NOMBRE_DIST')
                    ->where('DEN_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('NUM_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('DEN_PREMATURO.DENOMINADOR', '>', '0') ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                    ->get();

            $resum_red = DB::table('DEN_PREMATURO')
                    ->select('DEN_PREMATURO.NOMBRE_PROV', (DB::raw('SUM(DEN_PREMATURO.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_PREMATURO.NUMERADOR) AS NUM')))
                    ->leftJoin('NUM_PREMATURO', 'DEN_PREMATURO.NOMBRE_DIST', '=', 'NUM_PREMATURO.NOMBRE_DIST')
                    ->where('DEN_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('NUM_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('DEN_PREMATURO.DENOMINADOR', '>', '0') ->groupBy('DEN_PREMATURO.NOMBRE_PROV') ->orderBy('NOMBRE_PROV', 'ASC')
                    ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('BAJO_PESO_PREMATURO', 'SI') ->where('NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_PREMATURO')
                    ->select('DEN_PREMATURO.NOMBRE_PROV','DEN_PREMATURO.NOMBRE_DIST','DEN_PREMATURO.DENOMINADOR', 'NUM_PREMATURO.NUMERADOR')
                    // (DB::raw('round((cast(NUM_PREMATURO.NUMERADOR as float) / cast(DEN_PREMATURO.DENOMINADOR as float) * 100), 1) AS AVANCE')))
                    ->leftJoin('NUM_PREMATURO', 'DEN_PREMATURO.NOMBRE_DIST', '=', 'NUM_PREMATURO.NOMBRE_DIST')
                    ->where('DEN_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('NUM_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('DEN_PREMATURO.DENOMINADOR', '>', '0') ->where('DEN_PREMATURO.NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                    ->get();

            $resum_red = DB::table('DEN_PREMATURO')
                    ->select('DEN_PREMATURO.NOMBRE_PROV', (DB::raw('SUM(DEN_PREMATURO.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_PREMATURO.NUMERADOR) AS NUM')))
                    ->leftJoin('NUM_PREMATURO', 'DEN_PREMATURO.NOMBRE_DIST', '=', 'NUM_PREMATURO.NOMBRE_DIST')
                    ->where('DEN_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('NUM_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('DEN_PREMATURO.DENOMINADOR', '>', '0') ->where('DEN_PREMATURO.NOMBRE_PROV', $red)
                    ->groupBy('DEN_PREMATURO.NOMBRE_PROV') ->orderBy('DEN_PREMATURO.NOMBRE_PROV', 'ASC')
                    ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('BAJO_PESO_PREMATURO', 'SI') ->where('NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_PREMATURO')
                    ->select('DEN_PREMATURO.NOMBRE_PROV','DEN_PREMATURO.NOMBRE_DIST','DEN_PREMATURO.DENOMINADOR', 'NUM_PREMATURO.NUMERADOR')
                    // (DB::raw('round((cast(NUM_PREMATURO.NUMERADOR as float) / cast(DEN_PREMATURO.DENOMINADOR as float) * 100), 1) AS AVANCE')))
                    ->leftJoin('NUM_PREMATURO', 'DEN_PREMATURO.NOMBRE_DIST', '=', 'NUM_PREMATURO.NOMBRE_DIST')
                    ->where('DEN_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('NUM_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('DEN_PREMATURO.DENOMINADOR', '>', '0') ->where('DEN_PREMATURO.NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                    ->get();

            $resum_red = DB::table('DEN_PREMATURO')
                    ->select('DEN_PREMATURO.NOMBRE_PROV', (DB::raw('SUM(DEN_PREMATURO.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_PREMATURO.NUMERADOR) AS NUM')))
                    ->leftJoin('NUM_PREMATURO', 'DEN_PREMATURO.NOMBRE_DIST', '=', 'NUM_PREMATURO.NOMBRE_DIST')
                    ->where('DEN_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('NUM_PREMATURO.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_PREMATURO.CORTE_PADRON', $anio.''.$mes2)
                    ->where('DEN_PREMATURO.DENOMINADOR', '>', '0') ->where('DEN_PREMATURO.NOMBRE_DIST', $dist)
                    ->groupBy('DEN_PREMATURO.NOMBRE_PROV') ->orderBy('DEN_PREMATURO.NOMBRE_PROV', 'ASC')
                    ->get();
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printPremature(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nom = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes) ->where('BAJO_PESO_PREMATURO', 'SI')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nom = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('BAJO_PESO_PREMATURO', 'SI') ->where('NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nom = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('BAJO_PESO_PREMATURO', 'SI') ->where('NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();
        }

        return Excel::download(new PrematureExport($nom, $anio, $request->nameMonth, $request->pn, $request->cnv), 'DEIT_PASCO CG_FT_PREMATUROS.xlsx');
    }

    public function indexTmz(Request $request) {
        return view('fed/kids/Tmz/index');
    }

    public function listTmzNeonatal(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_NEONATAL')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes) ->where('CUMPLE_28_DIAS', '>=',  $anio.'-'.$mes2.'-01')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_NEONATAL')
                        ->select('DEN_NEONATAL.NOMBRE_PROV','DEN_NEONATAL.NOMBRE_DIST','DEN_NEONATAL.DENOMINADOR', 'NUM_NEONATAL.NUMERADOR')
                        // (DB::raw('round((cast(NUM_NEONATAL.NUMERADOR as float) / cast(DEN_NEONATAL.DENOMINADOR as float) * 100), 1) AS AVANCE')))
                        ->leftJoin('NUM_NEONATAL', 'DEN_NEONATAL.NOMBRE_DIST', '=', 'NUM_NEONATAL.NOMBRE_DIST')
                        ->where('DEN_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('NUM_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('DEN_NEONATAL.DENOMINADOR', '>', '0')
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_NEONATAL')
                        ->select('DEN_NEONATAL.NOMBRE_PROV', (DB::raw('SUM(DEN_NEONATAL.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_NEONATAL.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_NEONATAL', 'DEN_NEONATAL.NOMBRE_DIST', '=', 'NUM_NEONATAL.NOMBRE_DIST')
                        ->where('DEN_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('NUM_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('DEN_NEONATAL.DENOMINADOR', '>', '0')
                        ->groupBy('DEN_NEONATAL.NOMBRE_PROV') ->orderBy('DEN_NEONATAL.NOMBRE_PROV', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_NEONATAL')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('CUMPLE_28_DIAS', '>=',  $anio.'-'.$mes2.'-01') ->where('NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_NEONATAL')
                        ->select('DEN_NEONATAL.NOMBRE_PROV','DEN_NEONATAL.NOMBRE_DIST','DEN_NEONATAL.DENOMINADOR', 'NUM_NEONATAL.NUMERADOR')
                        // (DB::raw('round((cast(NUM_NEONATAL.NUMERADOR as float) / cast(DEN_NEONATAL.DENOMINADOR as float) * 100), 1) AS AVANCE')))
                        ->leftJoin('NUM_NEONATAL', 'DEN_NEONATAL.NOMBRE_DIST', '=', 'NUM_NEONATAL.NOMBRE_DIST')
                        ->where('DEN_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('NUM_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('DEN_NEONATAL.DENOMINADOR', '>', '0') ->where('DEN_NEONATAL.NOMBRE_PROV', $red)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_NEONATAL')
                        ->select('DEN_NEONATAL.NOMBRE_PROV', (DB::raw('SUM(DEN_NEONATAL.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_NEONATAL.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_NEONATAL', 'DEN_NEONATAL.NOMBRE_DIST', '=', 'NUM_NEONATAL.NOMBRE_DIST')
                        ->where('DEN_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('NUM_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('DEN_NEONATAL.DENOMINADOR', '>', '0') ->where('DEN_NEONATAL.NOMBRE_PROV', $red)
                        ->groupBy('DEN_NEONATAL.NOMBRE_PROV') ->orderBy('DEN_NEONATAL.NOMBRE_PROV', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_NEONATAL')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('CUMPLE_28_DIAS', '>=',  $anio.'-'.$mes2.'-01') ->where('NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_NEONATAL')
                        ->select('DEN_NEONATAL.NOMBRE_PROV','DEN_NEONATAL.NOMBRE_DIST','DEN_NEONATAL.DENOMINADOR', 'NUM_NEONATAL.NUMERADOR')
                        ->leftJoin('NUM_NEONATAL', 'DEN_NEONATAL.NOMBRE_DIST', '=', 'NUM_NEONATAL.NOMBRE_DIST')
                        ->where('DEN_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('NUM_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('DEN_NEONATAL.DENOMINADOR', '>', '0') ->where('DEN_NEONATAL.NOMBRE_DIST', $dist)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_NEONATAL')
                        ->select('DEN_NEONATAL.NOMBRE_PROV', (DB::raw('SUM(DEN_NEONATAL.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_NEONATAL.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_NEONATAL', 'DEN_NEONATAL.NOMBRE_DIST', '=', 'NUM_NEONATAL.NOMBRE_DIST')
                        ->where('DEN_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('NUM_NEONATAL.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_NEONATAL.CORTE_PADRON', $anio.''.$mes2)
                        ->where('DEN_NEONATAL.DENOMINADOR', '>', '0') ->where('DEN_NEONATAL.NOMBRE_DIST', $dist)
                        ->groupBy('DEN_NEONATAL.NOMBRE_PROV') ->orderBy('DEN_NEONATAL.NOMBRE_PROV', 'ASC')
                        ->get();
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printTmz(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_NEONATAL')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes) ->where('CUMPLE_28_DIAS', '>=',  $anio.'-'.$mes2.'-01')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_NEONATAL')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('CUMPLE_28_DIAS', '>=',  $anio.'-'.$mes2.'-01') ->where('NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_NEONATAL')
                    ->where('CORTE_PADRON', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('CUMPLE_28_DIAS', '>=',  $anio.'-'.$mes2.'-01') ->where('NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();
        }

	    return Excel::download(new TmzNeonatalExport($nominal, $anio, $request->nameMonth, $request->pn, $request->his), 'DEIT_PASCO CG_FT_TMZ_NEONATAL.xlsx');
    }

    public function indexSuple(Request $request) {
        return view('fed/kids/FourthMonth/index');
    }

    public function listSuple(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_SUPLE')
                    ->where('PADRON_CORTE', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes) ->where('ANEMIA_O_PREMATURO', 'NO')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_4MESES')
                        ->select('DEN_4MESES.NOMBRE_PROV','DEN_4MESES.NOMBRE_DIST','DEN_4MESES.DENOMINADOR', 'NUM_4MESES.NUMERADOR')
                        ->leftJoin('NUM_4MESES', 'DEN_4MESES.NOMBRE_DIST', '=', 'NUM_4MESES.NOMBRE_DIST')
                        ->where('DEN_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('NUM_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('DEN_4MESES.DENOMINADOR', '>', '0')
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_4MESES')
                        ->select('DEN_4MESES.NOMBRE_PROV', (DB::raw('SUM(DEN_4MESES.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_4MESES.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_4MESES', 'DEN_4MESES.NOMBRE_DIST', '=', 'NUM_4MESES.NOMBRE_DIST')
                        ->where('DEN_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('NUM_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('DEN_4MESES.DENOMINADOR', '>', '0')
                        ->groupBy('DEN_4MESES.NOMBRE_PROV') ->orderBy('DEN_4MESES.NOMBRE_PROV', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_SUPLE')
                    ->where('PADRON_CORTE', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('ANEMIA_O_PREMATURO', 'NO') ->where('NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_4MESES')
                        ->select('DEN_4MESES.NOMBRE_PROV','DEN_4MESES.NOMBRE_DIST','DEN_4MESES.DENOMINADOR', 'NUM_4MESES.NUMERADOR')
                        ->leftJoin('NUM_4MESES', 'DEN_4MESES.NOMBRE_DIST', '=', 'NUM_4MESES.NOMBRE_DIST')
                        ->where('DEN_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('NUM_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('DEN_4MESES.DENOMINADOR', '>', '0') ->where('DEN_4MESES.NOMBRE_PROV', $red)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_4MESES')
                        ->select('DEN_4MESES.NOMBRE_PROV', (DB::raw('SUM(DEN_4MESES.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_4MESES.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_4MESES', 'DEN_4MESES.NOMBRE_DIST', '=', 'NUM_4MESES.NOMBRE_DIST')
                        ->where('DEN_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('NUM_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('DEN_4MESES.DENOMINADOR', '>', '0') ->where('DEN_4MESES.NOMBRE_PROV', $red)
                        ->groupBy('DEN_4MESES.NOMBRE_PROV') ->orderBy('DEN_4MESES.NOMBRE_PROV', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOL_SUPLE')
                    ->where('PADRON_CORTE', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('ANEMIA_O_PREMATURO', 'NO') ->where('NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_4MESES')
                        ->select('DEN_4MESES.NOMBRE_PROV','DEN_4MESES.NOMBRE_DIST','DEN_4MESES.DENOMINADOR', 'NUM_4MESES.NUMERADOR')
                        ->leftJoin('NUM_4MESES', 'DEN_4MESES.NOMBRE_DIST', '=', 'NUM_4MESES.NOMBRE_DIST')
                        ->where('DEN_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('NUM_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('DEN_4MESES.DENOMINADOR', '>', '0') ->where('DEN_4MESES.NOMBRE_DIST', $dist)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_4MESES')
                        ->select('DEN_4MESES.NOMBRE_PROV', (DB::raw('SUM(DEN_4MESES.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_4MESES.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_4MESES', 'DEN_4MESES.NOMBRE_DIST', '=', 'NUM_4MESES.NOMBRE_DIST')
                        ->where('DEN_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('DEN_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('NUM_4MESES.PERIODO_MEDICION', $anio.'-'. $mes) ->where('NUM_4MESES.PADRON_CORTE', $anio.''.$mes2)
                        ->where('DEN_4MESES.DENOMINADOR', '>', '0') ->where('DEN_4MESES.NOMBRE_DIST', $dist)
                        ->groupBy('DEN_4MESES.NOMBRE_PROV') ->orderBy('DEN_4MESES.NOMBRE_PROV', 'ASC')
                        ->get();
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printSuple(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_SUPLE')
                    ->where('PADRON_CORTE', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes) ->where('ANEMIA_O_PREMATURO', 'NO')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_SUPLE')
                    ->where('PADRON_CORTE', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('ANEMIA_O_PREMATURO', 'NO') ->where('DEN_4MESES.NOMBRE_PROV', $red)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOL_SUPLE')
                    ->where('PADRON_CORTE', $anio.''.$mes2) ->where('PERIODO_MEDICION', $anio.'-'. $mes)
                    ->where('ANEMIA_O_PREMATURO', 'NO') ->where('NOMBRE_DIST', $dist)
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();
        }

	    return Excel::download(new SuplementadosExport($nominal, $anio, $request->nameMonth, $request->pn, $request->his), 'DEIT_PASCO CG_FT_SUPLEMENTACION_NIÑOS_4_MESES.xlsx');
    }

    public function indexIniOport(Request $request) {
        return view('fed/kids/SixEightMonth/index');
    }

    public function listIniOportuno(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('BD_PADRON_NOMINAL')
                    ->statement("SELECT pn.NOMBRE_PROV, pn.NOMBRE_DIST, pn.MENOR_VISITADO, PN.MENOR_ENCONTRADO, pn.NUM_DNI, pn.NUM_CNV,
                    pn.FECHA_NACIMIENTO_NINO, 'DOCUMENTO' = CASE WHEN pn.NUM_DNI IS NOT NULL THEN pn.NUM_DNI ELSE pn.NUM_CNV END,
                    CONCAT(pn.APELLIDO_PATERNO_NINO,' ',pn.APELLIDO_MATERNO_NINO,' ', pn.NOMBRE_NINO) AS APELLIDOS_NOMBRES,
                    pn.TIPO_SEGURO, pn.NOMBRE_EESS AS ULTIMA_ATE_PN
                    into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6
                    from NOMINAL_PADRON_NOMINAL AS pn
                    where YEAR (DATEADD(DAY,269,FECHA_NACIMIENTO_NINO))='".$anio."' and month(DATEADD(DAY,269,FECHA_NACIMIENTO_NINO))='".$mes."'
                    and mes='".$anio."".$mes2."';
                    with c as ( select DOCUMENTO, nombre_dist, ROW_NUMBER() over(partition by DOCUMENTO order by DOCUMENTO) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6)
                    delete  from c
                    where duplicado >1;");

        $query1 = DB::connection('BDHIS_MINSA')
                    ->statement("SELECT A.Provincia_Establecimiento, A.Distrito_Establecimiento, A.Nombre_Establecimiento, A.Abrev_Tipo_Doc_Paciente, A.Numero_Documento_Paciente, A.Fecha_Nacimiento_Paciente,
                    Min(CASE WHEN (Codigo_Item ='85018' AND Tipo_Diagnostico='D' AND  EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' )THEN A.Fecha_Atencion ELSE NULL END)'85018',
                    Min(CASE WHEN (Codigo_Item IN ('D509','D500','D649','D508') AND Tipo_Diagnostico='D' AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' )THEN A.Fecha_Atencion ELSE NULL END)'D50X',
                    -- Min(CASE WHEN (Codigo_Item ='U310' AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01','1') AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' )THEN A.Fecha_Atencion ELSE
                    -- NULL END)'U310_SF1',
                    Min(CASE WHEN (Codigo_Item  IN('U310', 'Z298','99199.17','99199.19') AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01','1') AND EDAD_REG IN ('6','7','8') AND Tipo_Edad='M' )
                    THEN A.Fecha_Atencion ELSE NULL END)'SUPLE'
                    into BDHIS_MINSA_EXTERNO_V2.dbo.suple6
                    FROM T_CONSOLIDADO_NUEVA_TRAMA_HISMINSA AS A WHERE
                    ((a.fecha_atencion> CONVERT(DATE, DATEADD(dd, -170, CONCAT('".$anio."".$mes2."', DAY(DATEADD(DD,-1,DATEADD(MM,DATEDIFF(MM,-1,'01/".$mes2."/".$anio."'),0))))))) and
                    (a.fecha_atencion<= CONCAT('".$anio."-".$mes2."-', DAY(DATEADD(DD,-1,DATEADD(MM,DATEDIFF(MM,-1,'01/".$mes2."/".$anio."'),0)))))) AND
                    ( (Codigo_Item ='85018' AND Tipo_Diagnostico='D' AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' ) OR
                    (Codigo_Item IN ('D509','D500','D649','D508') AND Tipo_Diagnostico='D'  AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' ) OR
                    --(Codigo_Item IN('U310','99199.17') AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01') AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' ) or
                    (Codigo_Item  IN('U310', 'Z298','99199.17','99199.19') AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01','1') AND EDAD_REG IN ('6','7','8') AND Tipo_Edad='M' ) )
                    GROUP BY A.Provincia_Establecimiento, A.Distrito_Establecimiento, A.Nombre_Establecimiento,
                    A.Abrev_Tipo_Doc_Paciente, A.Numero_Documento_Paciente, A.Fecha_Nacimiento_Paciente
                    ORDER BY Numero_Documento_Paciente asc, A.Nombre_Establecimiento;

                    SELECT pn.NOMBRE_PROV PROVINCIA, pn.NOMBRE_DIST DISTRITO, pn.MENOR_VISITADO, PN.MENOR_ENCONTRADO, pn.NUM_DNI, pn.NUM_CNV,
                    pn.FECHA_NACIMIENTO_NINO,DOCUMENTO, s.Abrev_Tipo_Doc_Paciente AS TIPO_DOC, APELLIDOS_NOMBRES,
                    pn.TIPO_SEGURO, pn.ULTIMA_ATE_PN AS PN_ULTIMO_LUGAR, S.Nombre_Establecimiento AS ESTAB_ACTIVIDAD, s.[85018] HEMOGLOBINA, s.D50X, s.SUPLE,
                    DATEDIFF (DAY, s.[85018], s.SUPLE) AS DIA, DATEDIFF (DAY, s.[85018], s.D50X) AS DIA2,
                    CASE WHEN ((DATEDIFF (DAY, s.[85018], s.D50X) < 8) AND (DATEDIFF (DAY, s.[85018], s.SUPLE) < 8)) THEN 'CUMPLE'
                    WHEN ((DATEDIFF (DAY, s.[85018], s.SUPLE) < 8) AND (s.D50X IS NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END AS MIDE
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_6_8
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6 PN LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.suple6 AS s on pn.DOCUMENTO=s.Numero_Documento_Paciente
                    WHERE pn.TIPO_SEGURO != '2,' OR pn.TIPO_SEGURO IS NULL
                    order by NOMBRE_PROV,NOMBRE_DIST,DOCUMENTO

                    SELECT PROVINCIA, DISTRITO, COUNT(*) AS 'DENOMINADOR'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DEN_6_8MESES
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_6_8
                    GROUP BY PROVINCIA, DISTRITO

                    SELECT PROVINCIA,DISTRITO, COUNT(CASE WHEN MIDE='CUMPLE' THEN 'SI' END) AS 'NUMERADOR'
                    into BDHIS_MINSA_EXTERNO_V2.dbo.NUM_6_8MESES
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_6_8
                    GROUP BY PROVINCIA, DISTRITO");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_6_8')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_6_8MESES')
                        ->select('DEN_6_8MESES.PROVINCIA','DEN_6_8MESES.DISTRITO','DEN_6_8MESES.DENOMINADOR', 'NUM_6_8MESES.NUMERADOR')
                        ->leftJoin('NUM_6_8MESES', 'DEN_6_8MESES.DISTRITO', '=', 'NUM_6_8MESES.DISTRITO')
                        ->where('DEN_6_8MESES.DENOMINADOR', '>', '0')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_6_8MESES')
                        ->select('DEN_6_8MESES.PROVINCIA', (DB::raw('SUM(DEN_6_8MESES.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_6_8MESES.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_6_8MESES', 'DEN_6_8MESES.DISTRITO', '=', 'NUM_6_8MESES.DISTRITO')
                        ->where('DEN_6_8MESES.DENOMINADOR', '>', '0')
                        ->groupBy('DEN_6_8MESES.PROVINCIA') ->orderBy('DEN_6_8MESES.PROVINCIA', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_6_8') ->where('DEN_6_8MESES.PROVINCIA', $red)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_6_8MESES')
                        ->select('DEN_6_8MESES.PROVINCIA','DEN_6_8MESES.DISTRITO','DEN_6_8MESES.DENOMINADOR', 'NUM_6_8MESES.NUMERADOR')
                        ->leftJoin('NUM_6_8MESES', 'DEN_6_8MESES.DISTRITO', '=', 'NUM_6_8MESES.DISTRITO')
                        ->where('DEN_6_8MESES.DENOMINADOR', '>', '0') ->where('DEN_6_8MESES.PROVINCIA', $red)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_6_8MESES')
                        ->select('DEN_6_8MESES.PROVINCIA', (DB::raw('SUM(DEN_6_8MESES.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_6_8MESES.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_6_8MESES', 'DEN_6_8MESES.DISTRITO', '=', 'NUM_6_8MESES.DISTRITO')
                        ->where('DEN_6_8MESES.DENOMINADOR', '>', '0') ->where('DEN_6_8MESES.PROVINCIA', $red)
                        ->groupBy('DEN_6_8MESES.PROVINCIA') ->orderBy('DEN_6_8MESES.PROVINCIA', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOL_SUPLE') ->where('DISTRITO', $dist)
                    ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                    ->get();

            $t_resume = DB::table('DEN_6_8MESES')
                        ->select('DEN_6_8MESES.PROVINCIA','DEN_6_8MESES.DISTRITO','DEN_6_8MESES.DENOMINADOR', 'NUM_6_8MESES.NUMERADOR')
                        ->leftJoin('NUM_6_8MESES', 'DEN_6_8MESES.DISTRITO', '=', 'NUM_6_8MESES.DISTRITO')
                        ->where('DEN_6_8MESES.DENOMINADOR', '>', '0') ->where('DEN_6_8MESES.DISTRITO', $dist)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_6_8MESES')
                        ->select('DEN_6_8MESES.PROVINCIA', (DB::raw('SUM(DEN_6_8MESES.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_6_8MESES.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_6_8MESES', 'DEN_6_8MESES.DISTRITO', '=', 'NUM_6_8MESES.DISTRITO')
                        ->where('DEN_6_8MESES.DENOMINADOR', '>', '0') ->where('DEN_6_8MESES.DISTRITO', $dist)
                        ->groupBy('DEN_6_8MESES.PROVINCIA') ->orderBy('DEN_6_8MESES.PROVINCIA', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.suple6
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_6_8
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DEN_6_8MESES
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.NUM_6_8MESES"));

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printIniOportuno(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('BD_PADRON_NOMINAL')
                    ->statement("SELECT pn.NOMBRE_PROV, pn.NOMBRE_DIST, pn.MENOR_VISITADO, PN.MENOR_ENCONTRADO, pn.NUM_DNI, pn.NUM_CNV,
                    pn.FECHA_NACIMIENTO_NINO, 'DOCUMENTO' = CASE WHEN pn.NUM_DNI IS NOT NULL THEN pn.NUM_DNI ELSE pn.NUM_CNV END,
                    CONCAT(pn.APELLIDO_PATERNO_NINO,' ',pn.APELLIDO_MATERNO_NINO,' ', pn.NOMBRE_NINO) AS APELLIDOS_NOMBRES,
                    pn.TIPO_SEGURO, pn.NOMBRE_EESS AS ULTIMA_ATE_PN
                    into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6
                    from NOMINAL_PADRON_NOMINAL AS pn
                    where YEAR (DATEADD(DAY,269,FECHA_NACIMIENTO_NINO))='".$anio."' and month(DATEADD(DAY,269,FECHA_NACIMIENTO_NINO))='".$mes."'
                    and mes='".$anio."".$mes2."';
                    with c as ( select DOCUMENTO, nombre_dist, ROW_NUMBER() over(partition by DOCUMENTO order by DOCUMENTO) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6)
                    delete  from c
                    where duplicado >1;");

        $query1 = DB::connection('BDHIS_MINSA')
                    ->statement("SELECT A.Provincia_Establecimiento, A.Distrito_Establecimiento, A.Nombre_Establecimiento, A.Abrev_Tipo_Doc_Paciente, A.Numero_Documento_Paciente, A.Fecha_Nacimiento_Paciente,
                    Min(CASE WHEN (Codigo_Item ='85018' AND Tipo_Diagnostico='D' AND  EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' )THEN A.Fecha_Atencion ELSE NULL END)'85018',
                    Min(CASE WHEN (Codigo_Item IN ('D509','D500','D649','D508') AND Tipo_Diagnostico='D' AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' )THEN A.Fecha_Atencion ELSE NULL END)'D50X',
                    -- Min(CASE WHEN (Codigo_Item ='U310' AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01','1') AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' )THEN A.Fecha_Atencion ELSE
                    -- NULL END)'U310_SF1',
                    Min(CASE WHEN (Codigo_Item  IN('U310', 'Z298','99199.17','99199.19') AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01','1') AND EDAD_REG IN ('6','7','8') AND Tipo_Edad='M' )
                    THEN A.Fecha_Atencion ELSE NULL END)'SUPLE'
                    into BDHIS_MINSA_EXTERNO_V2.dbo.suple6
                    FROM T_CONSOLIDADO_NUEVA_TRAMA_HISMINSA AS A WHERE
                    ((a.fecha_atencion> CONVERT(DATE, DATEADD(dd, -170, CONCAT('".$anio."".$mes2."', DAY(DATEADD(DD,-1,DATEADD(MM,DATEDIFF(MM,-1,'01/".$mes2."/".$anio."'),0))))))) and
                    (a.fecha_atencion<= CONCAT('".$anio."-".$mes2."-', DAY(DATEADD(DD,-1,DATEADD(MM,DATEDIFF(MM,-1,'01/".$mes2."/".$anio."'),0)))))) AND
                    ( (Codigo_Item ='85018' AND Tipo_Diagnostico='D' AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' ) OR
                    (Codigo_Item IN ('D509','D500','D649','D508') AND Tipo_Diagnostico='D'  AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' ) OR
                    --(Codigo_Item IN('U310','99199.17') AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01') AND EDAD_REG IN ('5','6','7','8') AND Tipo_Edad='M' ) or
                    (Codigo_Item  IN('U310', 'Z298','99199.17','99199.19') AND Tipo_Diagnostico='D' AND VALOR_LAB IN ('SF1','PO1','P01','1') AND EDAD_REG IN ('6','7','8') AND Tipo_Edad='M' ) )
                    GROUP BY A.Provincia_Establecimiento, A.Distrito_Establecimiento, A.Nombre_Establecimiento,
                    A.Abrev_Tipo_Doc_Paciente, A.Numero_Documento_Paciente, A.Fecha_Nacimiento_Paciente
                    ORDER BY Numero_Documento_Paciente asc, A.Nombre_Establecimiento;

                    SELECT pn.NOMBRE_PROV PROVINCIA, pn.NOMBRE_DIST DISTRITO, pn.MENOR_VISITADO, PN.MENOR_ENCONTRADO, pn.NUM_DNI, pn.NUM_CNV,
                    pn.FECHA_NACIMIENTO_NINO,DOCUMENTO, s.Abrev_Tipo_Doc_Paciente AS TIPO_DOC, APELLIDOS_NOMBRES,
                    pn.TIPO_SEGURO, pn.ULTIMA_ATE_PN AS PN_ULTIMO_LUGAR, S.Nombre_Establecimiento AS ESTAB_ACTIVIDAD, s.[85018] HEMOGLOBINA, s.D50X, s.SUPLE,
                    DATEDIFF (DAY, s.[85018], s.SUPLE) AS DIA, DATEDIFF (DAY, s.[85018], s.D50X) AS DIA2,
                    CASE WHEN ((DATEDIFF (DAY, s.[85018], s.D50X) < 8) AND (DATEDIFF (DAY, s.[85018], s.SUPLE) < 8)) THEN 'CUMPLE'
                    WHEN ((DATEDIFF (DAY, s.[85018], s.SUPLE) < 8) AND (s.D50X IS NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END AS MIDE
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_6_8
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6 PN LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.suple6 AS s on pn.DOCUMENTO=s.Numero_Documento_Paciente
                    WHERE pn.TIPO_SEGURO != '2,' OR pn.TIPO_SEGURO IS NULL
                    order by NOMBRE_PROV,NOMBRE_DIST,DOCUMENTO");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_6_8')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_6_8') ->where('DEN_6_8MESES.PROVINCIA', $red)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOL_SUPLE') ->where('DISTRITO', $dist)
                    ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_ATENCION', 'ASC')
                    ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.suple6
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR6
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_6_8"));

        return Excel::download(new SixEightMonthExport($nominal, $anio, $request->nameMonth, $request->pn, $request->his), 'DEIT_PASCO CG_FT_INICIO_OPORTUNO.xlsx');
    }

    public function indexCredMes(Request $request) {
        return view('fed/kids/CredMonthly/index');
    }

    public function listCredMes(Request $request){
        $red_1 = $request->red; $dist = $request->distrito; $anio = $request->anio; $mes = $request->mes;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $anioVivo = date("Y");
        $mesVivo = date("n");

        if (strlen($mesVivo) == 1){ $mesVivo2 = '0'.$mesVivo; }
        else{ $mesVivo2 = $mesVivo; }

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        $consol = DB::connection('BD_PADRON_NOMINAL')
                    ->statement("SELECT NOMBRE_PROV, NOMBRE_DIST, NOMBRE_EESS_NACIMIENTO,NOMBRE_EESS, MENOR_VISITADO, MENOR_ENCONTRADO, TIPO_SEGURO, FECHA_NACIMIENTO_NINO,
                    'DOCUMENTO' = CASE WHEN pn.NUM_DNI IS NOT NULL THEN pn.NUM_DNI ELSE pn.NUM_CNV END,
                    CONCAT(pn.APELLIDO_PATERNO_NINO,' ',pn.APELLIDO_MATERNO_NINO,' ', pn.NOMBRE_NINO) APELLIDOS_NOMBRES
                    into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL FROM NOMINAL_PADRON_NOMINAL PN
                    WHERE cast(FECHA_NACIMIENTO_NINO as date)>='".$anio."-".$mes2."-01' AND MES='202204';
                    with c as ( select DOCUMENTO,  ROW_NUMBER() over(partition by DOCUMENTO order by DOCUMENTO) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL ) delete  from c where duplicado >1");

        $consol2 = DB::statement("SELECT NOMBRE_PROV,NOMBRE_DIST,MENOR_ENCONTRADO,APELLIDOS_NOMBRES,DOCUMENTO, TIPO_SEGURO, MONTH(FECHA_NACIMIENTO_NINO)MES_MEDIR,
                        FECHA_NACIMIENTO_NINO, PRIMER_CNTRL,SEG_CNTRL, TERCER_CNTRL,CUARTO_CNTRL,
                        CASE WHEN DATEDIFF(dd,primer_cntrl,SEG_CNTRL) BETWEEN 3 AND 7 THEN 'CUMPLE' END CUMPLE_CTRLMES,
                        PRIMER_CNTRL_MES,SEGUNDO_CNTRL_MES,TERCER_CNTRL_MES,CUARTO_CNTRL_MES,
                        QUINTO_CNTRL_MES, SEXTO_CNTRL_MES,SEPTIMO_CNTRL_MES, OCTAVO_CNTRL_MES, NOVENO_CNTRL_MES, DECIMO_CNTRL_MES,
                        ONCEAVO_CNTRL_MES, Convert(Integer, Datediff(Day, fecha_nacimiento_nino, Getdate())/30)EdadMeses
                        INTO BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED
                        FROM ( SELECT P.NOMBRE_PROV,P.NOMBRE_DIST,P.MENOR_ENCONTRADO,P.APELLIDOS_NOMBRES,P.DOCUMENTO,P.TIPO_SEGURO, P.FECHA_NACIMIENTO_NINO,
                            Max(CASE WHEN ((a.NUMEROFILA='1') )THEN A.Fecha_Atencion ELSE NULL END)'PRIMER_CNTRL',
                            Max(CASE WHEN ((a.NUMEROFILA='2') )THEN A.Fecha_Atencion ELSE NULL END)'SEG_CNTRL',
                            Max(CASE WHEN ((a1.NUMEROFILA='1') )THEN A1.Fecha_Atencion ELSE NULL END)'TERCER_CNTRL',
                            Max(CASE WHEN ((a1.NUMEROFILA='2') )THEN A1.Fecha_Atencion ELSE NULL END)'CUARTO_CNTRL',
                            Max(CASE WHEN ((C1.NUMEROFILA='1') )THEN C1.Fecha_Atencion ELSE NULL END)'PRIMER_CNTRL_MES',
                            Max(CASE WHEN ((C2.NUMEROFILA='1') )THEN C2.Fecha_Atencion ELSE NULL END)'SEGUNDO_CNTRL_MES',
                            Max(CASE WHEN ((C3.NUMEROFILA='1') )THEN C3.Fecha_Atencion ELSE NULL END)'TERCER_CNTRL_MES',
                            Max(CASE WHEN ((C4.NUMEROFILA='1') )THEN C4.Fecha_Atencion ELSE NULL END)'CUARTO_CNTRL_MES',
                            Max(CASE WHEN ((C5.NUMEROFILA='1') )THEN C5.Fecha_Atencion ELSE NULL END)'QUINTO_CNTRL_MES',
                            Max(CASE WHEN ((C6.NUMEROFILA='1') )THEN C6.Fecha_Atencion ELSE NULL END)'SEXTO_CNTRL_MES',
                            Max(CASE WHEN ((C7.NUMEROFILA='1') )THEN C7.Fecha_Atencion ELSE NULL END)'SEPTIMO_CNTRL_MES',
                            Max(CASE WHEN ((C8.NUMEROFILA='1') )THEN C8.Fecha_Atencion ELSE NULL END)'OCTAVO_CNTRL_MES',
                            Max(CASE WHEN ((C9.NUMEROFILA='1') )THEN C9.Fecha_Atencion ELSE NULL END)'NOVENO_CNTRL_MES',
                            Max(CASE WHEN ((C10.NUMEROFILA='1') )THEN C10.Fecha_Atencion ELSE NULL END)'DECIMO_CNTRL_MES',
                            Max(CASE WHEN ((C11.NUMEROFILA='1') )THEN C11.Fecha_Atencion ELSE NULL END)'ONCEAVO_CNTRL_MES'
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL P
                            LEFT JOIN  BDHIS_MINSA_EXTERNO_V2.dbo.cred_rn1_2m A on P.DOCUMENTO=A.numero_documento_paciente
                            left JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_Rn3_4m a1 ON P.DOCUMENTO=A1.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes1m C1 ON  P.DOCUMENTO=C1.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes2m C2 ON  P.DOCUMENTO=C2.numero_documento_paciente
                            LEFt JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes3m C3 ON P.DOCUMENTO=C3.numero_documento_paciente
                            LEFt JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes4m C4 ON  P.DOCUMENTO=C4.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes5m C5 ON  P.DOCUMENTO=C5.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes6m C6 ON  P.DOCUMENTO=C6.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes7m C7 ON  P.DOCUMENTO=C7.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes8m C8 ON  P.DOCUMENTO=C8.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes9m C9 ON  P.DOCUMENTO=C9.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes10m C10 ON  P.DOCUMENTO=C10.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes11m C11 ON  P.DOCUMENTO=C11.numero_documento_paciente
                                --WHERE (p.TIPO_SEGURO != '2,' OR p.TIPO_SEGURO IS NULL)
                        GROUP BY P.NOMBRE_PROV,P.NOMBRE_DIST,P.DOCUMENTO, P.TIPO_SEGURO,P.FECHA_NACIMIENTO_NINO,P.MENOR_ENCONTRADO,P.APELLIDOS_NOMBRES   )A
                            group by NOMBRE_PROV,NOMBRE_DIST,MENOR_ENCONTRADO,APELLIDOS_NOMBRES,DOCUMENTO,TIPO_SEGURO, FECHA_NACIMIENTO_NINO,
                            PRIMER_CNTRL,SEG_CNTRL,	TERCER_CNTRL,CUARTO_CNTRL,PRIMER_CNTRL_MES,SEGUNDO_CNTRL_MES,TERCER_CNTRL_MES,
                            CUARTO_CNTRL_MES,QUINTO_CNTRL_MES, SEXTO_CNTRL_MES,SEPTIMO_CNTRL_MES, OCTAVO_CNTRL_MES,
                        NOVENO_CNTRL_MES, DECIMO_CNTRL_MES,	ONCEAVO_CNTRL_MES");

        $consol3 = DB::statement("SELECT *,
                        DATEDIFF(DAY,FECHA_NACIMIENTO_NINO,PRIMER_CNTRL) DIA1, DATEDIFF(DAY,FECHA_NACIMIENTO_NINO,SEG_CNTRL) DIA2,
                        'CUMPLE' = CASE
                        WHEN EDADMESES='0' AND  cumple_ctrlmes is not null  THEN 'CUMPLE'
                        WHEN EDADMESES='11' AND  onceavo_cntrl_mes is not null  THEN 'CUMPLE'
                        WHEN EDADMESES='1' AND  cumple_ctrlmes is not null and primer_cntrl_mes is not null THEN 'CUMPLE'
                        WHEN EDADMESES='2' AND  cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null THEN 'CUMPLE'
                        WHEN EDADMESES='4' AND  cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null and cuarto_cntrl_mes is not null THEN 'CUMPLE'
                        WHEN EDADMESES='6' AND  cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null and cuarto_cntrl_mes is not null and sexto_cntrl_mes is not null
                        THEN 'CUMPLE'
                        WHEN EDADMESES='9' AND  cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null and cuarto_cntrl_mes is not null and sexto_cntrl_mes is not null
                        and noveno_cntrl_mes is not null
                        THEN 'CUMPLE' ELSE 'NOCUMPLE' END
                        INTO BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED2
                        FROM BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED");

        $consol4 = DB::statement("SELECT NOMBRE_PROV, NOMBRE_DIST, COUNT(*) 'DENOMINADOR'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DEN_CREDMENSUAL
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_CRED2
                    GROUP BY NOMBRE_PROV, NOMBRE_DIST;
                    SELECT NOMBRE_PROV, NOMBRE_DIST, COUNT( CASE WHEN (CUMPLE='CUMPLE') THEN 'SI' END) 'NUMERADOR'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.NUM_CREDMENSUAL
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_CRED2
                    GROUP BY NOMBRE_PROV, NOMBRE_DIST");
        // ----
        if($red_1 == 'TODOS'){
            $nominal2 = DB::table('CONSOLIDADO_CRED2')
                        ->select('*')
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_CREDMENSUAL AS a')
                        ->select('a.NOMBRE_PROV','a.NOMBRE_DIST','a.DENOMINADOR', 'b.NUMERADOR')
                        ->leftJoin('NUM_CREDMENSUAL as b', 'a.NOMBRE_DIST', '=', 'b.NOMBRE_DIST')
                        ->where('a.DENOMINADOR', '>', '0')
                        ->orderBy('a.NOMBRE_PROV', 'ASC') ->orderBy('a.NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_CREDMENSUAL AS a')
                        ->select('a.NOMBRE_PROV', (DB::raw('SUM(a.DENOMINADOR) AS DEN')), (DB::raw('SUM(b.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_CREDMENSUAL as b', 'a.NOMBRE_DIST', '=', 'b.NOMBRE_DIST')
                        ->where('a.DENOMINADOR', '>', '0')
                        ->groupBy('a.NOMBRE_PROV')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal2 = DB::table('CONSOLIDADO_CRED2')
                        ->select('*') ->where('NOMBRE_PROV', $red)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_CREDMENSUAL AS a')
                        ->select('a.NOMBRE_PROV','a.NOMBRE_DIST','a.DENOMINADOR', 'b.NUMERADOR')
                        ->leftJoin('NUM_CREDMENSUAL as b', 'a.NOMBRE_DIST', '=', 'b.NOMBRE_DIST')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.NOMBRE_PROV', $red)
                        ->orderBy('a.NOMBRE_PROV', 'ASC') ->orderBy('a.NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_CREDMENSUAL AS a')
                        ->select('a.NOMBRE_PROV', (DB::raw('SUM(a.DENOMINADOR) AS DEN')), (DB::raw('SUM(b.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_CREDMENSUAL as b', 'a.NOMBRE_DIST', '=', 'b.NOMBRE_DIST')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.NOMBRE_PROV', $red)
                        ->groupBy('a.NOMBRE_PROV')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::table('CONSOLIDADO_CRED2')
                        ->select('*') ->where('NOMBRE_DIST', $dist)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_CREDMENSUAL AS a')
                        ->select('a.NOMBRE_PROV','a.NOMBRE_DIST','a.DENOMINADOR', 'b.NUMERADOR')
                        ->leftJoin('NUM_CREDMENSUAL as b', 'a.NOMBRE_DIST', '=', 'b.NOMBRE_DIST')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.NOMBRE_DIST', $dist)
                        ->orderBy('a.NOMBRE_PROV', 'ASC') ->orderBy('a.NOMBRE_DIST', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_CREDMENSUAL AS a')
                        ->select('a.NOMBRE_PROV', (DB::raw('SUM(a.DENOMINADOR) AS DEN')), (DB::raw('SUM(b.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_CREDMENSUAL as b', 'a.NOMBRE_DIST', '=', 'b.NOMBRE_DIST')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.NOMBRE_DIST', $dist)
                        ->groupBy('a.NOMBRE_PROV')
                        ->get();
        }

        $query1 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED2
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.NUM_CREDMENSUAL
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DEN_CREDMENSUAL"));

        $q[] = json_decode($nominal2, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printCredMes(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $anioVivo = date("Y");
        $mesVivo = date("n");

        if (strlen($mesVivo) == 1){ $mesVivo2 = '0'.$mesVivo; }
        else{ $mesVivo2 = $mesVivo; }

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        $consol = DB::connection('BD_PADRON_NOMINAL')
                    ->statement("SELECT NOMBRE_PROV, NOMBRE_DIST, NOMBRE_EESS_NACIMIENTO,NOMBRE_EESS, MENOR_VISITADO, MENOR_ENCONTRADO, TIPO_SEGURO, FECHA_NACIMIENTO_NINO,
                    'DOCUMENTO' = CASE WHEN pn.NUM_DNI IS NOT NULL THEN pn.NUM_DNI ELSE pn.NUM_CNV END,
                    CONCAT(pn.APELLIDO_PATERNO_NINO,' ',pn.APELLIDO_MATERNO_NINO,' ', pn.NOMBRE_NINO) APELLIDOS_NOMBRES
                    into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL FROM NOMINAL_PADRON_NOMINAL PN
                    WHERE cast(FECHA_NACIMIENTO_NINO as date)>='".$anio."-".$mes2."-01' AND MES='202204';
                    with c as ( select DOCUMENTO,  ROW_NUMBER() over(partition by DOCUMENTO order by DOCUMENTO) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL ) delete  from c where duplicado >1");

        $consol2 = DB::statement("SELECT NOMBRE_PROV,NOMBRE_DIST,MENOR_ENCONTRADO,APELLIDOS_NOMBRES,DOCUMENTO, TIPO_SEGURO, MONTH(FECHA_NACIMIENTO_NINO)MES_MEDIR,
                    FECHA_NACIMIENTO_NINO, PRIMER_CNTRL,SEG_CNTRL, TERCER_CNTRL,CUARTO_CNTRL,
                    CASE WHEN DATEDIFF(dd,primer_cntrl,SEG_CNTRL) BETWEEN 3 AND 7 THEN 'CUMPLE' END CUMPLE_CTRLMES,
                    PRIMER_CNTRL_MES,SEGUNDO_CNTRL_MES,TERCER_CNTRL_MES,CUARTO_CNTRL_MES,
                    QUINTO_CNTRL_MES, SEXTO_CNTRL_MES,SEPTIMO_CNTRL_MES, OCTAVO_CNTRL_MES, NOVENO_CNTRL_MES, DECIMO_CNTRL_MES,
                    ONCEAVO_CNTRL_MES, Convert(Integer, Datediff(Day, fecha_nacimiento_nino, Getdate())/30)EdadMeses
                    INTO BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED
                        FROM ( SELECT P.NOMBRE_PROV,P.NOMBRE_DIST,P.MENOR_ENCONTRADO,P.APELLIDOS_NOMBRES,P.DOCUMENTO,P.TIPO_SEGURO, P.FECHA_NACIMIENTO_NINO,
                            Max(CASE WHEN ((a.NUMEROFILA='1') )THEN A.Fecha_Atencion ELSE NULL END)'PRIMER_CNTRL',
                            Max(CASE WHEN ((a.NUMEROFILA='2') )THEN A.Fecha_Atencion ELSE NULL END)'SEG_CNTRL',
                            Max(CASE WHEN ((a1.NUMEROFILA='1') )THEN A1.Fecha_Atencion ELSE NULL END)'TERCER_CNTRL',
                            Max(CASE WHEN ((a1.NUMEROFILA='2') )THEN A1.Fecha_Atencion ELSE NULL END)'CUARTO_CNTRL',
                            Max(CASE WHEN ((C1.NUMEROFILA='1') )THEN C1.Fecha_Atencion ELSE NULL END)'PRIMER_CNTRL_MES',
                            Max(CASE WHEN ((C2.NUMEROFILA='1') )THEN C2.Fecha_Atencion ELSE NULL END)'SEGUNDO_CNTRL_MES',
                            Max(CASE WHEN ((C3.NUMEROFILA='1') )THEN C3.Fecha_Atencion ELSE NULL END)'TERCER_CNTRL_MES',
                            Max(CASE WHEN ((C4.NUMEROFILA='1') )THEN C4.Fecha_Atencion ELSE NULL END)'CUARTO_CNTRL_MES',
                            Max(CASE WHEN ((C5.NUMEROFILA='1') )THEN C5.Fecha_Atencion ELSE NULL END)'QUINTO_CNTRL_MES',
                            Max(CASE WHEN ((C6.NUMEROFILA='1') )THEN C6.Fecha_Atencion ELSE NULL END)'SEXTO_CNTRL_MES',
                            Max(CASE WHEN ((C7.NUMEROFILA='1') )THEN C7.Fecha_Atencion ELSE NULL END)'SEPTIMO_CNTRL_MES',
                            Max(CASE WHEN ((C8.NUMEROFILA='1') )THEN C8.Fecha_Atencion ELSE NULL END)'OCTAVO_CNTRL_MES',
                            Max(CASE WHEN ((C9.NUMEROFILA='1') )THEN C9.Fecha_Atencion ELSE NULL END)'NOVENO_CNTRL_MES',
                            Max(CASE WHEN ((C10.NUMEROFILA='1') )THEN C10.Fecha_Atencion ELSE NULL END)'DECIMO_CNTRL_MES',
                            Max(CASE WHEN ((C11.NUMEROFILA='1') )THEN C11.Fecha_Atencion ELSE NULL END)'ONCEAVO_CNTRL_MES'
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL P
                            LEFT JOIN  BDHIS_MINSA_EXTERNO_V2.dbo.cred_rn1_2m A on P.DOCUMENTO=A.numero_documento_paciente
                            left JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_Rn3_4m a1 ON P.DOCUMENTO=A1.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes1m C1 ON  P.DOCUMENTO=C1.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes2m C2 ON  P.DOCUMENTO=C2.numero_documento_paciente
                            LEFt JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes3m C3 ON P.DOCUMENTO=C3.numero_documento_paciente
                            LEFt JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes4m C4 ON  P.DOCUMENTO=C4.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes5m C5 ON  P.DOCUMENTO=C5.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes6m C6 ON  P.DOCUMENTO=C6.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes7m C7 ON  P.DOCUMENTO=C7.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes8m C8 ON  P.DOCUMENTO=C8.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes9m C9 ON  P.DOCUMENTO=C9.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes10m C10 ON  P.DOCUMENTO=C10.numero_documento_paciente
                            LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.cred_mes11m C11 ON  P.DOCUMENTO=C11.numero_documento_paciente
                                --WHERE (p.TIPO_SEGURO != '2,' OR p.TIPO_SEGURO IS NULL)
                        GROUP BY P.NOMBRE_PROV,P.NOMBRE_DIST,P.DOCUMENTO, P.TIPO_SEGURO,P.FECHA_NACIMIENTO_NINO,P.MENOR_ENCONTRADO,P.APELLIDOS_NOMBRES   )A
                            group by NOMBRE_PROV,NOMBRE_DIST,MENOR_ENCONTRADO,APELLIDOS_NOMBRES,DOCUMENTO,TIPO_SEGURO, FECHA_NACIMIENTO_NINO,
                            PRIMER_CNTRL,SEG_CNTRL,	TERCER_CNTRL,CUARTO_CNTRL,PRIMER_CNTRL_MES,SEGUNDO_CNTRL_MES,TERCER_CNTRL_MES,
                            CUARTO_CNTRL_MES,QUINTO_CNTRL_MES, SEXTO_CNTRL_MES,SEPTIMO_CNTRL_MES, OCTAVO_CNTRL_MES,
                        NOVENO_CNTRL_MES, DECIMO_CNTRL_MES,	ONCEAVO_CNTRL_MES");

        $consol3 = DB::statement("SELECT *,
                        DATEDIFF(DAY,FECHA_NACIMIENTO_NINO,PRIMER_CNTRL) DIA1, DATEDIFF(DAY,FECHA_NACIMIENTO_NINO,SEG_CNTRL) DIA2, 'CUMPLE' = CASE
                        WHEN EDADMESES='0' AND cumple_ctrlmes is not null  THEN 'CUMPLE'
                        WHEN EDADMESES='11' AND onceavo_cntrl_mes is not null  THEN 'CUMPLE'
                        WHEN EDADMESES='1' AND cumple_ctrlmes is not null and primer_cntrl_mes is not null THEN 'CUMPLE'
                        WHEN EDADMESES='2' AND cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null THEN 'CUMPLE'
                        WHEN EDADMESES='4' AND cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null and cuarto_cntrl_mes is not null THEN 'CUMPLE'
                        WHEN EDADMESES='6' AND cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null and cuarto_cntrl_mes is not null and sexto_cntrl_mes is not null
                        THEN 'CUMPLE'
                        WHEN EDADMESES='9' AND  cumple_ctrlmes is not null and primer_cntrl_mes is not null and segundo_cntrl_mes is not null and cuarto_cntrl_mes is not null and sexto_cntrl_mes is not null
                        and noveno_cntrl_mes is not null
                        THEN 'CUMPLE' ELSE 'NOCUMPLE' END
                        INTO BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED2
                        FROM BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED");
        // -----
        if($red_1 == 'TODOS'){
            $nominal = DB::table('CONSOLIDADO_CRED2')
                        ->select('*')
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('CONSOLIDADO_CRED2')
                        ->select('*') ->where('NOMBRE_PROV', $red)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::table('CONSOLIDADO_CRED2')
                        ->select('*') ->where('NOMBRE_DIST', $dist)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();
        }

        $query1 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_EVALUAR_CREDMESUAL
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.DBO.CONSOLIDADO_CRED2"));

        return Excel::download(new CredMonthlyExport($nominal, $anio, $request->nameMonth, $request->his, $request->pn), 'DEIT_PASCO CRED_MENSUAL.xlsx');
    }

    public function indexChildPackage(Request $request) {
        return view('fed/kids/childPackage/index');
    }

    public function printchildPackage(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m; $type = $request->type;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL ALCIDES CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $anioVivo = date("Y");
        $mesVivo = date("n");

        if (strlen($mesVivo) == 1){ $mesVivo2 = '0'.$mesVivo; }
        else{ $mesVivo2 = $mesVivo; }

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        echo $red_1, '-', $dist, '-', $anio, '-', $mes, '-', $type;

        if($type == "indicator"){
            $query = DB::connection('BD_PADRON_NOMINAL')
                        ->statement("SELECT pn.NOMBRE_PROV, pn.NOMBRE_DIST,pn.NOMBRE_EESS,pn.MENOR_VISITADO,PN.MENOR_ENCONTRADO,pn.NUM_DNI,pn.NUM_CNV,
                        pn.FECHA_NACIMIENTO_NINO, 'DOCUMENTO' = CASE WHEN pn.NUM_DNI IS NOT NULL THEN pn.NUM_DNI ELSE pn.NUM_CNV END,
                        CONCAT(pn.APELLIDO_PATERNO_NINO,' ',pn.APELLIDO_MATERNO_NINO,' ', pn.NOMBRE_NINO) APELLIDOS_NOMBRES, pn.TIPO_SEGURO
                        INTO PAQUETE.dbo.PAQUETE_NINIO_PN
                        from NOMINAL_PADRON_NOMINAL pn
                        where YEAR  (DATEADD(DAY,364,FECHA_NACIMIENTO_NINO))='$anio' and month(DATEADD(DAY,364,FECHA_NACIMIENTO_NINO))='$mes'
                        and mes='$anio$mes2';
                        with c as ( select DOCUMENTO,  ROW_NUMBER()
                            over(partition by DOCUMENTO order by DOCUMENTO) as duplicado from PAQUETE.dbo.PAQUETE_NINIO_PN )
                        delete  from c
                        where duplicado >1;");
        }else{
            $query = DB::connection('BD_PADRON_NOMINAL')
                        ->statement("SELECT pn.NOMBRE_PROV, pn.NOMBRE_DIST,pn.NOMBRE_EESS,pn.MENOR_VISITADO,PN.MENOR_ENCONTRADO,pn.NUM_DNI,pn.NUM_CNV,
                        pn.FECHA_NACIMIENTO_NINO, 'DOCUMENTO' = CASE WHEN pn.NUM_DNI IS NOT NULL THEN pn.NUM_DNI ELSE pn.NUM_CNV END,
                        CONCAT(pn.APELLIDO_PATERNO_NINO,' ',pn.APELLIDO_MATERNO_NINO,' ', pn.NOMBRE_NINO) APELLIDOS_NOMBRES, pn.TIPO_SEGURO
                        INTO PAQUETE.dbo.PAQUETE_NINIO_PN
                        from NOMINAL_PADRON_NOMINAL pn
                        WHERE cast(FECHA_NACIMIENTO_NINO as date)>='$anio-$mes2-01' AND MES='202206';
                        with c as ( select DOCUMENTO,  ROW_NUMBER()
                            over(partition by DOCUMENTO order by DOCUMENTO) as duplicado from PAQUETE.dbo.PAQUETE_NINIO_PN )
                        delete  from c
                        where duplicado >1;");
        }

        $query1 = DB::statement("SELECT p.NOMBRE_PROV,p.NOMBRE_DIST,p.FECHA_NACIMIENTO_NINO,p.DOCUMENTO,p.TIPO_SEGURO, P.APELLIDOS_NOMBRES,
                        Max(CASE WHEN ((crn1.NUMEROFILA='1') )THEN crn1.Fecha_Atencion ELSE NULL END)'PRIMER_CNTRL',
                        Max(CASE WHEN ((crn1.NUMEROFILA='2') )THEN crn1.Fecha_Atencion ELSE NULL END)'SEG_CNTRL',
                        Max(CASE WHEN ((crn2.NUMEROFILA='1') )THEN crn1.Fecha_Atencion ELSE NULL END)'TER_CNTRL',
                        Max(CASE WHEN ((crn2.NUMEROFILA='2') )THEN crn1.Fecha_Atencion ELSE NULL END)'CUAR_CNTRL',
                        C1.Fecha_Atencion CRED1,C2.Fecha_Atencion CRED2,C3.Fecha_Atencion CRED3,C4.Fecha_Atencion CRED4,C5.Fecha_Atencion CRED5,
                        C6.Fecha_Atencion CRED6,C7.Fecha_Atencion CRED7,C8.Fecha_Atencion CRED8,C9.Fecha_Atencion CRED9,
                        cred_mesdiez.Fecha_Atencion CRED10,C11.Fecha_Atencion CRED11,S4.Fecha_Atencion SUPLE4,
                        S5.Fecha_Atencion SUPLE5,TMZ.Fecha_Atencion TAMIZAJE,ANEMIA.FECHA_ATENCION DXANEMIA,
                        S6.Fecha_Atencion SUPLE6,S7.Fecha_Atencion SUPLE7,S8.Fecha_Atencion SUPLE8,
                        S9.FECHA_ATENCION SUPLE9,suplediez.Fecha_Atencion SUPLE10,S11.FECHA_ATENCION SUPLE11
                        INTO PAQUETE.DBO.NINO_CRED_SUPLE
                        from PAQUETE.dbo.PAQUETE_NINIO_PN p
                            LEFT JOIN PAQUETE.dbo.cred_rn1_2 CRN1 ON p.DOCUMENTO=CRN1.NUMERO_DOCUMENTO_PACIENTE
                            left join PAQUETE.dbo.cred_rn3_4 CRN2 ON p.DOCUMENTO=CRN2.NUMERO_DOCUMENTO_PACIENTE
                            LEFT JOIN PAQUETE.dbo.cred_mes1 C1 on p.DOCUMENTO=c1.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes2 C2 on p.DOCUMENTO=c2.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes3 C3 on p.DOCUMENTO=C3.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes4 C4 on p.DOCUMENTO=c4.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes5 C5 on p.DOCUMENTO=c5.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes6 C6 on p.DOCUMENTO=C6.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes7 C7 on p.DOCUMENTO=c7.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes8 C8 on p.DOCUMENTO=c8.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes9 C9 on p.DOCUMENTO=c9.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.DBO.cred_mesdiez cred_mesdiez on p.DOCUMENTO=cred_mesdiez.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.cred_mes11 C11 on p.DOCUMENTO=c11.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple4 S4 on p.DOCUMENTO=S4.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple5 S5 on p.DOCUMENTO=S5.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple6 S6 on p.DOCUMENTO=S6.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple7 S7 on p.DOCUMENTO=S7.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple8 S8 on p.DOCUMENTO=S8.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple9 S9 on p.DOCUMENTO=S9.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suplediez suplediez on p.DOCUMENTO=Suplediez.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.suple11 S11 on p.DOCUMENTO=S11.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.tamizaje TMZ on p.DOCUMENTO=TMZ.Numero_Documento_Paciente
                            left join PAQUETE.dbo.DXANEMIA68 anemia on p.DOCUMENTO=ANEMIA.Numero_Documento_Paciente
                        group by p.NOMBRE_PROV,p.NOMBRE_DIST,p.FECHA_NACIMIENTO_NINO,p.DOCUMENTO,p.TIPO_SEGURO,
                                    C1.Fecha_Atencion,C2.Fecha_Atencion,C3.Fecha_Atencion,C4.Fecha_Atencion,C5.Fecha_Atencion,
                                    C6.Fecha_Atencion,C7.Fecha_Atencion,C8.Fecha_Atencion,C9.Fecha_Atencion, cred_mesdiez.fecha_atencion,
                                    C11.Fecha_Atencion,S4.Fecha_Atencion, S5.Fecha_Atencion,S6.Fecha_Atencion,
                                    S7.Fecha_Atencion,S8.Fecha_Atencion,S9.FECHA_ATENCION,P.APELLIDOS_NOMBRES,
                        suplediez.fecha_atencion,S11.FECHA_ATENCION,TMZ.Fecha_Atencion,ANEMIA.FECHA_ATENCION");

        $query2 = DB::statement("SELECT P.NOMBRE_PROV,p.NOMBRE_DIST,p.FECHA_NACIMIENTO_NINO,p.DOCUMENTO,p.TIPO_SEGURO, P.APELLIDOS_NOMBRES, PR.PREMATURO,
                        P.PRIMER_CNTRL,P.SEG_CNTRL,P.TER_CNTRL,P.CUAR_CNTRL, (DATEDIFF(DAY,P.FECHA_NACIMIENTO_NINO,p.SEG_CNTRL))MAXDIAS,(DATEDIFF(DAY,P.PRIMER_CNTRL,p.SEG_CNTRL))	 DIFERENCIA,
                        P.CRED1,P.CRED2,A1.Fecha_Atencion NEUMOCOCICA1,B1.Fecha_Atencion ROTAVIRUS1,
                        C1.Fecha_Atencion ANTIPOLIO1,D1.Fecha_Atencion PENTAVALENTE1,
                        P.CRED3,P.CRED4, P.SUPLE4, A2.Fecha_Atencion NEUMOCOCICA2,B2.Fecha_Atencion ROTAVIRUS2,D2.Fecha_Atencion PENTAVALENTE2,
                        C2.Fecha_Atencion ANTIPOLIO2,P.CRED5,P.SUPLE5,P.CRED6, P.TAMIZAJE,P.DXANEMIA,P.SUPLE6, C3.Fecha_Atencion ANTIPOLIO3,
                        D3.Fecha_Atencion PENTAVALENTE3, P.CRED7, P.SUPLE7,P.CRED8, P.SUPLE8,P.CRED9,P.SUPLE9,P.CRED10, P.SUPLE10, P.CRED11, P.SUPLE11
                        INTO PAQUETE.DBO.PAQUETE_NINO_COMPLETO
                        FROM PAQUETE.DBO.NINO_CRED_SUPLE P
                            LEFT JOIN PAQUETE.dbo.v90670_1 A1 ON P.DOCUMENTO=A1.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90670_2 A2 ON P.DOCUMENTO=A2.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90681_1 B1 ON P.DOCUMENTO=B1.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90681_2 B2 ON P.DOCUMENTO=B2.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90712_13_1 C1 ON P.DOCUMENTO=C1.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90712_13_2 C2 ON P.DOCUMENTO=C2.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90712_13_3 C3 ON P.DOCUMENTO=C3.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90723_1 D1 ON P.DOCUMENTO=D1.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90723_2 D2 ON P.DOCUMENTO=D2.Numero_Documento_Paciente
                            LEFT JOIN PAQUETE.dbo.v90723_3 D3 ON P.DOCUMENTO=D3.Numero_Documento_Paciente
                        LEFT JOIN PAQUETE.dbo.PREMATUROS PR ON P.DOCUMENTO=PR.Numero_Documento_Paciente");

        $query3 = DB::statement("SELECT NOMBRE_PROV,NOMBRE_DIST,FECHA_NACIMIENTO_NINO,DOCUMENTO,TIPO_SEGURO, APELLIDOS_NOMBRES, PREMATURO,PRIMER_CNTRL,
                        SEG_CNTRL,TER_CNTRL,CUAR_CNTRL, (DATEDIFF(DAY,FECHA_NACIMIENTO_NINO,SEG_CNTRL))MAXDIAS,(DATEDIFF(DAY,PRIMER_CNTRL,SEG_CNTRL)) DIFERENCIA,
                        'CUMPLE' = CASE WHEN
                        MAXDIAS <'15' AND DIFERENCIA >='3' and cred1 is not null and cred2 is not null and cred4 is not null and cred6 is not null and cred9 is not null AND
                        NEUMOCOCICA1 IS NOT NULL AND NEUMOCOCICA2 IS NOT NULL AND ROTAVIRUS1 IS NOT NULL AND ROTAVIRUS2 IS NOT NULL AND ANTIPOLIO1 IS NOT NULL AND
                        ANTIPOLIO2 IS NOT NULL AND ANTIPOLIO3 IS NOT NULL AND PENTAVALENTE1 IS NOT NULL AND PENTAVALENTE2 IS NOT NULL AND PENTAVALENTE3 IS NOT NULL AND
                        SUPLE4 IS NOT NULL AND SUPLE5 IS NOT NULL AND SUPLE6 IS NOT NULL AND SUPLE7 IS NOT NULL AND SUPLE8 IS NOT NULL AND SUPLE10 IS NOT NULL AND suple11 IS NOT NULL
                        THEN 'SI' ELSE 'NO' END,
                        CRED1,CRED2,NEUMOCOCICA1,ROTAVIRUS1,ANTIPOLIO1,PENTAVALENTE1,CRED3,CRED4,SUPLE4, NEUMOCOCICA2,ROTAVIRUS2,PENTAVALENTE2,
                        ANTIPOLIO2,CRED5,SUPLE5,CRED6, TAMIZAJE,DXANEMIA,SUPLE6,ANTIPOLIO3,PENTAVALENTE3, CRED7,SUPLE7,CRED8, SUPLE8,CRED9,SUPLE9,CRED10,SUPLE10,CRED11,
                        SUPLE11 INTO PAQUETE.DBO.CONSOLIDADO_PAQUETE_NINIO_COMPLETO
                        FROM PAQUETE.DBO.PAQUETE_NINO_COMPLETO
                        ORDER BY NOMBRE_PROV,NOMBRE_DIST");

        // -----
        if($red_1 == 'TODOS'){
            $nominal = DB::connection('PAQUETE')
                        ->table('CONSOLIDADO_PAQUETE_NINIO_COMPLETO') ->select('*')
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::connection('PAQUETE') ->table('CONSOLIDADO_PAQUETE_NINIO_COMPLETO')
                        ->select('*') ->where('NOMBRE_PROV', $red)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::connection('PAQUETE') ->table('CONSOLIDADO_PAQUETE_NINIO_COMPLETO')
                        ->select('*') ->where('NOMBRE_DIST', $dist)
                        ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC')
                        ->get();
        }

        $query1 = DB::statement(DB::raw("DROP TABLE PAQUETE.dbo.PAQUETE_NINIO_PN
                                        DROP TABLE PAQUETE.DBO.NINO_CRED_SUPLE
                                        DROP TABLE PAQUETE.DBO.PAQUETE_NINO_COMPLETO
                                        DROP TABLE PAQUETE.DBO.CONSOLIDADO_PAQUETE_NINIO_COMPLETO"));

        return Excel::download(new ChildPackageExport($nominal, $anio, $request->nameMonth, $request->his, $request->pn), 'DEIT_PASCO PAQUETE_NIÑO.xlsx');
    }

    public function indexBateria(Request $request) {
        return view('fed/Pregnant/Bateria/index');
    }

    public function listBateria(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_BATERIA')
                        ->select('*', (DB::raw("CASE WHEN (TMZ_ANEMIA IS NULL OR SIFILIS IS NULL OR VIH IS NULL OR BACTERIURIA IS NULL) THEN 'NO' WHEN (TMZ_ANEMIA=SIFILIS AND TMZ_ANEMIA=VIH AND TMZ_ANEMIA=BACTERIURIA) THEN 'SI' ELSE 'NO' END 'MIDE'")))
                        ->where('ANIO', $anio) ->where('MES', $mes)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('IPRESS', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_BATERIA')
                        ->select('DEN_BATERIA.PROVINCIA','DEN_BATERIA.DISTRITO','DEN_BATERIA.DENOMINADOR', 'NUM_BATERIA.NUMERADOR')
                        ->leftJoin('NUM_BATERIA', 'DEN_BATERIA.DISTRITO', '=', 'NUM_BATERIA.DISTRITO')
                        ->where('DEN_BATERIA.DENOMINADOR', '>', '0')
                        ->where('DEN_BATERIA.Anio', $anio) ->where('NUM_BATERIA.Anio', $anio)
                        ->where('DEN_BATERIA.Mes', $mes) ->where('NUM_BATERIA.Mes', $mes)
                        ->orderBy('DEN_BATERIA.PROVINCIA', 'ASC') ->orderBy('DEN_BATERIA.DISTRITO', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_BATERIA')
                        ->select('DEN_BATERIA.PROVINCIA', (DB::raw('SUM(DEN_BATERIA.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_BATERIA.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_BATERIA', 'DEN_BATERIA.DISTRITO', '=', 'NUM_BATERIA.DISTRITO')
                        ->where('DEN_BATERIA.Anio', $anio) ->where('NUM_BATERIA.Anio', $anio)
                        ->where('DEN_BATERIA.Mes', $mes) ->where('NUM_BATERIA.Mes', $mes)
                        ->where('DEN_BATERIA.DENOMINADOR', '>', '0')
                        ->groupBy('DEN_BATERIA.PROVINCIA') ->orderBy('DEN_BATERIA.PROVINCIA', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_BATERIA')
                        ->select('*', (DB::raw("CASE WHEN (TMZ_ANEMIA IS NULL OR SIFILIS IS NULL OR VIH IS NULL OR BACTERIURIA IS NULL) THEN 'NO' WHEN (TMZ_ANEMIA=SIFILIS AND TMZ_ANEMIA=VIH AND TMZ_ANEMIA=BACTERIURIA) THEN 'SI' ELSE 'NO' END 'MIDE'")))
                        ->where('ANIO', $anio) ->where('MES', $mes) ->where('PROVINCIA', $red)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('IPRESS', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_BATERIA')
                        ->select('DEN_BATERIA.PROVINCIA','DEN_BATERIA.DISTRITO','DEN_BATERIA.DENOMINADOR', 'NUM_BATERIA.NUMERADOR')
                        ->leftJoin('NUM_BATERIA', 'DEN_BATERIA.DISTRITO', '=', 'NUM_BATERIA.DISTRITO')
                        ->where('DEN_BATERIA.DENOMINADOR', '>', '0')
                        ->where('DEN_BATERIA.Anio', $anio) ->where('NUM_BATERIA.Anio', $anio)
                        ->where('DEN_BATERIA.Mes', $mes) ->where('NUM_BATERIA.Mes', $mes) ->where('DEN_BATERIA.PROVINCIA', $red)
                        ->orderBy('DEN_BATERIA.PROVINCIA', 'ASC') ->orderBy('DEN_BATERIA.DISTRITO', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_BATERIA')
                        ->select('DEN_BATERIA.PROVINCIA', (DB::raw('SUM(DEN_BATERIA.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_BATERIA.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_BATERIA', 'DEN_BATERIA.DISTRITO', '=', 'NUM_BATERIA.DISTRITO')
                        ->where('DEN_BATERIA.Anio', $anio) ->where('NUM_BATERIA.Anio', $anio)
                        ->where('DEN_BATERIA.Mes', $mes) ->where('NUM_BATERIA.Mes', $mes)
                        ->where('DEN_BATERIA.DENOMINADOR', '>', '0') ->where('DEN_BATERIA.PROVINCIA', $red)
                        ->groupBy('DEN_BATERIA.PROVINCIA') ->orderBy('DEN_BATERIA.PROVINCIA', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            // if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            // if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_BATERIA')
                        ->select('*', (DB::raw("CASE WHEN (TMZ_ANEMIA IS NULL OR SIFILIS IS NULL OR VIH IS NULL OR BACTERIURIA IS NULL) THEN 'NO' WHEN (TMZ_ANEMIA=SIFILIS AND TMZ_ANEMIA=VIH AND TMZ_ANEMIA=BACTERIURIA) THEN 'SI' ELSE 'NO' END 'MIDE'")))
                        ->where('ANIO', $anio) ->where('MES', $mes) ->where('DISTRITO', $dist)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('IPRESS', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_BATERIA')
                        ->select('DEN_BATERIA.PROVINCIA','DEN_BATERIA.DISTRITO','DEN_BATERIA.DENOMINADOR', 'NUM_BATERIA.NUMERADOR')
                        ->leftJoin('NUM_BATERIA', 'DEN_BATERIA.DISTRITO', '=', 'NUM_BATERIA.DISTRITO')
                        ->where('DEN_BATERIA.DENOMINADOR', '>', '0')
                        ->where('DEN_BATERIA.Anio', $anio) ->where('NUM_BATERIA.Anio', $anio)
                        ->where('DEN_BATERIA.Mes', $mes) ->where('NUM_BATERIA.Mes', $mes) ->where('DEN_BATERIA.DISTRITO', $dist)
                        ->orderBy('DEN_BATERIA.PROVINCIA', 'ASC') ->orderBy('DEN_BATERIA.DISTRITO', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_BATERIA')
                        ->select('DEN_BATERIA.PROVINCIA', (DB::raw('SUM(DEN_BATERIA.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_BATERIA.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_BATERIA', 'DEN_BATERIA.DISTRITO', '=', 'NUM_BATERIA.DISTRITO')
                        ->where('DEN_BATERIA.Anio', $anio) ->where('NUM_BATERIA.Anio', $anio)
                        ->where('DEN_BATERIA.Mes', $mes) ->where('NUM_BATERIA.Mes', $mes)
                        ->where('DEN_BATERIA.DENOMINADOR', '>', '0') ->where('DEN_BATERIA.DISTRITO', $dist)
                        ->groupBy('DEN_BATERIA.PROVINCIA') ->orderBy('DEN_BATERIA.PROVINCIA', 'ASC')
                        ->get();
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printBateria(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_BATERIA')
                        ->select('*', (DB::raw("CASE WHEN (TMZ_ANEMIA IS NULL OR SIFILIS IS NULL OR VIH IS NULL OR BACTERIURIA IS NULL) THEN 'NO' WHEN (TMZ_ANEMIA=SIFILIS AND TMZ_ANEMIA=VIH AND TMZ_ANEMIA=BACTERIURIA) THEN 'SI' ELSE 'NO' END 'MIDE'")))
                        ->where('ANIO', $anio) ->where('MES', $mes)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('IPRESS', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_BATERIA')
                        ->select('*', (DB::raw("CASE WHEN (TMZ_ANEMIA IS NULL OR SIFILIS IS NULL OR VIH IS NULL OR BACTERIURIA IS NULL) THEN 'NO' WHEN (TMZ_ANEMIA=SIFILIS AND TMZ_ANEMIA=VIH AND TMZ_ANEMIA=BACTERIURIA) THEN 'SI' ELSE 'NO' END 'MIDE'")))
                        ->where('ANIO', $anio) ->where('MES', $mes) ->where('PROVINCIA', $red)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('IPRESS', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_BATERIA')
                        ->select('*', (DB::raw("CASE WHEN (TMZ_ANEMIA IS NULL OR SIFILIS IS NULL OR VIH IS NULL OR BACTERIURIA IS NULL) THEN 'NO' WHEN (TMZ_ANEMIA=SIFILIS AND TMZ_ANEMIA=VIH AND TMZ_ANEMIA=BACTERIURIA) THEN 'SI' ELSE 'NO' END 'MIDE'")))
                        ->where('ANIO', $anio) ->where('MES', $mes) ->where('DISTRITO', $dist)
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('IPRESS', 'ASC')
                        ->get();
        }

        return Excel::download(new BateriaExport($nominal, $anio, $request->nameMonth, $request->his), 'DEIT_PASCO CG_FT_BATERIA_COMPLETA.xlsx');
    }

    public function indexTratamiento(Request $request) {
        return view('fed/Pregnant/sospecha_tratamiento/index');
    }

    public function listSospecha(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_SOSPECHA')
                        ->select('*') ->where('ANIO', $anio) ->where('MES', $mes)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC') ->orderBy('ATENDIDOS', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_SOSPECHA')
                        ->select('DEN_SOSPECHA.Provincia_Establecimiento','DEN_SOSPECHA.Distrito_Establecimiento','DEN_SOSPECHA.DENOMINADOR', 'NUM_SOSPECHA.NUMERADOR')
                        ->leftJoin('NUM_SOSPECHA', 'DEN_SOSPECHA.Distrito_Establecimiento', '=', 'NUM_SOSPECHA.Distrito_Establecimiento')
                        ->where('DEN_SOSPECHA.DENOMINADOR', '>', '0')
                        ->where('DEN_SOSPECHA.Anio', $anio) ->where('NUM_SOSPECHA.Anio', $anio)
                        ->where('DEN_SOSPECHA.Mes', $mes) ->where('NUM_SOSPECHA.Mes', $mes)
                        ->orderBy('DEN_SOSPECHA.Provincia_Establecimiento', 'ASC') ->orderBy('DEN_SOSPECHA.Distrito_Establecimiento', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_SOSPECHA')
                        ->select('DEN_SOSPECHA.Provincia_Establecimiento', (DB::raw('SUM(DEN_SOSPECHA.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_SOSPECHA.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_SOSPECHA', 'DEN_SOSPECHA.Distrito_Establecimiento', '=', 'NUM_SOSPECHA.Distrito_Establecimiento')
                        ->where('DEN_SOSPECHA.Anio', $anio) ->where('NUM_SOSPECHA.Anio', $anio)
                        ->where('DEN_SOSPECHA.Mes', $mes) ->where('NUM_SOSPECHA.Mes', $mes)
                        ->where('DEN_SOSPECHA.DENOMINADOR', '>', '0')
                        ->groupBy('DEN_SOSPECHA.Provincia_Establecimiento') ->orderBy('DEN_SOSPECHA.Provincia_Establecimiento', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_SOSPECHA')
                        ->select('*') ->where('ANIO', $anio) ->where('MES', $mes) ->where('Provincia_Establecimiento', $red)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC') ->orderBy('ATENDIDOS', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_SOSPECHA')
                        ->select('DEN_SOSPECHA.Provincia_Establecimiento','DEN_SOSPECHA.Distrito_Establecimiento','DEN_SOSPECHA.DENOMINADOR', 'NUM_SOSPECHA.NUMERADOR')
                        ->leftJoin('NUM_SOSPECHA', 'DEN_SOSPECHA.Distrito_Establecimiento', '=', 'NUM_SOSPECHA.Distrito_Establecimiento')
                        ->where('DEN_SOSPECHA.DENOMINADOR', '>', '0')
                        ->where('DEN_SOSPECHA.Anio', $anio) ->where('NUM_SOSPECHA.Anio', $anio)
                        ->where('DEN_SOSPECHA.Mes', $mes) ->where('NUM_SOSPECHA.Mes', $mes) ->where('DEN_SOSPECHA.Provincia_Establecimiento', $red)
                        ->orderBy('DEN_SOSPECHA.Provincia_Establecimiento', 'ASC') ->orderBy('DEN_SOSPECHA.Distrito_Establecimiento', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_SOSPECHA')
                        ->select('DEN_SOSPECHA.Provincia_Establecimiento', (DB::raw('SUM(DEN_SOSPECHA.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_SOSPECHA.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_SOSPECHA', 'DEN_SOSPECHA.Distrito_Establecimiento', '=', 'NUM_SOSPECHA.Distrito_Establecimiento')
                        ->where('DEN_SOSPECHA.Anio', $anio) ->where('NUM_SOSPECHA.Anio', $anio)
                        ->where('DEN_SOSPECHA.Mes', $mes) ->where('NUM_SOSPECHA.Mes', $mes)
                        ->where('DEN_SOSPECHA.DENOMINADOR', '>', '0') ->where('DEN_SOSPECHA.Provincia_Establecimiento', $red)
                        ->groupBy('DEN_SOSPECHA.Provincia_Establecimiento') ->orderBy('DEN_SOSPECHA.Provincia_Establecimiento', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            // if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            // if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_SOSPECHA')
                        ->select('*') ->where('Anio', $anio) ->where('Mes', $mes) ->where('Distrito_Establecimiento', $dist)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC') ->orderBy('ATENDIDOS', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_SOSPECHA')
                        ->select('DEN_SOSPECHA.Provincia_Establecimiento','DEN_SOSPECHA.Distrito_Establecimiento','DEN_SOSPECHA.DENOMINADOR', 'NUM_SOSPECHA.NUMERADOR')
                        ->leftJoin('NUM_SOSPECHA', 'DEN_SOSPECHA.Distrito_Establecimiento', '=', 'NUM_SOSPECHA.Distrito_Establecimiento')
                        ->where('DEN_SOSPECHA.DENOMINADOR', '>', '0') ->where('DEN_SOSPECHA.Distrito_Establecimiento', $dist)
                        ->where('DEN_SOSPECHA.Anio', $anio) ->where('NUM_SOSPECHA.Anio', $anio)
                        ->where('DEN_SOSPECHA.Mes', $mes) ->where('NUM_SOSPECHA.Mes', $mes)
                        ->orderBy('DEN_SOSPECHA.Provincia_Establecimiento', 'ASC') ->orderBy('DEN_SOSPECHA.Distrito_Establecimiento', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_SOSPECHA')
                        ->select('DEN_SOSPECHA.Provincia_Establecimiento', (DB::raw('SUM(DEN_SOSPECHA.DENOMINADOR) AS DEN')), (DB::raw('SUM(NUM_SOSPECHA.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_SOSPECHA', 'DEN_SOSPECHA.Distrito_Establecimiento', '=', 'NUM_SOSPECHA.Distrito_Establecimiento')
                        ->where('DEN_SOSPECHA.Anio', $anio) ->where('NUM_SOSPECHA.Anio', $anio)
                        ->where('DEN_SOSPECHA.Mes', $mes) ->where('NUM_SOSPECHA.Mes', $mes)
                        ->where('DEN_SOSPECHA.DENOMINADOR', '>', '0') ->where('DEN_SOSPECHA.Distrito_Establecimiento', $dist)
                        ->groupBy('DEN_SOSPECHA.Provincia_Establecimiento')
                        ->orderBy('DEN_SOSPECHA.Provincia_Establecimiento', 'ASC')
                        ->get();
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printSospecha(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_SOSPECHA')
                        ->select('*') ->where('ANIO', $anio) ->where('MES', $mes)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC') ->orderBy('ATENDIDOS', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOLIDADO_SOSPECHA')
                        ->select('*') ->where('ANIO', $anio) ->where('MES', $mes)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC') ->orderBy('ATENDIDOS', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            // if($dist == 'CONSTITUCIÓN'){ $dist = 'CONSTITUCION'; }
            // if($dist == 'SAN FRANCISCO DE ASIS DE YARUSYACAN'){ $dist = 'SAN FCO DE ASIS DE YARUSYACAN'; }
            $nominal = DB::table('dbo.CONSOLIDADO_SOSPECHA')
                        ->select('*') ->where('Anio', $anio) ->where('Mes', $mes)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC') ->orderBy('ATENDIDOS', 'ASC')
                        ->get();
        }

        return Excel::download(new SospechaVioExport($nominal, $anio, $request->nameMonth, $request->his), 'DEIT_PASCO CG_FT_GESTANTES CON SOSPECHA DE VIOLENCIA.xlsx');
    }

    public function listTratamiento(Request $request){
        $red_1 = $request->red2;
        $dist = $request->distrito2;
        $anio = $request->anio2;
        $mes = $request->mes2;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $nominal = DB::statement("SELECT *, DATEDIFF (DAY, R456 , diagnostico) AS DIA1,  DATEDIFF (DAY, diagnostico , iniciotto) AS DIA2,
                    CASE WHEN (VIF IS NOT NULL AND R456 IS NOT NULL) AND (diagnostico IS NOT NULL AND iniciotto IS NOT NULL) AND
                    (VIF = R456) AND (((DATEDIFF (DAY, R456 , diagnostico)) <= 15) AND (DATEDIFF (DAY, R456 , diagnostico)) >= 0)
                    AND (((DATEDIFF (DAY, diagnostico , iniciotto)) <= 7) AND (DATEDIFF (DAY, diagnostico , iniciotto)) >= 0)
                    THEN 'SI' ELSE 'NO' END 'MIDE' INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO1
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO
                    WHERE VIF IS NOT NULL AND R456 IS NOT NULL AND Anio=$anio AND Mes=$mes
                    ORDER BY Provincia_Establecimiento, Distrito_Establecimiento, ATENDIDOS;
                    with c as ( select ATENDIDOS,  ROW_NUMBER() over(partition by ATENDIDOS order by iniciotto) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO1 ) delete from c where duplicado >1;
                    SELECT * FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO1");

        $num_den = DB::statement("SELECT Provincia_Establecimiento, Distrito_Establecimiento, COUNT(*) 'DENOMINADOR'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DEN_TRATAMIENTO
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO1
                    GROUP BY Provincia_Establecimiento, Distrito_Establecimiento;

                    SELECT Provincia_Establecimiento,Distrito_Establecimiento, COUNT( CASE WHEN (MIDE='SI') THEN 'SI' END) 'NUMERADOR'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.NUM_TRATAMIENTO
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO1
                    GROUP BY Provincia_Establecimiento,Distrito_Establecimiento");

        if($red_1 == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOLIDADO_TRATAMIENTO1')
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_TRATAMIENTO AS a')
                        ->select('a.Provincia_Establecimiento','a.Distrito_Establecimiento','a.DENOMINADOR', 'b.NUMERADOR')
                        ->leftJoin('NUM_TRATAMIENTO AS b', 'a.Distrito_Establecimiento', '=', 'b.Distrito_Establecimiento')
                        ->where('a.DENOMINADOR', '>', '0')
                        ->orderBy('a.Provincia_Establecimiento', 'ASC') ->orderBy('a.Distrito_Establecimiento', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_TRATAMIENTO AS a')
                        ->select('a.Provincia_Establecimiento', (DB::raw('SUM(a.DENOMINADOR) AS DEN')), (DB::raw('SUM(b.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_TRATAMIENTO AS b', 'a.Distrito_Establecimiento', '=', 'b.Distrito_Establecimiento')
                        ->where('a.DENOMINADOR', '>', '0')
                        ->groupBy('a.Provincia_Establecimiento') ->orderBy('a.Provincia_Establecimiento', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOLIDADO_TRATAMIENTO1') ->where('Provincia_Establecimiento', $red)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_TRATAMIENTO AS a')
                        ->select('a.Provincia_Establecimiento','a.Distrito_Establecimiento','a.DENOMINADOR', 'b.NUMERADOR')
                        ->leftJoin('NUM_TRATAMIENTO AS b', 'a.Distrito_Establecimiento', '=', 'b.Distrito_Establecimiento')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.Provincia_Establecimiento', $red)
                        ->orderBy('a.Provincia_Establecimiento', 'ASC') ->orderBy('a.Distrito_Establecimiento', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_TRATAMIENTO AS a')
                        ->select('a.Provincia_Establecimiento', (DB::raw('SUM(a.DENOMINADOR) AS DEN')), (DB::raw('SUM(b.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_TRATAMIENTO AS b', 'a.Distrito_Establecimiento', '=', 'b.Distrito_Establecimiento')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.Provincia_Establecimiento', $red)
                        ->groupBy('a.Provincia_Establecimiento') ->orderBy('a.Provincia_Establecimiento', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOLIDADO_TRATAMIENTO1') ->where('Distrito_Establecimiento', $dist)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_TRATAMIENTO AS a')
                        ->select('a.Provincia_Establecimiento','a.Distrito_Establecimiento','a.DENOMINADOR', 'b.NUMERADOR')
                        ->leftJoin('NUM_TRATAMIENTO AS b', 'a.Distrito_Establecimiento', '=', 'b.Distrito_Establecimiento')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.Distrito_Establecimiento', $dist)
                        ->orderBy('a.Provincia_Establecimiento', 'ASC') ->orderBy('a.Distrito_Establecimiento', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_TRATAMIENTO AS a')
                        ->select('a.Provincia_Establecimiento', (DB::raw('SUM(a.DENOMINADOR) AS DEN')), (DB::raw('SUM(b.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_TRATAMIENTO AS b', 'a.Distrito_Establecimiento', '=', 'b.Distrito_Establecimiento')
                        ->where('a.DENOMINADOR', '>', '0') ->where('a.Distrito_Establecimiento', $dist)
                        ->groupBy('a.Provincia_Establecimiento') ->orderBy('a.Provincia_Establecimiento', 'ASC')
                        ->get();
        }

        $query1 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOLIDADO_TRATAMIENTO1
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DEN_TRATAMIENTO
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.NUM_TRATAMIENTO"));

        $q[] = json_decode($nominal2, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function indexNewUsers(Request $request) {
        return view('fed/Pregnant/NewUsers/index');
    }

    public function listNewUsers(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('BDHIS_MINSA')
                    ->statement("SELECT distinct try_convert(int,r.Codigo_Unico) renaes, try_convert(date,Fecha_Atencion) fecha_cita, convert(varchar,Tipo_Doc_Paciente)+convert(varchar,Numero_Documento_Paciente) id, den=1
                    into BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USERNEW_GES
                    from BDHIS_MINSA_EXTERNO_V2.dbo.TRAMAHIS_GES h
                    left join BDHIS_MINSA_EXTERNO_V2.dbo.RENAES_GES r ON TRY_CONVERT(INT,h.Codigo_Unico) = TRY_CONVERT(INT,R.Codigo_Unico)
                    where ltrim(rtrim(Codigo_Item)) in ('99208') and ltrim(rtrim(Tipo_Diagnostico)) in ('D')
                    and month(try_convert(date,Fecha_Atencion))='".$mes."' and year(try_convert(date,Fecha_Atencion))='".$anio."'
                    and Numero_Documento_Paciente is not null AND Categoria_Establecimiento IN ('I-1','I-2','I-3','I-4');

                    SELECT distinct try_convert(int,Codigo_Unico) renaes, try_convert(date,Fecha_Atencion) fecha_cita, convert(varchar,Tipo_Doc_Paciente)+convert(varchar,Numero_Documento_Paciente) id, num=1
                    into BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USERNEW_GES
                    from BDHIS_MINSA_EXTERNO_V2.dbo.TRAMAHIS_GES
                    where ((ltrim(rtrim(Codigo_Item)) = '96150' and ltrim(rtrim(Tipo_Diagnostico)) ='D' and ltrim(rtrim(valor_lab)) ='VIF'	)
                    or (ltrim(rtrim(Codigo_Item)) = '96150.01' and ltrim(rtrim(Tipo_Diagnostico)) = 'D' )
                    ) and month(try_convert(date,Fecha_Atencion))='".$mes."' and year(try_convert(date,Fecha_Atencion))='".$anio."';

                    SELECT  m.Provincia,m.Distrito,m.Nombre_Establecimiento,SUBSTRING(d.id,2,10)documento,d.fecha_cita ATE_PLANIFICACION,n.fecha_cita TMZ_VIF
                    intO BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES
                    from BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USERNEW_GES d left join BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USERNEW_GES n on d.id=n.id
                    left join MAESTRO_HIS_ESTABLECIMIENTO m on d.renaes=cast(m.Codigo_Unico as int)
                    ORDER BY Provincia, Distrito, Nombre_Establecimiento;

                    with c as (select DOCUMENTO,  ROW_NUMBER() over(partition by DOCUMENTO order by DOCUMENTO) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES)
                    delete  from c
                    where duplicado >1;

                    SELECT Provincia, Distrito, COUNT(*) AS 'DENOMINADOR'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USUARIAS_NUEVAS
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES
                    GROUP BY Provincia, Distrito

                    SELECT Provincia,Distrito, COUNT(CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' END) AS 'NUMERADOR'
                    into BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USUARIAS_NUEVAS
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES
                    GROUP BY Provincia, Distrito");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.PADRONINICIO_GES')
                        ->select('*', (DB::raw("CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND
                        (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END MIDE")))
                        ->orderBy('Provincia', 'ASC') ->orderBy('Distrito', 'ASC') ->orderBy('Nombre_Establecimiento', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_USUARIAS_NUEVAS AS A')
                        ->select('A.Provincia','A.Distrito','A.DENOMINADOR', 'B.NUMERADOR')
                        ->leftJoin('NUM_USUARIAS_NUEVAS AS B', 'A.Distrito', '=', 'B.Distrito')
                        ->where('A.DENOMINADOR', '>', '0')
                        ->orderBy('A.Provincia', 'ASC') ->orderBy('A.Distrito', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_USUARIAS_NUEVAS AS A')
                        ->select('A.Provincia', (DB::raw('SUM(A.DENOMINADOR) AS DEN')), (DB::raw('SUM(B.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_USUARIAS_NUEVAS AS B', 'A.Distrito', '=', 'B.Distrito')
                        ->where('A.DENOMINADOR', '>', '0')
                        ->groupBy('A.Provincia') ->orderBy('A.Provincia', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.PADRONINICIO_GES')
                        ->select('*', (DB::raw("CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND
                        (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END MIDE"))) ->where('Provincia', $red)
                        ->orderBy('Provincia', 'ASC') ->orderBy('Distrito', 'ASC') ->orderBy('Nombre_Establecimiento', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_USUARIAS_NUEVAS AS A')
                        ->select('A.Provincia','A.Distrito','A.DENOMINADOR', 'B.NUMERADOR')
                        ->leftJoin('NUM_USUARIAS_NUEVAS AS B', 'A.Distrito', '=', 'B.Distrito')
                        ->where('A.DENOMINADOR', '>', '0') ->where('A.Provincia', $red)
                        ->orderBy('A.Provincia', 'ASC') ->orderBy('A.Distrito', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_USUARIAS_NUEVAS AS A')
                        ->select('A.Provincia', (DB::raw('SUM(A.DENOMINADOR) AS DEN')), (DB::raw('SUM(B.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_USUARIAS_NUEVAS AS B', 'A.Distrito', '=', 'B.Distrito')
                        ->where('A.DENOMINADOR', '>', '0') ->where('A.Provincia', $red)
                        ->groupBy('A.Provincia') ->orderBy('A.Provincia', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.PADRONINICIO_GES')
                        ->select('*', (DB::raw("CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND
                        (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END MIDE"))) ->where('Distrito', $dist)
                        ->orderBy('Provincia', 'ASC') ->orderBy('Distrito', 'ASC') ->orderBy('Nombre_Establecimiento', 'ASC')
                        ->get();

            $t_resume = DB::table('DEN_USUARIAS_NUEVAS AS A')
                        ->select('A.Provincia','A.Distrito','A.DENOMINADOR', 'B.NUMERADOR')
                        ->leftJoin('NUM_USUARIAS_NUEVAS AS B', 'A.Distrito', '=', 'B.Distrito')
                        ->where('A.DENOMINADOR', '>', '0') ->where('A.Distrito', $dist)
                        ->orderBy('A.Provincia', 'ASC') ->orderBy('A.Distrito', 'ASC')
                        ->get();

            $resum_red = DB::table('DEN_USUARIAS_NUEVAS AS A')
                        ->select('A.Provincia', (DB::raw('SUM(A.DENOMINADOR) AS DEN')), (DB::raw('SUM(B.NUMERADOR) AS NUM')))
                        ->leftJoin('NUM_USUARIAS_NUEVAS AS B', 'A.Distrito', '=', 'B.Distrito')
                        ->where('A.DENOMINADOR', '>', '0') ->where('A.Distrito', $dist)
                        ->groupBy('A.Provincia') ->orderBy('A.Provincia', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USERNEW_GES
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USERNEW_GES
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USUARIAS_NUEVAS
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USUARIAS_NUEVAS"));

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printNewUsers(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('BDHIS_MINSA')
                    ->statement("SELECT distinct try_convert(int,r.Codigo_Unico) renaes, try_convert(date,Fecha_Atencion) fecha_cita, convert(varchar,Tipo_Doc_Paciente)+convert(varchar,Numero_Documento_Paciente) id, den=1
                    into BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USERNEW_GES
                    from BDHIS_MINSA_EXTERNO_V2.dbo.TRAMAHIS_GES h
                    left join BDHIS_MINSA_EXTERNO_V2.dbo.RENAES_GES r ON TRY_CONVERT(INT,h.Codigo_Unico) = TRY_CONVERT(INT,R.Codigo_Unico)
                    where ltrim(rtrim(Codigo_Item)) in ('99208') and ltrim(rtrim(Tipo_Diagnostico)) in ('D')
                    and month(try_convert(date,Fecha_Atencion))='".$mes."' and year(try_convert(date,Fecha_Atencion))='".$anio."'
                    and Numero_Documento_Paciente is not null AND Categoria_Establecimiento IN ('I-1','I-2','I-3','I-4');

                    SELECT distinct try_convert(int,Codigo_Unico) renaes, try_convert(date,Fecha_Atencion) fecha_cita, convert(varchar,Tipo_Doc_Paciente)+convert(varchar,Numero_Documento_Paciente) id, num=1
                    into BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USERNEW_GES
                    from BDHIS_MINSA_EXTERNO_V2.dbo.TRAMAHIS_GES
                    where ((ltrim(rtrim(Codigo_Item)) = '96150' and ltrim(rtrim(Tipo_Diagnostico)) ='D' and ltrim(rtrim(valor_lab)) ='VIF'	)
                    or (ltrim(rtrim(Codigo_Item)) = '96150.01' and ltrim(rtrim(Tipo_Diagnostico)) = 'D' )
                    ) and month(try_convert(date,Fecha_Atencion))='".$mes."' and year(try_convert(date,Fecha_Atencion))='".$anio."';

                    SELECT  m.Provincia,m.Distrito,m.Nombre_Establecimiento,SUBSTRING(d.id,2,10)documento,d.fecha_cita ATE_PLANIFICACION,n.fecha_cita TMZ_VIF
                    intO BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES
                    from BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USERNEW_GES d left join BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USERNEW_GES n on d.id=n.id
                    left join MAESTRO_HIS_ESTABLECIMIENTO m on d.renaes=cast(m.Codigo_Unico as int)
                    ORDER BY Provincia, Distrito, Nombre_Establecimiento;
                    with c as (select DOCUMENTO,  ROW_NUMBER() over(partition by DOCUMENTO order by DOCUMENTO) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES)
                    delete  from c
                    where duplicado >1;");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.PADRONINICIO_GES')
                        ->select('*', (DB::raw("CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND
                        (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END MIDE")))
                        ->orderBy('Provincia', 'ASC') ->orderBy('Distrito', 'ASC') ->orderBy('Nombre_Establecimiento', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.PADRONINICIO_GES')
                        ->select('*', (DB::raw("CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND
                        (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END MIDE"))) ->where('Provincia', $red)
                        ->orderBy('Provincia', 'ASC') ->orderBy('Distrito', 'ASC') ->orderBy('Nombre_Establecimiento', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.PADRONINICIO_GES')
                        ->select('*', (DB::raw("CASE WHEN ((ATE_PLANIFICACION = TMZ_VIF) AND (ATE_PLANIFICACION IS NOT NULL) AND
                        (TMZ_VIF IS NOT NULL)) THEN 'CUMPLE' ELSE 'NO CUMPLE' END MIDE"))) ->where('Distrito', $dist)
                        ->orderBy('Provincia', 'ASC') ->orderBy('Distrito', 'ASC') ->orderBy('Nombre_Establecimiento', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.NUM_USERNEW_GES
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DEN_USERNEW_GES
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRONINICIO_GES"));

        return Excel::download(new UsersNewExport($nominal, $anio, $request->nameMonth, $request->his), 'DEIT_PASCO CG_FT_USUAR_NUEVAS_SERV_PLANIF_FAM - PPFF_CON_DX_VIOLENC (TMZ).xlsx');
    }

    public function indexProfesion(Request $request) {
        return view('fed/medicines/index');
    }

    public function listProfesion(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('BDHIS_MINSA')
                ->statement("SELECT DISTINCT(Numero_Documento_Personal),Provincia_Establecimiento, Distrito_Establecimiento, Codigo_Unico, Nombre_Establecimiento, Descripcion_Profesion, mes,
                concat(Nombres_Personal,' ',Apellido_Paterno_Paciente) PERSONAL
                INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_PROFESIONALES
                from T_CONSOLIDADO_NUEVA_TRAMA_HISMINSA
                where mes='". $mes ."'and anio='". $anio ."' and Codigo_Item in ('99208','99402.04', 'Z3491','Z3492','Z3493','Z3591','Z3592','Z3593', 'z001',
                '90585', '90744', '90712', '90713', '90723', '90681', '90670', '90657', '90658','90707', '90717','90701','Z298')
                AND ID_CITA not IN (SELECT Id_Cita FROM T_CONSOLIDADO_NUEVA_TRAMA_HISMINSA WHERE Codigo_Item in ('99499.01','99499.08','99499.10')
                AND ANIO='". $anio ."' AND MES='". $mes ."')
                ORDER BY Provincia_Establecimiento, Distrito_Establecimiento, Codigo_Unico, Nombre_Establecimiento, Numero_Documento_Personal");

        if($red_1 == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_PROFESIONALES')
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_PROFESIONALES') ->where('Provincia_Establecimiento', $red)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_PROFESIONALES') ->where('Distrito_Establecimiento', $dist)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_PROFESIONALES"));

        return response()->json($nominal2);
    }

    public function printProfesion(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('BDHIS_MINSA')
                ->statement("SELECT DISTINCT(Numero_Documento_Personal),Provincia_Establecimiento, Distrito_Establecimiento, Codigo_Unico, Nombre_Establecimiento, Descripcion_Profesion, mes,
                concat(Nombres_Personal,' ',Apellido_Paterno_Paciente) PERSONAL
                INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_PROFESIONALES
                from T_CONSOLIDADO_NUEVA_TRAMA_HISMINSA
                where mes='". $mes ."'and anio='". $anio ."' and Codigo_Item in ('99208','99402.04', 'Z3491','Z3492','Z3493','Z3591','Z3592','Z3593', 'z001',
                '90585', '90744', '90712', '90713', '90723', '90681', '90670', '90657', '90658','90707', '90717','90701','Z298')
                AND ID_CITA not IN (SELECT Id_Cita FROM T_CONSOLIDADO_NUEVA_TRAMA_HISMINSA WHERE Codigo_Item in ('99499.01','99499.08','99499.10')
                AND ANIO='". $anio ."' AND MES='". $mes ."')
                ORDER BY Provincia_Establecimiento, Distrito_Establecimiento, Codigo_Unico, Nombre_Establecimiento, Numero_Documento_Personal");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_PROFESIONALES')
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_PROFESIONALES') ->where('Provincia_Establecimiento', $red)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_PROFESIONALES') ->where('Distrito_Establecimiento', $dist)
                        ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_PROFESIONALES"));

        return Excel::download(new ProfessionalsExport($nominal, $anio, $request->nameMonth, $request->his), 'DEIT_PASCO CG_FT_USUAR_NUEVAS_SERV_PLANIF_FAM - PPFF_CON_DX_VIOLENC (TMZ).xlsx');
    }

    public function indexSisCovid(Request $request) {
        return view('fed/SisCovid/index');
    }

    public function listSisCovid(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL ALCIDES CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('SIS_COVID')
                    ->statement("SELECT M.Descripcion_Sector,U.DESC_DEPART,U.DESC_PROV,U.DESC_DIST, USUARIO_PROCEDENCIA, NUMERO_DOCUMENTO,
                    CAST(FECHA_REGISTRO AS DATE) AS FECHA_EVALUACION, FECHA_REGISTRO, TIPO='SOSPECHOSO'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO FROM F0 F
                    LEFT JOIN MAESTRO_HIS_ESTABLECIMIENTO M ON F.USUARIO_PROCEDENCIA = M.Nombre_Establecimiento AND M.Departamento='PASCO'
                    LEFT JOIN MAESTRO_UBIGEO_20200407 U ON M.Departamento=U.DESC_DEPART AND M.Provincia=U.DESC_PROV AND M.Distrito=U.DESC_DIST AND U.DESC_DEPART='PASCO'
                    WHERE CASO_VALIDO='SOSPECHOSO' AND TIPO_SEVERIDAD='LEVE' AND
                    YEAR(FECHA_REGISTRO)='". $anio ."' AND MONTH(FECHA_REGISTRO)='". $mes ."' AND DAY(FECHA_REGISTRO)<='25';
                    with c as ( select NUMERO_DOCUMENTO,FECHA_EVALUACION,FECHA_REGISTRO, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FECHA_EVALUACION ASC, FECHA_REGISTRO DESC) as duplicado
                    from  BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO)
                    delete  from c
                    where duplicado >1;

                    SELECT M.Descripcion_Sector,U.DESC_DEPART,U.DESC_PROV,U.DESC_DIST,ESTABLECIMIENTO_EJECUTA,NUMERO_DOCUMENTO,FECHA_EJECUCION_PRUEBA,
                    FECHA_REGISTRO, TIPO='PRUEBA' INTO BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA FROM F100 F
                    LEFT JOIN MAESTRO_HIS_ESTABLECIMIENTO M ON CONVERT(INT,F.COD_ESTABLECIMIENTO_EJECUTA) = M.Codigo_Unico AND M.Departamento='PASCO'
                    LEFT JOIN MAESTRO_UBIGEO_20200407 U ON M.Departamento=U.DESC_DEPART AND M.Provincia=U.DESC_PROV AND M.Distrito=U.DESC_DIST AND U.DESC_DEPART='PASCO'
                    WHERE YEAR(FECHA_EJECUCION_PRUEBA)='". $anio ."' AND MONTH(FECHA_EJECUCION_PRUEBA)='". $mes ."' AND DAY(FECHA_EJECUCION_PRUEBA)<='25' AND (RESULTADO1 LIKE '%POSITIVO%' 
                    OR RESULTADO1 IN ('IgM e IgG Reactivo','Reactivo','Anticuerpos totales reactivo','IgM Reactivo','IgG Reactivo')) AND CLASIFICACION_CLINICA_SEVERIDAD LIKE '%LEVE%'
                    AND GERESA_DIRESA_DIRIS='PASCO';
                    with c as ( select NUMERO_DOCUMENTO,FECHA_EJECUCION_PRUEBA,FECHA_REGISTRO, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FECHA_EJECUCION_PRUEBA ASC, FECHA_REGISTRO DESC) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA)
                    delete  from c
                    where duplicado >1;

                    SELECT NUMERO_DOCUMENTO,FICHA_300_FECHA_DEL_SEGUIMIENTO,FECHA_REGISTRO INTO BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS FROM F300
                    WHERE YEAR(FICHA_300_FECHA_DEL_SEGUIMIENTO)='". $anio ."' AND MONTH(FICHA_300_FECHA_DEL_SEGUIMIENTO)='". $mes ."' AND DAY(FICHA_300_FECHA_DEL_SEGUIMIENTO)<='25';
                    with c as ( select NUMERO_DOCUMENTO,FICHA_300_FECHA_DEL_SEGUIMIENTO,FECHA_REGISTRO, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FICHA_300_FECHA_DEL_SEGUIMIENTO ASC, FECHA_REGISTRO ASC) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS)
                    delete  from c
                    where duplicado >1;

                    SELECT NUMERO_DOCUMENTO,FECHA_RECETA,FECHA_ENTREGA INTO BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO
                    FROM MED WHERE (YEAR(FECHA_RECETA)='". $anio ."' AND MONTH(FECHA_RECETA)='". $mes ."' AND DAY(FECHA_RECETA)<='25');
                    with c as ( select NUMERO_DOCUMENTO,FECHA_RECETA,FECHA_ENTREGA, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FECHA_RECETA ASC, FECHA_ENTREGA ASC) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO)
                    delete  from c
                    where duplicado >1;

                    SELECT F.*,S.FICHA_300_FECHA_DEL_SEGUIMIENTO,M.FECHA_RECETA,M.FECHA_ENTREGA,
                    CASE WHEN (DATEDIFF(DAY,FECHA_EJECUCION_PRUEBA,S.FICHA_300_FECHA_DEL_SEGUIMIENTO) IN (1,0)
                    AND DATEDIFF(DAY,FECHA_EJECUCION_PRUEBA,M.FECHA_RECETA) IN (1,0)
                    AND DATEDIFF(DAY,FECHA_EJECUCION_PRUEBA,M.FECHA_ENTREGA) IN (1,0,2) ) THEN 'CUMPLE' ELSE 'NO CUMPLE' END AS 'FED'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_SISCOVID
                    FROM ( SELECT * FROM BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA WHERE Descripcion_Sector IN ('GOBIERNO REGIONAL')
                    UNION ALL SELECT * FROM BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO WHERE Descripcion_Sector IN ('GOBIERNO REGIONAL')) F
                    LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS S ON F.NUMERO_DOCUMENTO=S.NUMERO_DOCUMENTO
                    LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO M ON F.NUMERO_DOCUMENTO=M.NUMERO_DOCUMENTO
                    ORDER BY DESC_PROV, DESC_DIST");

        if($red_1 == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_SISCOVID')
                        ->select('*', (DB::raw('Convert(DATE, FECHA_REGISTRO) AS FECHA_REGISTRO2')))
                        ->orderBy('DESC_PROV', 'ASC') ->orderBy('DESC_DIST', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_SISCOVID')
                        ->select('*', (DB::raw('Convert(DATE, FECHA_REGISTRO) AS FECHA_REGISTRO2')))
                        ->where('DESC_PROV', $red)
                        ->orderBy('DESC_PROV', 'ASC') ->orderBy('DESC_DIST', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_SISCOVID')
                        ->select('*', (DB::raw('Convert(DATE, FECHA_REGISTRO) AS FECHA_REGISTRO2')))
                        ->where('DESC_DIST', $dist)
                        ->orderBy('DESC_PROV', 'ASC') ->orderBy('DESC_DIST', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_SISCOVID"));

        return response()->json($nominal2);
    }

    public function printSisCovid(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL ALCIDES CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $query = DB::connection('SIS_COVID')
                    ->statement("SELECT M.Descripcion_Sector,U.DESC_DEPART,U.DESC_PROV,U.DESC_DIST, USUARIO_PROCEDENCIA, NUMERO_DOCUMENTO,
                    CAST(FECHA_REGISTRO AS DATE) AS FECHA_EVALUACION, FECHA_REGISTRO, TIPO='SOSPECHOSO'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO FROM F0 F
                    LEFT JOIN MAESTRO_HIS_ESTABLECIMIENTO M ON F.USUARIO_PROCEDENCIA = M.Nombre_Establecimiento AND M.Departamento='PASCO'
                    LEFT JOIN MAESTRO_UBIGEO_20200407 U ON M.Departamento=U.DESC_DEPART AND M.Provincia=U.DESC_PROV AND M.Distrito=U.DESC_DIST AND U.DESC_DEPART='PASCO'
                    WHERE CASO_VALIDO='SOSPECHOSO' AND TIPO_SEVERIDAD='LEVE' AND
                    YEAR(FECHA_REGISTRO)='". $anio ."' AND MONTH(FECHA_REGISTRO)='". $mes ."' AND DAY(FECHA_REGISTRO)<='25';
                    with c as ( select NUMERO_DOCUMENTO,FECHA_EVALUACION,FECHA_REGISTRO, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FECHA_EVALUACION ASC, FECHA_REGISTRO DESC) as duplicado
                    from  BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO)
                    delete  from c
                    where duplicado >1;

                    SELECT M.Descripcion_Sector,U.DESC_DEPART,U.DESC_PROV,U.DESC_DIST,ESTABLECIMIENTO_EJECUTA,NUMERO_DOCUMENTO,FECHA_EJECUCION_PRUEBA,
                    FECHA_REGISTRO, TIPO='PRUEBA' INTO BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA FROM F100 F
                    LEFT JOIN MAESTRO_HIS_ESTABLECIMIENTO M ON CONVERT(INT,F.COD_ESTABLECIMIENTO_EJECUTA) = M.Codigo_Unico AND M.Departamento='PASCO'
                    LEFT JOIN MAESTRO_UBIGEO_20200407 U ON M.Departamento=U.DESC_DEPART AND M.Provincia=U.DESC_PROV AND M.Distrito=U.DESC_DIST AND U.DESC_DEPART='PASCO'
                    WHERE YEAR(FECHA_EJECUCION_PRUEBA)='". $anio ."' AND MONTH(FECHA_EJECUCION_PRUEBA)='". $mes ."' AND DAY(FECHA_EJECUCION_PRUEBA)<='25' AND (RESULTADO1 LIKE '%POSITIVO%' 
                    OR RESULTADO1 IN ('IgM e IgG Reactivo','Reactivo','Anticuerpos totales reactivo','IgM Reactivo','IgG Reactivo')) AND CLASIFICACION_CLINICA_SEVERIDAD LIKE '%LEVE%'
                    AND GERESA_DIRESA_DIRIS='PASCO';
                    with c as ( select NUMERO_DOCUMENTO,FECHA_EJECUCION_PRUEBA,FECHA_REGISTRO, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FECHA_EJECUCION_PRUEBA ASC, FECHA_REGISTRO DESC) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA)
                    delete  from c
                    where duplicado >1;

                    SELECT NUMERO_DOCUMENTO,FICHA_300_FECHA_DEL_SEGUIMIENTO,FECHA_REGISTRO INTO BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS FROM F300
                    WHERE YEAR(FICHA_300_FECHA_DEL_SEGUIMIENTO)='". $anio ."' AND MONTH(FICHA_300_FECHA_DEL_SEGUIMIENTO)='". $mes ."' AND DAY(FICHA_300_FECHA_DEL_SEGUIMIENTO)<='25';
                    with c as ( select NUMERO_DOCUMENTO,FICHA_300_FECHA_DEL_SEGUIMIENTO,FECHA_REGISTRO, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FICHA_300_FECHA_DEL_SEGUIMIENTO ASC, FECHA_REGISTRO ASC) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS)
                    delete  from c
                    where duplicado >1;

                    SELECT NUMERO_DOCUMENTO,FECHA_RECETA,FECHA_ENTREGA INTO BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO
                    FROM MED WHERE (YEAR(FECHA_RECETA)='". $anio ."' AND MONTH(FECHA_RECETA)='". $mes ."' AND DAY(FECHA_RECETA)<='25');
                    with c as ( select NUMERO_DOCUMENTO,FECHA_RECETA,FECHA_ENTREGA, ROW_NUMBER()
                            over(partition by NUMERO_DOCUMENTO  order by FECHA_RECETA ASC, FECHA_ENTREGA ASC) as duplicado
                    from BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO)
                    delete  from c
                    where duplicado >1;

                    SELECT F.*,S.FICHA_300_FECHA_DEL_SEGUIMIENTO,M.FECHA_RECETA,M.FECHA_ENTREGA,
                    CASE WHEN (DATEDIFF(DAY,FECHA_EJECUCION_PRUEBA,S.FICHA_300_FECHA_DEL_SEGUIMIENTO) IN (1,0)
                    AND DATEDIFF(DAY,FECHA_EJECUCION_PRUEBA,M.FECHA_RECETA) IN (1,0)
                    AND DATEDIFF(DAY,FECHA_EJECUCION_PRUEBA,M.FECHA_ENTREGA) IN (1,0,2) ) THEN 'CUMPLE' ELSE 'NO CUMPLE' END AS 'FED'
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_SISCOVID
                    FROM ( SELECT * FROM BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA WHERE Descripcion_Sector IN ('GOBIERNO REGIONAL')
                    UNION ALL SELECT * FROM BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO WHERE Descripcion_Sector IN ('GOBIERNO REGIONAL')) F
                    LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS S ON F.NUMERO_DOCUMENTO=S.NUMERO_DOCUMENTO
                    LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO M ON F.NUMERO_DOCUMENTO=M.NUMERO_DOCUMENTO
                    ORDER BY DESC_PROV, DESC_DIST");

        if($red_1 == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_SISCOVID')
                        ->select('*', (DB::raw('Convert(DATE, FECHA_REGISTRO) AS FECHA_REGISTRO2')))
                        ->orderBy('DESC_PROV', 'ASC') ->orderBy('DESC_DIST', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_SISCOVID')
                        ->select('*', (DB::raw('Convert(DATE, FECHA_REGISTRO) AS FECHA_REGISTRO2')))
                        ->where('DESC_PROV', $red)
                        ->orderBy('DESC_PROV', 'ASC') ->orderBy('DESC_DIST', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal2 = DB::table('dbo.CONSOL_SISCOVID')
                        ->select('*', (DB::raw('Convert(DATE, FECHA_REGISTRO) AS FECHA_REGISTRO2')))
                        ->where('DESC_DIST', $dist)
                        ->orderBy('DESC_PROV', 'ASC') ->orderBy('DESC_DIST', 'ASC')
                        ->get();
        }

        $query2 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PRUEBA
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.SOSPECHOSO
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.SEGUIMIENTOS
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.MEDICAMENTO
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_SISCOVID"));

        return Excel::download(new SisCovidExport($nominal2, $anio, $request->nameMonth, $request->his), 'DEIT_PASCO SIS COVID.xlsx');
    }
}