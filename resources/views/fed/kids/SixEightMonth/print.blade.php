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
            <tr><td colspan="16"></td></tr>
            <tr>
                <td colspan="16" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="16"></td></tr>
            <tr>
                <td colspan="16" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Niños 6 a 8 Meses CG05 - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="16"></td></tr>
            <tr>
                <td colspan="16" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD Padrón Nominal con Fecha {{ $pn }} y BD HisMinsa con fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="16"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">#</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Provincia</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Distrito</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Tipo Doc</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Documento</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Apellidos y Nombres</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Fecha de Nacimiento</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #dae9f3;">Menor Visitado</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #dae9f3;">Menor Encontrado</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #dae9f3;">Tipo de Seguro</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #dae9f3;">PN Último Lugar</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Actividad Establecimiento (HIS)</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Dosaje de Hemoglobina</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">DX Anemia</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Suplementación</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Cumple</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($nomin as $tmz)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $tmz->PROVINCIA }}</td>
                    <td>{{ $tmz->DISTRITO }}</td>
                    <td style="text-align: center;">{{ $tmz->TIPO_DOC }}</td>
                    <td style="text-align: center;">{{ $tmz->DOCUMENTO }}</td>
                    <td>{{ $tmz->APELLIDOS_NOMBRES }}</td>
                    <td style="text-align: center;">{{ $tmz->FECHA_NACIMIENTO_NINO }}</td>
                    <td style="text-align: center;">{{ $tmz->MENOR_VISITADO }}</td>
                    <td style="text-align: center;">{{ $tmz->MENOR_ENCONTRADO }}</td>
                    <td style="text-align: center;">{{ $tmz->TIPO_SEGURO }}</td>
                    <td>{{ $tmz->PN_ULTIMO_LUGAR }}</td>
                    <td>{{ $tmz->ESTAB_ACTIVIDAD }}</td>
                    <td style="text-align: center;">{{ $tmz->HEMOGLOBINA }}</td>
                    <td style="text-align: center;">{{ $tmz->D50X }}</td>
                    <td style="text-align: center;">{{ $tmz->SUPLE }}</td>
                    <td style="text-align: center;">
                        @if ($tmz->MIDE == 'CUMPLE') Cumple
                        @else No Cumple
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>