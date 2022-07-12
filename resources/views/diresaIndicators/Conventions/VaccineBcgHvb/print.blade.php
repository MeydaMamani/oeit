<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- <style type="text/css">
        .cabecera {
            background: #e0eff5; font-weight: 500; text-align: center;
        }
    </style> --}}
</head>
<body>
    <table>
        <thead>
            <tr><td colspan="14"></td></tr>
            <tr>
                <td colspan="14" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="14"></td></tr>
            <tr>
                <td colspan="14" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Vacunas Bcg Hvb - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="14"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Periodo</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Ubigeo</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Id EESS</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Establecimiento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Categoría</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Nacido</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num Hvb</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num Bcg</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Den</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($bcgHvb as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PERIODO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->UBIGEO }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->PROVINCIA }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->DISTRITO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ID_EESS }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->EESS_NOMBRE }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->Categoria }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NUM_DOC }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FE_NACIDO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NUM_HVB }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NUM_BCG }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DEN }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NUM }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>