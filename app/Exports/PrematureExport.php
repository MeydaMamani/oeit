<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PrematureExport implements FromView, ShouldAutoSize
{
    protected $nominal;
    protected $anio;
    protected $nameMonth;
    protected $pn;
    protected $cnv;

    public function __construct($nominal_f=null, $a, $name, $pn, $cnv)
    {
        $this->nominal=$nominal_f;
        $this->anio=$a;
        $this->nameMonth=$name;
        $this->pn=$pn;
        $this->cnv=$cnv;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        $a = $this->anio;
        $name = $this->nameMonth;
        $pn = $this->pn;
        $cnv = $this->cnv;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('fed.kids.Premature.print', [ 'prematuros' => $nominal_f, 'anio' => $a, 'nameMonth' => $name, 'pn' => $pn, 'cnv' => $cnv ]);
    }
}