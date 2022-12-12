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
                <td colspan="13" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Niños 4 Meses CG04 - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="13"></td></tr>
            <tr>
                <td colspan="13" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD Padrón Nominal con Fecha {{ $pn }} y BD HisMinsa con fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="13"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Establecimiento</th>
                <th style="background: #BDD7EE; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Menor Visitado</th>
                <th style="background: #BDD7EE; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Menor Encontrado</th>
                <th style="background: #BDD7EE; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Fecha Nacimiento</th>
                <th style="background: #BDD7EE; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Tipo Seguro</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Apellidos y Nombres</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Suplementado</th>
                <th style="background: #BDD7EE; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Ultima Ate PN</th>
                <th style="background: #c9d0e2; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Cumple</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($suplementados as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_PROV }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_DIST }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->EESS_ATENCION }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->MENOR_VISITADO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->MENOR_ENCONTRADO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CNV_O_DNI }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_NACIMIENTO_NINO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO_SEGURO }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->APELLIDOS_NOMBRES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLEMENTADO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ULTIMA_ATE_PN }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">
                        @if ($pr->SUPLEMENTADO == 'SI') Cumple
                        @else No Cumple
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>