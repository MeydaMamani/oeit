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
            <tr><td colspan="27"></td></tr>
            <tr>
                <td colspan="27" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="27"></td></tr>
            <tr>
                <td colspan="27" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Cred Mensual - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="27"></td></tr>
            <tr>
                <td colspan="27" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD Padrón Nominal con Fecha {{ $pn }} y BD HisMinsa con fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="27"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Menor Encontrado</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Apellidos y Nombres</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tipo Seguro</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Nacimiento</th>
                <th style="background: #f1f1c0; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Primer Control</th>
                <th style="background: #f1f1c0; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Segundo Control</th>
                <th style="background: #f1f1c0; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tercer Control</th>
                <th style="background: #f1f1c0; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cuarto Control</th>
                <th style="background: #F0B0AF; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Días 1er Cntrol (PC - FN)</th>
                <th style="background: #F0B0AF; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Días 2do Cntrol (SC - FN)</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cumple Control Mes</th>
                <th style="background: #f0dfc7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Primer Control Mes</th>
                <th style="background: #f0dfc7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Segundo Control Mes</th>
                <th style="background: #f0dfc7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tercer Control Mes</th>
                <th style="background: #f0dfc7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cuarto Control Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Quinto Control Mes</th>
                <th style="background: #f0dfc7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Sexto Control Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Séptimo Control Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Octavo Control Mes</th>
                <th style="background: #f0dfc7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Noveno Control Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Décimo Control Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Onceavo Control Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cumple</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($credMensual as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_PROV }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_DIST }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->MENOR_ENCONTRADO }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->APELLIDOS_NOMBRES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DOCUMENTO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO_SEGURO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_NACIMIENTO_NINO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PRIMER_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEG_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TERCER_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CUARTO_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DIA1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DIA2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CUMPLE_CTRLMES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PRIMER_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEGUNDO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TERCER_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CUARTO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->QUINTO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEXTO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEPTIMO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->OCTAVO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NOVENO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DECIMO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ONCEAVO_CNTRL_MES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">
                        @if ($pr->CUMPLE == 'CUMPLE') Cumplen
                        @else No Cumplen
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>