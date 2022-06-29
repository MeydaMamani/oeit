<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuplementadosExport implements FromView, ShouldAutoSize
{
    protected $nominal;
    protected $anio;
    protected $nameMonth;
    protected $pn;
    protected $his;

    public function __construct($nominal_f=null, $a, $name, $pn, $his)
    {
        $this->nominal=$nominal_f;
        $this->anio=$a;
        $this->nameMonth=$name;
        $this->pn=$pn;
        $this->his=$his;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        $a = $this->anio;
        $name = $this->nameMonth;
        $pn = $this->pn;
        $his = $this->his;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('fed.kids.FourthMonth.print', [ 'suplementados' => $nominal_f, 'anio' => $a, 'nameMonth' => $name, 'pn' => $pn, 'his' => $his ]);
    }
}