<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Document</title>
    {{-- <style type="text/css">
        .cabecera {
            background: #e0eff5; font-weight: 500; text-align: center;
        }
    </style> --}}
</head>
<body>
    <table>
            <tr><td colspan="10"></td></tr>
            <tr>
                <td colspan="10" style="font-size: 18px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">DIRESA PASCO DEIT</td>
            </tr>
            <tr><td colspan="10"></td></tr>
            <tr>
                <td colspan="10" style="font-size: 16px; border: 3px solid #807d7d; font-weight: 500; text-align: center;">Registro de Seguimiento de Personas Expuestas a Metales Pesados, Metaloides y Otras Sustancias Químicas</td>
            </tr>
            <tr><td colspan="10"></td></tr>
            <tr><td colspan="10"></td></tr>
    </table>
    <table>
        @foreach($list as $pr)
            <tr>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Tipo Documento:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO_DOCUMENTO }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Documento:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NUMERO_DOCUMENTO }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Apellidos y Nombres:</th>
                <td style="border: 3px solid #A6A6A6;">{{ $pr->APELLIDOS_NOMBRES }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Fecha Nacimiento:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_NACIMIENTO }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Edad:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->EDAD }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Sexo:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->SEXO }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Región Antigua:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->REGION_ANTERIOR }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Provincia Antigua:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PROVINCIA_ANTERIOR }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Distrito Antigua:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DISTRITO_ANTERIOR }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="background: #963634; color: white; font-weight: 500; border: 3px solid #A6A6A6;">Región Actual:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->REGION_ANTERIOR }}</td>
                <th></th>
                <th style="background: #963634; color: white; font-weight: 500; border: 3px solid #A6A6A6;">Provincia Actual:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->PROVINCIA_ANTERIOR }}</td>
                <th></th>
                <th style="background: #963634; color: white; font-weight: 500; border: 3px solid #A6A6A6;">Distrito Actual:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DISTRITO_ANTERIOR }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Dirección Antigua:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DIRECCION_ANTERIOR }}</td>
                <th></th>
                <th style="background: #963634; color: white; font-weight: 500; border: 3px solid #A6A6A6;">Dirección Actual:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DIRECCION_ANTERIOR }}</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Historia Clínica:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->HISTORIA_CLINICA }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Categoria:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">I</td>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Tipo Caso:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TIPO_CASO }}</td>
                <th></th>
                <th style="background: #963634; color: white; font-weight: 500; border: 3px solid #A6A6A6;">Fecha Ingreso Padrón:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->FECHA_INGRESO_A_PADRON }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="background: #E6B8B7; font-weight: 500; border: 3px solid #A6A6A6;">Vacunación Covid:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #963634; color: white; font-weight: 500; border: 3px solid #A6A6A6;">Tipo Intoxicación:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr>
                <th></th>
                <th rowspan="2" style="text-align: center; color: #366092; font-weight: 500; border: 3px solid #A6A6A6;">DATOS APODERADO</th>
                <th style="background: #366092; color: white; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Documento Apoderado</th>
                <th colspan="2" style="background: #366092; color: white; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Nombres Apoderado</th>
                <th colspan="2" style="background: #366092; color: white; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Teléfono</th>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <td style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->DOCUMENTO_APODERADO }}</td>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->NOMBRE_APODERADO }}</td>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;">{{ $pr->TELEFONO }}</td>
                <th></th>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr>
                <th></th>
                <th rowspan="4" style="background: ; color: #366092; text-align: center;  font-weight: 500; border: 3px solid #A6A6A6;">DATOS CENSOPAS</th>
                <th colspan="2" style="background: #95B3D7; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Pb (µg/dl)</th>
                <th colspan="2" style="background: #95B3D7; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">As (µg/g creatinina)</th>
                <th colspan="2" style="background: #95B3D7; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Cd (µg/g creatinina)</th>
            </tr>
            <tr>
                <th></th>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;">PERFIL HEPATICO</td>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;"></td>
            </tr>
            <tr>
                <th></th>
                <th colspan="2" style="background: #95B3D7; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Hg (µg/g creatinina)</th>
                <th colspan="2" style="background: #95B3D7; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Otros Examenes</th>
            </tr>
            <tr>
                <th></th>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td colspan="2" style="text-align: center; border: 3px solid #A6A6A6;"></td>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr>
                <th></th>
                <th colspan="2" style="background: ; text-align: center; font-weight: 500; border: 3px solid black;">SEGUIMIENTO ENERO</th>
            </tr>
            <tr>
                <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Pb (µg/dl)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
            </tr>
            <tr>
                <th></th>
                <th style="color: #4F6228; font-weight: 500; border: 3px solid #A6A6A6;">ATENCIÓN PRESENCIAL</th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Especializada:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"> 07/02/2022 </td>
                <td style="text-align: center; border: 3px solid #A6A6A6;">JOX</td>
                <td style="text-align: center; border: 3px solid #A6A6A6;">1009 - ULIACHIN</td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">As (µg/g creatinina)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Medicina:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Cd (µg/g creatinina)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Enfermeria:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Hg (µg/g creatinina)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Obstetricia:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"> 15/03/2022 </td>
                <td style="text-align: center; border: 3px solid #A6A6A6;">JOX</td>
                <td style="text-align: center; border: 3px solid #A6A6A6;">1009 - ULIACHIN</td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Otro</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Psicología:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Odontología:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="color: #4F6228; font-weight: 500; border: 3px solid #A6A6A6;">ATENCIÓN TELEMEDICINA</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr>
                <th></th>
                <th colspan="2" style="background: ; text-align: center; font-weight: 500; border: 3px solid black;">SEGUIMIENTO FEBRERO</th>
            </tr>
            <tr>
                <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Pb (µg/dl)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
            </tr>
            <tr>
                <th></th>
                <th style="color: #4F6228; font-weight: 500; border: 3px solid #A6A6A6;">ATENCIÓN PRESENCIAL</th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Especializada:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">As (µg/g creatinina)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Medicina:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Cd (µg/g creatinina)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Enfermeria:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Hg (µg/g creatinina)</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Obstetricia:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
                <th style="background: #FDE9D9; text-align: center; font-weight: 500; border: 3px solid #A6A6A6;">Otro</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Psicología:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr>
                <th></th><th></th>
                <th style="background: #D8E4BC; font-weight: 500; border: 3px solid #A6A6A6;">Odontología:</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
            <tr></tr>
            <tr>
                <th></th>
                <th style="color: #4F6228; font-weight: 500; border: 3px solid #A6A6A6;">ATENCIÓN TELEMEDICINA</th>
                <td style="text-align: center; border: 3px solid #A6A6A6;"></td>
                <th></th>
            </tr>
        @endforeach
    </table>
</body>
</html>