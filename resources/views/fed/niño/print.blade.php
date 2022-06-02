<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style type="text/css">
        .cabecera {
            background: #e0eff5; font-weight: 500; text-align: center;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td rowspan="3" colspan="2" style="text-align: center;">
                    <img rowspan="3" colspan="2" src="./img/oeit.png" width="150" alt="icon cantidad total">
                </td>
                <td colspan="10" style="font-size: 20px; border: 1px solid #3A3838; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
                <td rowspan="3" colspan="1" style="text-align: center;"><img src="./img/diresa1.jpg" width="90" alt="icon cantidad total" style="position: absolute;"></td>
            </tr>
            <tr></tr>
            <tr>
                <td colspan="10" style="font-size: 18px; border: 1px solid #3A3838; font-weight: 500; text-align: center;">Niños Prematuros CG03</td>
            </tr>
            <tr></tr>
            <tr>
                <td colspan="13" style="font-size: 10px; color: #999595; font-weight: 500; border: 1px solid #ddd;"><b>Fuente: </b> BD Padrón Nominal con Fecha a las 08:30 horas</td>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">#</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Provincia</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Distrito</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Establecimiento</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Tipo Documento</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Documento</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Apellidos y Nombres</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Fecha Nacido</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Tipo Seguro</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Menor Visitado</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Suplementado</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Prematuro</th>
                <th style="background: #e0eff5; font-weight: 500; text-align: center;">Se Atiende</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($prematuros as $pr)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $pr->NOMBRE_PROV }}</td>
                    <td>{{ $pr->NOMBRE_DIST }}</td>
                    <td>{{ $pr->NOMBRE_EESS }}</td>
                    <td style="text-align: center;">{{ $pr->Tipo_Doc_Paciente }}</td>
                    <td style="text-align: center;">{{ $pr->CNV_O_DNI }}</td>
                    <td>{{ $pr->full_name }}</td>
                    <td style="text-align: center;">{{ $pr->FECHA_NACIMIENTO_NINO }}</td>
                    <td style="text-align: center;">{{ $pr->TIPO_SEGURO }}</td>
                    <td style="text-align: center;">{{ $pr->MENOR_VISITADO }}</td>
                    <td style="text-align: center;">{{ $pr->SUPLEMENTADO }}</td>
                    <td style="text-align: center;">{{ $pr->BAJO_PESO_PREMATURO }}</td>
                    <td style="text-align: center;">{{ $pr->Establecimiento }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>