<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;
use App\Exports\ReportMetlsXProvExport;
use App\Exports\ReportMetlsXDniExport;
use App\Exports\HomologationExport;

use PDF;

class TracingController extends Controller
{
    public function index() {
        return view('index');
    }

    public function indexHeavyMetals() {
        return view('HeavyMetals/index');
    }

    public function listHeavyMetals(Request $request){
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
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*')
                    ->get();

            $t_resume = '';

            $resum_red = '';
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*') ->where('PROVINCIA_ACTUAL', $red)
                    ->get();

            $t_resume = '';

            $resum_red = '';
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*') ->where('DISTRITO_ACTUAL', $dist)
                    ->get();

            $t_resume = '';

            $resum_red = '';
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function listHeavyMetalsDni(Request $request){
        $doc = $request->doc;
        $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*') ->where('NUMERO_DOCUMENTO', $doc)
                    ->get();

        return response()->json($nominal);
    }

    public function printHeavyMetals(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS') ->select('*') ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS') ->select('*') ->where('PROVINCIA_ACTUAL', $red) ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS') ->select('*') ->where('DISTRITO_ACTUAL', $dist) ->get();
        }
        return Excel::download(new ReportMetlsXProvExport($nominal, $anio), 'DEIT_PASCO SEGUIMIENTO DE METALES PESADOS.xlsx');
    }

    public function printHeavyMetalsDni(Request $request){
        $doc = $request->d;
        $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*') ->where('NUMERO_DOCUMENTO', $doc)
                    ->get();

        return Excel::download(new ReportMetlsXDniExport($nominal), 'DEIT_PASCO SEGUIMIENTO DE METALES PESADOS.xlsx');
    }

    public function indexHomologation() {
        return view('Homologation/index');
    }

    public function searchXMonth(Request $request){
        $mes = $request->id; $doc = $request->doc;
        if($mes == '1'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_01 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_01 AS IPRESS_ATENCION', 'SERVICIO_2022_01 AS SERVICIO',
                        'FECHA_2022_01 AS FECHA_2022', 'RESULTADOS_2022_01 AS RESULTADOS_2022', 'OBSERVACIONES_2022_01 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '2'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_02 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_02 AS IPRESS_ATENCION', 'SERVICIO_2022_02 AS SERVICIO',
                        'FECHA_2022_02 AS FECHA_2022', 'RESULTADOS_2022_02 AS RESULTADOS_2022', 'OBSERVACIONES_2022_02 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '3'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_03 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_03 AS IPRESS_ATENCION', 'SERVICIO_2022_03 AS SERVICIO',
                        'FECHA_2022_03 AS FECHA_2022', 'RESULTADOS_2022_03 AS RESULTADOS_2022', 'OBSERVACIONES_2022_03 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '4'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_04 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_04 AS IPRESS_ATENCION', 'SERVICIO_2022_04 AS SERVICIO',
                        'FECHA_2022_04 AS FECHA_2022', 'RESULTADOS_2022_04 AS RESULTADOS_2022', 'OBSERVACIONES_2022_04 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '5'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_05 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_05 AS IPRESS_ATENCION', 'SERVICIO_2022_05 AS SERVICIO',
                        'FECHA_2022_05 AS FECHA_2022', 'RESULTADOS_2022_05 AS RESULTADOS_2022', 'OBSERVACIONES_2022_05 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '6'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_06 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_06 AS IPRESS_ATENCION', 'SERVICIO_2022_06 AS SERVICIO',
                        'FECHA_2022_06 AS FECHA_2022', 'RESULTADOS_2022_06 AS RESULTADOS_2022', 'OBSERVACIONES_2022_06 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '7'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_07 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_07 AS IPRESS_ATENCION', 'SERVICIO_2022_07 AS SERVICIO',
                        'FECHA_2022_07 AS FECHA_2022', 'RESULTADOS_2022_07 AS RESULTADOS_2022', 'OBSERVACIONES_2022_07 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '8'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_08 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_08 AS IPRESS_ATENCION', 'SERVICIO_2022_08 AS SERVICIO',
                        'FECHA_2022_08 AS FECHA_2022', 'RESULTADOS_2022_08 AS RESULTADOS_2022', 'OBSERVACIONES_2022_08 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '9'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_09 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_09 AS IPRESS_ATENCION', 'SERVICIO_2022_09 AS SERVICIO',
                        'FECHA_2022_09 AS FECHA_2022', 'RESULTADOS_2022_09 AS RESULTADOS_2022', 'OBSERVACIONES_2022_09 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '10'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_10 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_10 AS IPRESS_ATENCION', 'SERVICIO_2022_10 AS SERVICIO',
                        'FECHA_2022_10 AS FECHA_2022', 'RESULTADOS_2022_10 AS RESULTADOS_2022', 'OBSERVACIONES_2022_10 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '11'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_11 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_11 AS IPRESS_ATENCION', 'SERVICIO_2022_11 AS SERVICIO',
                        'FECHA_2022_11 AS FECHA_2022', 'RESULTADOS_2022_11 AS RESULTADOS_2022', 'OBSERVACIONES_2022_11 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }
        if($mes == '12'){
            $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                        ->select('TIPO_DE_INTERVENCION_2022_12 AS TIPO_DE_INTERVENCION', 'IPRESS_ATENCION_2022_12 AS IPRESS_ATENCION', 'SERVICIO_2022_12 AS SERVICIO',
                        'FECHA_2022_12 AS FECHA_2022', 'RESULTADOS_2022_12 AS RESULTADOS_2022', 'OBSERVACIONES_2022_12 AS OBSERVACIONES_2022' )
                        ->where('NUMERO_DOCUMENTO', $doc)
                        ->get();
        }

        return response()->json($nominal);
    }

    public function updateHomologation(Request $request) {
        $id = $request->N;
        // $data = $request()->except(['_token', '_method']);
        // echo $data;
        // echo $request->APELLIDOS_NOMBRES;
        // DB::table('dbo.PADRON_METALES_PESADOS')
        //             ->where('N', $id)
        //             ->update($data);

        // $request->$request->all();
        // $request->save();
        $data = DB::table('dbo.PADRON_METALES_PESADOS') ->where('N', $id)
                ->update(['LENGUA_MATERNA' => $request->LENGUA_MATERNA, 'FECHA_INGRESO_A_PADRON' => $request->FECHA_INGRESO_A_PADRON,
                        'TELEFONO' => $request->TELEFONO, 'PSEUDONIMO_CODIGO' => $request->PSEUDONIMO_CODIGO,
                        'REGION_ANTERIOR' => $request->REGION_ANTERIOR, 'PROVINCIA_ANTERIOR' => $request->PROVINCIA_ANTERIOR,
                        'DISTRITO_ANTERIOR' => $request->DISTRITO_ANTERIOR, 'DIRECCION_ANTERIOR' => $request->DIRECCION_ANTERIOR,
                        'ANIOS_ANTERIOR' => $request->ANIOS_ANTERIOR, 'REGION_ACTUAL' => $request->REGION_ACTUAL,
                        'PROVINCIA_ACTUAL' => $request->PROVINCIA_ACTUAL, 'DISTRITO_ACTUAL' => $request->DISTRITO_ACTUAL,
                        'DIRECCION_ACTUAL' => $request->DIRECCION_ACTUAL, 'ANIOS_ACTUAL' => $request->ANIOS_ACTUAL,
                        'TIPO_DOC_APODERADO' => $request->TIPO_DOC_APODERADO, 'DOCUMENTO_APODERADO' => $request->DOCUMENTO_APODERADO,
                        'NOMBRE_APODERADO' => $request->NOMBRE_APODERADO, 'UPDATED_AT' => now()]);

        // return redirect('/homologation');
    }

    public function createUser(Request $request) {
        $first_name = $request->APELLIDOS_NOMBRES;
        echo $first_name;
        // $data=array('APELLIDOS_NOMBRES'=>$first_name);
        // DB::table('PADRON_METALES_PESADOS')->insert($data);
        // $data = $request()->except(['_token', '_method']);
        // echo $data;
        // echo $request->APELLIDOS_NOMBRES;
        // DB::table('dbo.PADRON_METALES_PESADOS')
        //             ->where('N', $id)
        //             ->update($data);

        // $request->$request->all();
        // $request->save();

        // return redirect('/homologation');
    }

    public function downloadPdf(Request $request){
        $doc = $request->doc;
        $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*') ->where('NUMERO_DOCUMENTO', $doc)
                    ->get();

        $data = [
                        'title' => 'Welcome to Web-Tuts.com',
                        'date' => date('m/d/Y'),
                        'users' => $nominal
        ];

        view()->share('Homologation.pdf', ($data));

        $pdf = PDF::loadView('Homologation.pdf', $data);

        return $pdf->download('Homologation.pdf');
    }

    public function downloaExcel(Request $request){
        $doc = $request->d;
        $nominal = DB::table('dbo.PADRON_METALES_PESADOS')
                    ->select('*') ->where('NUMERO_DOCUMENTO', $doc)
                    ->get();

        return Excel::download(new HomologationExport($nominal), 'DEIT_PASCO SEGUIMIENTO DE METALES PESADOS.xlsx');
    }

    public function indexNewUser() {
        return view('UserNew/index');
    }
}