<?php

namespace App\Http\Controllers;

use App\Exports\PrematureExport;
use App\Exports\TmzNeonatalExport;
use App\Exports\SuplementadosExport;
use App\Exports\BateriaExport;
use App\Exports\SospechaVioExport;
use App\Exports\CredMonthlyExport;
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
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
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

            $t_resume = DB::table('DEN_FourthMonth')
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

	    return Excel::download(new SuplementadosExport($nominal, $anio, $request->nameMonth, $request->pn, $request->his), 'DEIT_PASCO CG_FT_SUPLEMENTACION_kidsS_4_MESES.xlsx');
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

    public function indexProfesion(Request $request) {
        return view('fed/medicamentos/index');
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
                    WHERE cast(FECHA_NACIMIENTO_NINO as date)>='".$anio."-".$mes2."-01' AND MES='$anioVivo$mesVivo2';
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
                    WHERE cast(FECHA_NACIMIENTO_NINO as date)>='".$anio."-".$mes2."-01' AND MES='$anioVivo$mesVivo2';
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

        return Excel::download(new CredMonthlyExport($nominal, $anio, $request->nameMonth, $request->his), 'DEIT_PASCO CRED_MENSUAL.xlsx');
    }
}