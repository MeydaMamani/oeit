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
                <td colspan="13" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Niños Prematuros CG03 - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="13"></td></tr>
            <tr>
                <td colspan="13" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD Padrón Nominal con Fecha {{ $pn }} y BD CNV con fecha {{ $cnv }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="13"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Establecimiento</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tipo Documento</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Apellidos y Nombres</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Nacido</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Tipo Seguro</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Menor Visitado</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Suplementado</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Prematuro</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Se Atiende</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($prematuros as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_PROV }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_DIST }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_EESS }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">
                        @if ($pr->Tipo_Doc_Paciente == 1) DNI
                        @elseif ($pr->Tipo_Doc_Paciente == 2) CE
                        @elseif ($pr->Tipo_Doc_Paciente == 3) PASS
                        @elseif ($pr->Tipo_Doc_Paciente == 4) DIE
                        @elseif ($pr->Tipo_Doc_Paciente == 5) SIN DOCUMENTO
                        @elseif ($pr->Tipo_Doc_Paciente == 6) CNV
                        @else -
                        @endif
                    </td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->CNV_O_DNI }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->full_name }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_NACIMIENTO_NINO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO_SEGURO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->MENOR_VISITADO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SUPLEMENTADO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->BAJO_PESO_PREMATURO }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->Establecimiento }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>