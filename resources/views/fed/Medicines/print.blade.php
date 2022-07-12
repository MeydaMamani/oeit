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
            <tr><td colspan="8"></td></tr>
            <tr>
                <td colspan="8" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="8"></td></tr>
            <tr>
                <td colspan="8" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Cantidad de Profesionales EPP (2020 FED) - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="8"></td></tr>
            <tr>
                <td colspan="8" style="font-size: 10px; color: #999595; font-weight: 500; border: 10px solid #999595;"><b>Fuente: </b> BD HisMinsa con Fecha {{ $his }} a las 08:30 horas</td>
            </tr>
            <tr><td colspan="8"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Código EESS</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Establecimiento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Personal</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Profesional</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($professionals as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->Provincia_Establecimiento }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->Distrito_Establecimiento }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->Codigo_Unico }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->Nombre_Establecimiento }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->Numero_Documento_Personal }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->PERSONAL }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->Descripcion_Profesion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>