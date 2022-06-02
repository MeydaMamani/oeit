<?php

namespace App\Exports;

use App\Models\User;
// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// class UsersExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         return User::all();
//     }
// }
class UsersExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $nominal = DB::table('dbo.CONSOLIDADO_PREMATURO')
                    ->where('CORTE_PADRON', '202205') ->where('PERIODO_MEDICION', '2022-5') ->where('BAJO_PESO_PREMATURO', 'SI')
                    ->orderBy('NOMBRE_PROV', 'ASC') ->orderBy('NOMBRE_DIST', 'ASC') ->orderBy('NOMBRE_EESS', 'ASC')
                    ->get();

        return view('fed.niño.print', [ 'prematuros' => $nominal ]);
    }
}