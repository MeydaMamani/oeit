<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;
use App\Exports\FlatFileExport;


class TracingController extends Controller
{
    public function indexfilePlane() {
        return view('tracing/FlatFile/index');
    }

    public function printfilePlane(Request $request){
        $red_1 = $request->r;
        $dist = $request->d;
        $estab = $request->e;
        $anio = $request->a;
        $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        $nominal = DB::table('dbo.COMSOL_ARCHIVO_PLANO')
                    ->where('anio', '2022') -> where('mes', '4')
                    ->where('Nombre_Establecimiento', 'ULIACHIN')
                    ->get();

        return Excel::download(new FlatFileExport($nominal, $anio), 'DEIT_PASCO ARCHIVO PLANO.xlsx');
    }

    public function indexDetailPatient() {
        return view('tracing/PatientDetail/index');
    }
}