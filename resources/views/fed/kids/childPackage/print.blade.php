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
            <tr><td colspan="46"></td></tr>
            <tr>
                <td colspan="46" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="46"></td></tr>
            <tr>
                {{-- @if ($type == 'indicator')
                    <td colspan="46" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Paquete Niño - {{ $nameMonth }} {{ $anio }} </td>
                @else
                    <td colspan="46" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Paquete Niño Avance Mensual - {{ $nameMonth }} {{ $anio }} </td>
                @endif --}}
            </tr>
            <tr><td colspan="46"></td></tr>
            <tr>
                <td colspan="46" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD Padrón Nominal con Fecha {{ $pn }} y BD HisMinsa con fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="46"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Apellidos y Nombres</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Nacimiento</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tipo Seguro</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Prematuro</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">1er Contrl</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">2do Contrl</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">3er Contrl</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">4to Contrl</th>
                <th style="background: #F8CBAD; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Max Días</th>
                <th style="background: #F8CBAD; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Diferencia</th>
                <th style="background: #99CCFF; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cumple Cntrl Mes</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 1</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 2</th>
                <th style="background: #FCE4D6; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Neumococica 1</th>
                <th style="background: #E2EFDA; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Rotavirus 1</th>
                <th style="background: #FFE699; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Antipolio 1</th>
                <th style="background: #DDEBF7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Pentavalente 1</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 3</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 4</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 4</th>
                <th style="background: #FCE4D6; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Neumococica 2</th>
                <th style="background: #E2EFDA; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Rotavirus 2</th>
                <th style="background: #DDEBF7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Pentavalente 2</th>
                <th style="background: #FFE699; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Antipolio 2</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 5</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 5</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 6</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tamizaje</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Dx Anemia</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 6</th>
                <th style="background: #FFE699; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Antipolio 3</th>
                <th style="background: #DDEBF7; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Pentavalente 3</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 7</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 7</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 8</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 8</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 9</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 9</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 10</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 10</th>
                <th style="background: #c6deef; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Cred 11</th>
                <th style="background: white; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suple 11</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($childPackage as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_PROV }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_DIST }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DOCUMENTO }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->APELLIDOS_NOMBRES }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_NACIMIENTO_NINO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO_SEGURO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PREMATURO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PRIMER_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEG_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TER_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CUAR_CNTRL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->MAXDIAS }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DIFERENCIA }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CUMPLE }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NEUMOCOCICA1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ROTAVIRUS1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ANTIPOLIO1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PENTAVALENTE1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED3 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED4 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE4 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NEUMOCOCICA2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ROTAVIRUS2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PENTAVALENTE2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ANTIPOLIO2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED5 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE5 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED6 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TAMIZAJE }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DXANEMIA }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE6 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ANTIPOLIO3 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PENTAVALENTE3 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED7 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE7 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED8 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE8 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED9 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE9 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED10 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE10 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CRED11 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLE11 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>