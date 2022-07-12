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
            <tr><td colspan="12"></td></tr>
            <tr>
                <td colspan="12" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="12"></td></tr>
            <tr>
                <td colspan="12" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Sis Covid - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="12"></td></tr>
            <tr>
                <td colspan="12" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD HisMinsa con Fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="12"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Establecimiento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Ejecución Prueba</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Registro</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tipo</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Ficha 300</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Receta</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Entrega</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cumple</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($sis_covid as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->DESC_PROV }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->DESC_DIST }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->ESTABLECIMIENTO_EJECUTA }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NUMERO_DOCUMENTO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_EJECUCION_PRUEBA }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_REGISTRO2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FICHA_300_FECHA_DEL_SEGUIMIENTO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_RECETA }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_ENTREGA }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">
                        @if ($pr->FED == 'CUMPLE') Cumplen
                        @else No Cumplen
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>