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
            <tr><td colspan="13"></td></tr>
            <tr>
                <td colspan="13" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="13"></td></tr>
            <tr>
                <td colspan="13" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Tamizaje Neonatal CG02 - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="13"></td></tr>
            <tr>
                <td colspan="13" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD Padrón Nominal con Fecha {{ $pn }} y BD HisMinsa con fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="13"></td></tr>
        </thead>
        <thead>
            <tr class="text-center font-13 border">
                <th class="border" style="border: 1px solid #DDDDDD; font-size: 15px;"></th>
                <th colspan="9" class="border" style="font-weight: 500; background: #AED6F1; border: 1px solid #DDDDDD; text-align: center; font-size: 11px;">Padrón Nominal</th>
                <th colspan="3" class="border" style="font-weight: 500; background: #AED6F1; border: 1px solid #DDDDDD; text-align: center; font-size: 11px;">His Minsa</th>
            </tr>
            <tr>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">#</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Provincia</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Distrito</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Documento</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Apellidos y Nombres</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Fecha de Nacimiento</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Nombre ESS</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #dae9f3;">Menor Encontrado</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #dae9f3;">Tipo de Seguro</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #91b9d5;">Nombre ESS Nacimiento</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Lugar de Tamizaje (HIS)</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Fecha Atención</th>
                <th style="text-align: center; border: 1px solid #DDDDDD; font-weight: 500; font-size: 11px; background: #AED6F1;">Cumple</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($tamizados as $tmz)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $tmz->NOMBRE_PROV }}</td>
                    <td>{{ $tmz->NOMBRE_DIST }}</td>
                    <td style="text-align: center;">{{ $tmz->CNV_O_DNI }}</td>
                    <td>{{ $tmz->apellidos_nino }}</td>
                    <td style="text-align: center;">{{ $tmz->FECHA_NACIMIENTO_NINO }}</td>
                    <td>{{ $tmz->NOMBRE_EESS }}</td>
                    <td style="text-align: center;">{{ $tmz->MENOR_ENCONTRADO }}</td>
                    <td style="text-align: center;">{{ $tmz->TIPO_SEGURO }}</td>
                    <td>{{ $tmz->NOMBRE_EESS_NACIMIENTO }}</td>
                    <td style="text-align: center;">{{ $tmz->Lugar_TMZ }}</td>
                    <td style="text-align: center;">{{ $tmz->FECHA_ATENCION }}</td>
                    <td style="text-align: center;">
                        @if ($tmz->TAMIZADO == 'SI') Cumple
                        @else No Cumple
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>