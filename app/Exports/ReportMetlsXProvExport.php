<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportMetlsXProvExport implements FromView, ShouldAutoSize
{
    protected $nominal;
    protected $anio;


    public function __construct($nominal_f=null, $a)
    {
        $this->nominal=$nominal_f;
        $this->anio=$a;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        $a = $this->anio;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('HeavyMetals.printAll', [ 'prematuros' => $nominal_f, 'anio' => $a ]);
    }
}