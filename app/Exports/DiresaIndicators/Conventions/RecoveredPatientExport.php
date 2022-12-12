<?php

namespace App\Exports\DiresaIndicators\Conventions;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RecoveredPatientExport implements FromView, ShouldAutoSize
{
    protected $nominal;
    protected $anio;
    protected $nameMonth;

    public function __construct($nominal_f=null, $a, $name)
    {
        $this->nominal=$nominal_f;
        $this->anio=$a;
        $this->nameMonth=$name;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        $a = $this->anio;
        $name = $this->nameMonth;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('diresaIndicators.Conventions.RecoveredPatient.print', [ 'patient' => $nominal_f, 'anio' => $a, 'nameMonth' => $name ]);
    }
}