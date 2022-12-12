<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProfessionalsExport implements FromView, ShouldAutoSize
{
    protected $nominal;
    protected $anio;
    protected $nameMonth;
    protected $his;

    public function __construct($nominal_f=null, $a, $name, $his)
    {
        $this->nominal=$nominal_f;
        $this->anio=$a;
        $this->nameMonth=$name;
        $this->his=$his;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        $a = $this->anio;
        $name = $this->nameMonth;
        $his = $this->his;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('fed.Medicines.print', [ 'professionals' => $nominal_f, 'anio' => $a, 'nameMonth' => $name, 'his' => $his ]);
    }
}