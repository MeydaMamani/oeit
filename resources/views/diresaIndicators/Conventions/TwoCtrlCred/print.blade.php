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
            <tr><td colspan="10"></td></tr>
            <tr>
                <td colspan="10" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="10"></td></tr>
            <tr>
                <td colspan="10" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Dos Controles Cred - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="10"></td></tr>
            <tr><td colspan="10"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Periodo</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Ubigeo</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Nacimiento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Seguro</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Cred 1</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Cred 2</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($ctrlcred as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PERIODO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->UBIGEO_RESIDENCIA }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->PROVINCIA }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->DISTRITO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DNI }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_NAC }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEGURO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_CRED1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_CRED2 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>