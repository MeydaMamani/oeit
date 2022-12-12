<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ChildPackageExport implements FromView, ShouldAutoSize
{
    protected $nominal;
    protected $anio;
    protected $nameMonth;
    protected $his;
    protected $pn;

    public function __construct($nominal_f=null, $a, $name, $his, $pn)
    {
        $this->nominal=$nominal_f;
        $this->anio=$a;
        $this->nameMonth=$name;
        $this->his=$his;
        $this->pn=$pn;
    }

    public function view(): View {
        $nominal_f = $this->nominal;
        $a = $this->anio;
        $name = $this->nameMonth;
        $his = $this->his;
        $pn = $this->pn;
        // return view("facturas.ajax-product",compact("nominal_factura"));
        return view('fed.kids.childPackage.print', [ 'childPackage' => $nominal_f, 'anio' => $a, 'nameMonth' => $name, 'his' => $his, 'pn' => $pn ]);
    }
}