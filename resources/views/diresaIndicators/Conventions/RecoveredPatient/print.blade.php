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
            <tr><td colspan="21"></td></tr>
            <tr>
                <td colspan="21" style="font-size: 20px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="21"></td></tr>
            <tr>
                <td colspan="21" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Pacientes Recuperados - {{ $nameMonth }} {{ $anio }} </td>
            </tr>
            <tr><td colspan="21"></td></tr>
            <tr><td colspan="21"></td></tr>
        </thead>
        <thead>
            <tr>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">#</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Provincia</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Distrito</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Establecimiento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Documento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Nacimiento</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Seguro</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Ubigeo</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Año</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Mes</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Dx</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Denominador</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Numerador</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha T1</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num T1</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha T2</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num T2</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Recup</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num Recup</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Fecha Dosaje</th>
                <th style="background: #c9d0e2; font-weight: 500; text-align: center; border: 3px solid #A6A6A6;">Num Dosaje</th>
            </tr>
        </thead>
        <tbody>
            {{-- <td><img src="{{URL::asset('/images/avartar.png')}}" /></td> --}}
            @foreach($patient as $pr)
                <tr>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $loop->iteration }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->Provincia_Establecimiento }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->Distrito_Establecimiento }}</td>
                    <td style="border: 3px solid #A6A6A6;">{{ $pr->Nombre_Establecimiento }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->num_doc }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->fecha_nac }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->seguro }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->ubigeo }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->año }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->mes }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->fecha_dx }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->den }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->num }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->fecha_t1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->num_t1 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->fecha_t2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->num_t2 }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->fecha_recup }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->num_recup }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->fecha_dosaje }}</td>
                    <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->num_dosaje }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>