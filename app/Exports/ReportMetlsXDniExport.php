<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportMetlsXDniExport implements FromView, ShouldAutoSize
{
    protected $nominal;

    public function __construct($nominal_f=null)
    {
        $this->nominal=$nominal_f;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('HeavyMetals.printDoc', [ 'list' => $nominal_f ]);
    }
}