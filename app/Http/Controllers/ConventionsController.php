<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;

use App\Exports\DiresaIndicators\Conventions\VaccineBcgHvbExport;
use App\Exports\DiresaIndicators\Conventions\RecoveredPatientExport;
use App\Exports\DiresaIndicators\Conventions\TwoCtrlCredExport;

class ConventionsController extends Controller
{
    public function index(Request $request) {
        return view('diresaIndicators/Conventions/index');
    }

    public function listVaccineBcgHvb(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($mes == 'TODOS'){
            $query = DB::statement("DECLARE @MES_INICIO INT, @MES_FINAL INT, @YEAR INT, @FECHA_FIN DATE
                    SET @MES_INICIO='1'
                    SET @MES_FINAL=12
                    SET @YEAR=".$anio."
                    SET @fecha_fin= DATEADD(DD,0,DATEADD(MONTH,1,CONVERT(DATE,try_convert(varchar(4) ,@YEAR) + '-'+try_convert(varchar(2),@MES_FINAL)+'-'+try_convert(varchar(2),'01'))))

                    SELECT NU_CNV AS NUM_DOC,MONTH(CONVERT(DATE,FE_NACIDO)) MES,YEAR(CONVERT(DATE,FE_NACIDO)) ANIO, CONVERT(DATE,FE_NACIDO)FE_NACIDO, CO_LOCAL,Ubigeo_LugarNacido AS
                    UBIGEO,DPTO_EESS,PROV_EESS,DIST_EESS,Nombre_EESS as EESS_NOMBRE, Lugar_Nacido,Categoria,Institucion AS Sector
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                    FROM  BD_CNV.DBO.CNV_LUGARNACIDO_PASCO with (nolock)
                    WHERE MONTH(CONVERT(DATE,FE_NACIDO))>=@MES_INICIO AND MONTH(CONVERT(DATE,FE_NACIDO))<=@MES_FINAL AND YEAR (CONVERT (DATE, FE_NACIDO))=@YEAR AND
                    TRY_CONVERT(INT,PESO_NACIDO)>=2000 AND Lugar_Nacido='ESTABLECIMIENTO DE SALUD' AND Institucion='GOBIERNO REGIONAL' AND
                    Categoria IN ('II-1','II-2','II-E','III-1', 'III-E ','III-2')

                    SELECT ID_CITA,A.Fecha_Nacimiento_Paciente,CONVERT(DATE,fecha_atencion) FECHA_ATENDIDO, B.Numero_Documento_Paciente AS NUM_DOC, A.Id_Establecimiento,tipo_diagnostico,Codigo_Item, valor_lab
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                    FROM bdhis_minsa.dbo.t_consolidado_nueva_trama_hisminsa A
                    LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_PACIENTE B ON A.Id_Paciente=B.Id_Paciente
                    LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_HIS_TIPO_DOC C ON B.Id_Tipo_Documento_Paciente=C.Id_Tipo_Documento
                    WHERE Codigo_Item IN ('90585','90744') and B.Id_Tipo_Documento_Paciente in (1,6) and YEAR(CONVERT(DATE,fecha_atencion))=@YEAR AND
                    CONVERT(DATE,fecha_atencion)<=@fecha_fin

                    --2. Estableciente la ubicación de residencia según CNV y Partos en Establecimientos del MINSA Y REGIONALES. (NIVEL: HOSPITAL)
                    SELECT NUM_DOC, MES, ANIO, FE_NACIDO, UBIGEO, DPTO_EESS as Departamento, PROV_EESS AS Provincia, DIST_EESS AS Distrito, C.Red,
                    C.id_establecimiento ID_EESS, Sector, Categoria, EESS_NOMBRE, Lugar_Nacido, DEN=1
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB A
                    INNER jOIN BDHIS_MINSA.dbo.MAESTRO_HIS_ESTABLECIMIENTO C ON CONVERT(INT,A.CO_LOCAL)=CONVERT(INT,C.Codigo_Unico)

                    --===================================
                    --ATENDIDOS
                    --===================================
                    --UNION DE BASE DE HIS Y CNV
                    SELECT A.*, b. FECHA_ATENDIDO, b.id_cita, b.Tipo_Diagnostico, b.Codigo_Item, b. VALOR_LAB
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB A
                    LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB B ON CONVERT(INT, A. ID_EESS)=CONVERT(INT,B.Id_Establecimiento) AND A.NUM_DOC=B.num_doc

                    -- INDICADORES
                    --1. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA BCG DENTRO DE LAS 24 HORAS
                    SELECT *, MAX(TEMP_BCG) OVER (PARTITION BY num_doc) NUM_BCG
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                    FROM (
                            SELECT *,IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (
                            DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                            AND CODIGO_ITEM IN ('90585'),1,0) TEMP_BCG
                            FROM BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                    ) AS T

                    --2. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA HVB DENTRO DE LAS 24 HORAS
                    SELECT *, MAX(TEMP_HVB) OVER (PARTITION BY num_doc) NUM_HVB
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                    FROM (SELECT *, IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                    AND CODIGO_ITEM IN ('90744'),1,0) TEMP_HVB FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG ) AS T

                    --REPORTE
                    SELECT ANIO, CONCAT(ANIO,'-',MES)PERIODO, UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,
                    NUM_DOC,FE_NACIDO, MAX(DEN) DEN, MAX(NUM_HVB) NUM_HVB, MAX(NUM_BCG) NUM_BCG
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                    GROUP BY ANIO, MES,UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,num_doc,FE_NACIDO");
        }else{
            $query = DB::statement("DECLARE @MES_INICIO INT, @MES_FINAL INT, @YEAR INT, @FECHA_FIN DATE
                        SET @MES_INICIO=".$mes."
                        SET @MES_FINAL=".$mes."
                        SET @YEAR=".$anio."
                        SET @fecha_fin= DATEADD(DD,0,DATEADD(MONTH,1,CONVERT(DATE,try_convert(varchar(4) ,@YEAR) + '-'+try_convert(varchar(2),@MES_FINAL)+'-'+try_convert(varchar(2),'01'))))

                        SELECT NU_CNV AS NUM_DOC,MONTH(CONVERT(DATE,FE_NACIDO)) MES,YEAR(CONVERT(DATE,FE_NACIDO)) ANIO, CONVERT(DATE,FE_NACIDO)FE_NACIDO, CO_LOCAL,Ubigeo_LugarNacido AS
                        UBIGEO,DPTO_EESS,PROV_EESS,DIST_EESS,Nombre_EESS as EESS_NOMBRE, Lugar_Nacido,Categoria,Institucion AS Sector
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                        FROM  BD_CNV.DBO.CNV_LUGARNACIDO_PASCO with (nolock)
                        WHERE MONTH(CONVERT(DATE,FE_NACIDO))>=@MES_INICIO AND MONTH(CONVERT(DATE,FE_NACIDO))<=@MES_FINAL AND YEAR (CONVERT (DATE, FE_NACIDO))=@YEAR AND
                        TRY_CONVERT(INT,PESO_NACIDO)>=2000 AND Lugar_Nacido='ESTABLECIMIENTO DE SALUD' AND Institucion='GOBIERNO REGIONAL' AND
                        Categoria IN ('II-1','II-2','II-E','III-1', 'III-E ','III-2')

                        SELECT ID_CITA,A.Fecha_Nacimiento_Paciente,CONVERT(DATE,fecha_atencion) FECHA_ATENDIDO, B.Numero_Documento_Paciente AS NUM_DOC, A.Id_Establecimiento,tipo_diagnostico,Codigo_Item, valor_lab
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                        FROM bdhis_minsa.dbo.t_consolidado_nueva_trama_hisminsa A
                        LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_PACIENTE B ON A.Id_Paciente=B.Id_Paciente
                        LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_HIS_TIPO_DOC C ON B.Id_Tipo_Documento_Paciente=C.Id_Tipo_Documento
                        WHERE Codigo_Item IN ('90585','90744') and B.Id_Tipo_Documento_Paciente in (1,6) and YEAR(CONVERT(DATE,fecha_atencion))=@YEAR AND
                        CONVERT(DATE,fecha_atencion)<=@fecha_fin

                        --2. Estableciente la ubicación de residencia según CNV y Partos en Establecimientos del MINSA Y REGIONALES. (NIVEL: HOSPITAL)
                        SELECT NUM_DOC, MES, ANIO, FE_NACIDO, UBIGEO, DPTO_EESS as Departamento, PROV_EESS AS Provincia, DIST_EESS AS Distrito, C.Red,
                        C.id_establecimiento ID_EESS, Sector, Categoria, EESS_NOMBRE, Lugar_Nacido, DEN=1
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB A
                        INNER jOIN BDHIS_MINSA.dbo.MAESTRO_HIS_ESTABLECIMIENTO C ON CONVERT(INT,A.CO_LOCAL)=CONVERT(INT,C.Codigo_Unico)

                        --===================================
                        --ATENDIDOS
                        --===================================
                        --UNION DE BASE DE HIS Y CNV
                        SELECT A.*, b. FECHA_ATENDIDO, b.id_cita, b.Tipo_Diagnostico, b.Codigo_Item, b. VALOR_LAB
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB A
                        LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB B ON CONVERT(INT, A. ID_EESS)=CONVERT(INT,B.Id_Establecimiento) AND A.NUM_DOC=B.num_doc

                        -- INDICADORES
                        --1. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA BCG DENTRO DE LAS 24 HORAS
                        SELECT *, MAX(TEMP_BCG) OVER (PARTITION BY num_doc) NUM_BCG
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                        FROM (
                                SELECT *,IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (
                                DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                                AND CODIGO_ITEM IN ('90585'),1,0) TEMP_BCG
                                FROM BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                        ) AS T

                        --2. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA HVB DENTRO DE LAS 24 HORAS
                        SELECT *, MAX(TEMP_HVB) OVER (PARTITION BY num_doc) NUM_HVB
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                        FROM (SELECT *, IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                        AND CODIGO_ITEM IN ('90744'),1,0) TEMP_HVB FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG ) AS T

                        --REPORTE
                        SELECT ANIO, CONCAT(ANIO,'-',MES)PERIODO, UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,
                        NUM_DOC,FE_NACIDO, MAX(DEN) DEN, MAX(NUM_HVB) NUM_HVB, MAX(NUM_BCG) NUM_BCG
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                        GROUP BY ANIO, MES,UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,num_doc,FE_NACIDO");
        }

        $query1 = DB::statement("SELECT *, IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM
                                INTO BDHIS_MINSA_EXTERNO_V2.DBO.ID_FICHA_06_NOMINAL
                                FROM BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB");

        $query2 = DB::statement("SELECT ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS
                ,SUM(DEN) DENOMINADOR, SUM(NUM) NUMERADOR, SUM(NUM_HVB) NUM_HVB, SUM(NUM_BCG ) NUM_BCG
                INTO BDHIS_MINSA_EXTERNO_V2.DBO.FICHA_RESUME_HCB_BCG
                FROM BDHIS_MINSA_EXTERNO_V2.DBO.ID_FICHA_06_NOMINAL
                GROUP BY ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                    ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM")) ->orderBy('PERIODO', 'ASC')
                    ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                    ->get();

            $t_resume = DB::table('dbo.ID_FICHA_06_NOMINAL')
                        ->select('ANIO', 'PERIODO', 'UBIGEO', 'Departamento', 'PROVINCIA', 'DISTRITO', 'RED', 'Categoria', 'EESS_NOMBRE', 'ID_EESS',
                         DB::raw("SUM(DEN) DENOMINADOR, SUM(NUM) NUMERADOR, SUM(NUM_HVB) NUM_HVB, SUM(NUM_BCG ) NUM_BCG"))
                        ->groupByRaw('ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS')
                        ->orderBy('PERIODO', 'ASC') ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();

            $resum_red = DB::table('dbo.FICHA_RESUME_HCB_BCG')
                        ->select('PROVINCIA', DB::raw("SUM(DENOMINADOR) DENOMINADOR, SUM(NUMERADOR) NUMERADOR,
                            CASE WHEN (SUM(DENOMINADOR) = 0) THEN 0 ELSE round((cast(SUM(NUMERADOR) as float) / cast(SUM(DENOMINADOR) as float) * 100), 0) END 'AVANCE'"))
                        ->groupByRaw('PROVINCIA')
                        ->get();

        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                        ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM"))
                        ->where('PROVINCIA', $red) ->orderBy('PERIODO', 'ASC')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();

            $t_resume = DB::table('dbo.ID_FICHA_06_NOMINAL')
                        ->select('ANIO', 'PERIODO', 'UBIGEO', 'Departamento', 'PROVINCIA', 'DISTRITO', 'RED', 'Categoria', 'EESS_NOMBRE', 'ID_EESS',
                        DB::raw("SUM(DEN) DENOMINADOR, SUM(NUM) NUMERADOR, SUM(NUM_HVB) NUM_HVB, SUM(NUM_BCG ) NUM_BCG"))
                        ->where('PROVINCIA', $red)
                        ->groupByRaw('ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS')
                        ->orderBy('PERIODO', 'ASC') ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();

            $resum_red = DB::table('dbo.FICHA_RESUME_HCB_BCG')
                        ->select('PROVINCIA', DB::raw("SUM(DENOMINADOR) DENOMINADOR, SUM(NUMERADOR) NUMERADOR,
                            CASE WHEN (SUM(DENOMINADOR) = 0) THEN 0 ELSE round((cast(SUM(NUMERADOR) as float) / cast(SUM(DENOMINADOR) as float) * 100), 0) END 'AVANCE'"))
                        ->where('PROVINCIA', $red) ->groupByRaw('PROVINCIA')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                        ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM"))
                        ->where('DISTRITO', $dist) ->orderBy('PERIODO', 'ASC')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();

            $t_resume = DB::table('dbo.ID_FICHA_06_NOMINAL')
                        ->select('ANIO', 'PERIODO', 'UBIGEO', 'Departamento', 'PROVINCIA', 'DISTRITO', 'RED', 'Categoria', 'EESS_NOMBRE', 'ID_EESS',
                        DB::raw("SUM(DEN) DENOMINADOR, SUM(NUM) NUMERADOR, SUM(NUM_HVB) NUM_HVB, SUM(NUM_BCG ) NUM_BCG"))
                        ->where('DISTRITO', $dist)
                        ->groupByRaw('ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS')
                        ->orderBy('PERIODO', 'ASC') ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();

            $resum_red = DB::table('dbo.FICHA_RESUME_HCB_BCG')
                        ->select('PROVINCIA', DB::raw("SUM(DENOMINADOR) DENOMINADOR, SUM(NUMERADOR) NUMERADOR,
                            CASE WHEN (SUM(DENOMINADOR) = 0) THEN 0 ELSE round((cast(SUM(NUMERADOR) as float) / cast(SUM(DENOMINADOR) as float) * 100), 0) END 'AVANCE'"))
                        ->where('DISTRITO', $dist) ->groupByRaw('PROVINCIA')
                        ->get();
        }

        $query3 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.DBO.ID_FICHA_06_NOMINAL
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.DBO.FICHA_RESUME_HCB_BCG"));

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }

    public function printVaccineBcgHvb(Request $request){
        $red_1 = $request->r; $dist = $request->d; $anio = $request->a; $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($mes == 'TODOS'){
            $query = DB::statement("DECLARE @MES_INICIO INT, @MES_FINAL INT, @YEAR INT, @FECHA_FIN DATE
                    SET @MES_INICIO='1'
                    SET @MES_FINAL=12
                    SET @YEAR=".$anio."
                    SET @fecha_fin= DATEADD(DD,0,DATEADD(MONTH,1,CONVERT(DATE,try_convert(varchar(4) ,@YEAR) + '-'+try_convert(varchar(2),@MES_FINAL)+'-'+try_convert(varchar(2),'01'))))

                    SELECT NU_CNV AS NUM_DOC,MONTH(CONVERT(DATE,FE_NACIDO)) MES,YEAR(CONVERT(DATE,FE_NACIDO)) ANIO, CONVERT(DATE,FE_NACIDO)FE_NACIDO, CO_LOCAL,Ubigeo_LugarNacido AS
                    UBIGEO,DPTO_EESS,PROV_EESS,DIST_EESS,Nombre_EESS as EESS_NOMBRE, Lugar_Nacido,Categoria,Institucion AS Sector
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                    FROM  BD_CNV.DBO.CNV_LUGARNACIDO_PASCO with (nolock)
                    WHERE MONTH(CONVERT(DATE,FE_NACIDO))>=@MES_INICIO AND MONTH(CONVERT(DATE,FE_NACIDO))<=@MES_FINAL AND YEAR (CONVERT (DATE, FE_NACIDO))=@YEAR AND
                    TRY_CONVERT(INT,PESO_NACIDO)>=2000 AND Lugar_Nacido='ESTABLECIMIENTO DE SALUD' AND Institucion='GOBIERNO REGIONAL' AND
                    Categoria IN ('II-1','II-2','II-E','III-1', 'III-E ','III-2')

                    SELECT ID_CITA,A.Fecha_Nacimiento_Paciente,CONVERT(DATE,fecha_atencion) FECHA_ATENDIDO, B.Numero_Documento_Paciente AS NUM_DOC, A.Id_Establecimiento,tipo_diagnostico,Codigo_Item, valor_lab
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                    FROM bdhis_minsa.dbo.t_consolidado_nueva_trama_hisminsa A
                    LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_PACIENTE B ON A.Id_Paciente=B.Id_Paciente
                    LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_HIS_TIPO_DOC C ON B.Id_Tipo_Documento_Paciente=C.Id_Tipo_Documento
                    WHERE Codigo_Item IN ('90585','90744') and B.Id_Tipo_Documento_Paciente in (1,6) and YEAR(CONVERT(DATE,fecha_atencion))=@YEAR AND
                    CONVERT(DATE,fecha_atencion)<=@fecha_fin

                    --2. Estableciente la ubicación de residencia según CNV y Partos en Establecimientos del MINSA Y REGIONALES. (NIVEL: HOSPITAL)
                    SELECT NUM_DOC, MES, ANIO, FE_NACIDO, UBIGEO, DPTO_EESS as Departamento, PROV_EESS AS Provincia, DIST_EESS AS Distrito, C.Red,
                    C.id_establecimiento ID_EESS, Sector, Categoria, EESS_NOMBRE, Lugar_Nacido, DEN=1
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB A
                    INNER jOIN BDHIS_MINSA.dbo.MAESTRO_HIS_ESTABLECIMIENTO C ON CONVERT(INT,A.CO_LOCAL)=CONVERT(INT,C.Codigo_Unico)

                    --===================================
                    --ATENDIDOS
                    --===================================
                    --UNION DE BASE DE HIS Y CNV
                    SELECT A.*, b. FECHA_ATENDIDO, b.id_cita, b.Tipo_Diagnostico, b.Codigo_Item, b. VALOR_LAB
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB A
                    LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB B ON CONVERT(INT, A. ID_EESS)=CONVERT(INT,B.Id_Establecimiento) AND A.NUM_DOC=B.num_doc

                    -- INDICADORES
                    --1. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA BCG DENTRO DE LAS 24 HORAS
                    SELECT *, MAX(TEMP_BCG) OVER (PARTITION BY num_doc) NUM_BCG
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                    FROM (
                            SELECT *,IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (
                            DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                            AND CODIGO_ITEM IN ('90585'),1,0) TEMP_BCG
                            FROM BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                    ) AS T

                    --2. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA HVB DENTRO DE LAS 24 HORAS
                    SELECT *, MAX(TEMP_HVB) OVER (PARTITION BY num_doc) NUM_HVB
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                    FROM (SELECT *, IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                    AND CODIGO_ITEM IN ('90744'),1,0) TEMP_HVB FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG ) AS T

                    --REPORTE
                    SELECT ANIO, CONCAT(ANIO,'-',MES)PERIODO, UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,
                    NUM_DOC,FE_NACIDO, MAX(DEN) DEN, MAX(NUM_HVB) NUM_HVB, MAX(NUM_BCG) NUM_BCG
                    INTO BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB
                    FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                    GROUP BY ANIO, MES,UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,num_doc,FE_NACIDO");
        }else{
            $query = DB::statement("DECLARE @MES_INICIO INT, @MES_FINAL INT, @YEAR INT, @FECHA_FIN DATE
                        SET @MES_INICIO=".$mes."
                        SET @MES_FINAL=".$mes."
                        SET @YEAR=".$anio."
                        SET @fecha_fin= DATEADD(DD,0,DATEADD(MONTH,1,CONVERT(DATE,try_convert(varchar(4) ,@YEAR) + '-'+try_convert(varchar(2),@MES_FINAL)+'-'+try_convert(varchar(2),'01'))))

                        SELECT NU_CNV AS NUM_DOC,MONTH(CONVERT(DATE,FE_NACIDO)) MES,YEAR(CONVERT(DATE,FE_NACIDO)) ANIO, CONVERT(DATE,FE_NACIDO)FE_NACIDO, CO_LOCAL,Ubigeo_LugarNacido AS
                        UBIGEO,DPTO_EESS,PROV_EESS,DIST_EESS,Nombre_EESS as EESS_NOMBRE, Lugar_Nacido,Categoria,Institucion AS Sector
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                        FROM  BD_CNV.DBO.CNV_LUGARNACIDO_PASCO with (nolock)
                        WHERE MONTH(CONVERT(DATE,FE_NACIDO))>=@MES_INICIO AND MONTH(CONVERT(DATE,FE_NACIDO))<=@MES_FINAL AND YEAR (CONVERT (DATE, FE_NACIDO))=@YEAR AND
                        TRY_CONVERT(INT,PESO_NACIDO)>=2000 AND Lugar_Nacido='ESTABLECIMIENTO DE SALUD' AND Institucion='GOBIERNO REGIONAL' AND
                        Categoria IN ('II-1','II-2','II-E','III-1', 'III-E ','III-2')

                        SELECT ID_CITA,A.Fecha_Nacimiento_Paciente,CONVERT(DATE,fecha_atencion) FECHA_ATENDIDO, B.Numero_Documento_Paciente AS NUM_DOC, A.Id_Establecimiento,tipo_diagnostico,Codigo_Item, valor_lab
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                        FROM bdhis_minsa.dbo.t_consolidado_nueva_trama_hisminsa A
                        LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_PACIENTE B ON A.Id_Paciente=B.Id_Paciente
                        LEFT JOIN BDHIS_MINSA.DBO.MAESTRO_HIS_TIPO_DOC C ON B.Id_Tipo_Documento_Paciente=C.Id_Tipo_Documento
                        WHERE Codigo_Item IN ('90585','90744') and B.Id_Tipo_Documento_Paciente in (1,6) and YEAR(CONVERT(DATE,fecha_atencion))=@YEAR AND
                        CONVERT(DATE,fecha_atencion)<=@fecha_fin

                        --2. Estableciente la ubicación de residencia según CNV y Partos en Establecimientos del MINSA Y REGIONALES. (NIVEL: HOSPITAL)
                        SELECT NUM_DOC, MES, ANIO, FE_NACIDO, UBIGEO, DPTO_EESS as Departamento, PROV_EESS AS Provincia, DIST_EESS AS Distrito, C.Red,
                        C.id_establecimiento ID_EESS, Sector, Categoria, EESS_NOMBRE, Lugar_Nacido, DEN=1
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB A
                        INNER jOIN BDHIS_MINSA.dbo.MAESTRO_HIS_ESTABLECIMIENTO C ON CONVERT(INT,A.CO_LOCAL)=CONVERT(INT,C.Codigo_Unico)

                        --===================================
                        --ATENDIDOS
                        --===================================
                        --UNION DE BASE DE HIS Y CNV
                        SELECT A.*, b. FECHA_ATENDIDO, b.id_cita, b.Tipo_Diagnostico, b.Codigo_Item, b. VALOR_LAB
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB A
                        LEFT JOIN BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB B ON CONVERT(INT, A. ID_EESS)=CONVERT(INT,B.Id_Establecimiento) AND A.NUM_DOC=B.num_doc

                        -- INDICADORES
                        --1. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA BCG DENTRO DE LAS 24 HORAS
                        SELECT *, MAX(TEMP_BCG) OVER (PARTITION BY num_doc) NUM_BCG
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                        FROM (
                                SELECT *,IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (
                                DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                                AND CODIGO_ITEM IN ('90585'),1,0) TEMP_BCG
                                FROM BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                        ) AS T

                        --2. NUM_DOC QUE RECIBIERON 1 DOSIS DE VACUNA HVB DENTRO DE LAS 24 HORAS
                        SELECT *, MAX(TEMP_HVB) OVER (PARTITION BY num_doc) NUM_HVB
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                        FROM (SELECT *, IIF(FECHA_ATENDIDO IS NOT NULL AND FE_NACIDO IS NOT NULL AND (DATEDIFF(DD,FE_NACIDO,FECHA_ATENDIDO) BETWEEN 0 AND 1 )
                        AND CODIGO_ITEM IN ('90744'),1,0) TEMP_HVB FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG ) AS T

                        --REPORTE
                        SELECT ANIO, CONCAT(ANIO,'-',MES)PERIODO, UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,
                        NUM_DOC,FE_NACIDO, MAX(DEN) DEN, MAX(NUM_HVB) NUM_HVB, MAX(NUM_BCG) NUM_BCG
                        INTO BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB
                        FROM BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                        GROUP BY ANIO, MES,UBIGEO, Departamento, PROVINCIA, DISTRITO, RED, Categoria, EESS_NOMBRE, ID_EESS,num_doc,FE_NACIDO");
        }

        $query1 = DB::statement("SELECT *, IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM
                                INTO BDHIS_MINSA_EXTERNO_V2.DBO.ID_FICHA_06_NOMINAL
                                FROM BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB");

        $query2 = DB::statement("SELECT ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS
                ,SUM(DEN) DENOMINADOR, SUM(NUM) NUMERADOR, SUM(NUM_HVB) NUM_HVB, SUM(NUM_BCG ) NUM_BCG
                INTO BDHIS_MINSA_EXTERNO_V2.DBO.FICHA_RESUME_HCB_BCG
                FROM BDHIS_MINSA_EXTERNO_V2.DBO.ID_FICHA_06_NOMINAL
                GROUP BY ANIO,PERIODO,UBIGEO,Departamento,PROVINCIA,DISTRITO,RED,Categoria,EESS_NOMBRE,ID_EESS");

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                    ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM")) ->orderBy('PERIODO', 'ASC')
                    ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                    ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                        ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM"))
                        ->where('PROVINCIA', $red) ->orderBy('PERIODO', 'ASC')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                        ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM"))
                        ->where('DISTRITO', $dist) ->orderBy('PERIODO', 'ASC')
                        ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                        ->get();
        }

        $query3 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB"));

	    return Excel::download(new VaccineBcgHvbExport($nominal, $anio, $request->nameMonth), 'DEIT_PASCO VACUNAS_BCG_HVB.xlsx');
    }

    public function printRecovPatient(Request $request){
        $anio = $request->a; $mes = $request->m;

        if($mes == 'TODOS'){
            $anio = date("Y");
            $mes = date("n");
        }
        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        $query = DB::connection('BD_PADRON_NOMINAL')
                    ->statement("SELECT NUM_DNI ,FECHA_NACIMIENTO_NINO, convert(int,COD_UBIGEO_DIST) UBIGEO,NOMBRE_DEPAR,NOMBRE_PROV,NOMBRE_DIST , SEGURO='MINSA' , orden=1
                    into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_NOMINAL_FOR_PR
                    from ( select NUM_DNI, COD_UBIGEO_DIST,NOMBRE_DEPAR,NOMBRE_PROV,NOMBRE_DIST, TIPO_SEGURO, FECHA_NACIMIENTO_NINO,DATEADD(DAY,540,FECHA_NACIMIENTO_NINO)AS'PERIODO DE EVALUACION'
                        from NOMINAL_PADRON_NOMINAL with (nolock)
                        WHERE ((TIpo_SEGURO IN ('0, ','0','0, 1, ','0, 1, 2, ','0, 1, 3,' ,'0, 1, 4, ','0, 1, 2, 4, ','1, ','1','1, 2, ','1, 2, 3, ','1, 2, 3, ','1, 2, 4, ','1, 3, ','1, 3, 4, ','1, 4, '))
                    OR (TIPO_SEGURO IS NULL)) and MES='". $anio ."". $mes2 ."' ) pd ");

        $query1 = DB::statement("create table tabla_pr ( Provincia_Establecimiento nvarchar(200),
                    Distrito_Establecimiento nvarchar(200), Nombre_Establecimiento nvarchar(200),
                    num_doc nvarchar(8), fecha_nac date ,seguro varchar(50), ubigeo int, mes int
                    ,año int, fecha_dx date, den int, num int, fecha_t1 date, num_t1 int, fecha_t2 date, num_t2 int
                    ,fecha_recup date ,num_recup int ,fecha_dosaje date ,num_dosaje int )");

        if($request->m == 'TODOS'){
            $query2 = DB::connection('BDHIS_MINSA')
                        ->statement("declare @mes_eval int, @mes_final int, @year int, @fec_eval_1 date, @fec_eval_2 date
                        set @mes_eval=1
                        set @mes_final=12
                        set @year=". $anio ."
                        while @mes_eval<=@mes_final
                        begin
                            set @fec_eval_1=try_convert(date,try_convert(varchar(4),@YEAR)+'-'+right('00'+try_convert(varchar(2),@mes_eval),2)+'-'+right('00'+try_convert(varchar(2),1),2))                      --2021-01-01
                            set @fec_eval_2=EOMONTH( try_convert(date, try_convert(varchar(4),@YEAR)+'-'+right('00'+try_convert(varchar(2),@mes_eval),2)+'-'+right('00'+try_convert(varchar(2),1),2)))           --2021-01-31

                            --DENOMINADOR
                            --*********
                            --1.Reducción de padrón nominal. (Niños con 350 y 573 dias de edad en el mes de evaluación).
                            select NUM_DNI, FECHA_NACIMIENTO_NINO, seguro , ubigeo ,@mes_eval mes, @year año
                            into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR
                            from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_NOMINAL_FOR_PR
                            where ( ( @fec_eval_2 between dateadd(dd,350,FECHA_NACIMIENTO_NINO) and dateadd(dd,573,FECHA_NACIMIENTO_NINO) )OR (  @fec_eval_1 between dateadd(dd,350,FECHA_NACIMIENTO_NINO) and dateadd(dd,573,FECHA_NACIMIENTO_NINO) ) )

                            -- 2. búsqueda de niños con anemia entre 170 y 364 dias, que cumplen en el mes de evaluación 209 dias adicionales a partir del DX.
                            select Numero_Documento_Paciente, fecha_dx
                            into BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR
                            from (
                            select a.Numero_Documento_Paciente, MAX(a.fecha_atencion) fecha_dx
                            from (
                                    select distinct fecha_atencion, Numero_Documento_Paciente from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR
                                    where Codigo_Item in ('D500','D508','D509','D649') and Tipo_Diagnostico='D'
                                    and fecha_atencion<=@fec_eval_2
                                ) a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR b on a.Numero_Documento_Paciente=b.NUM_DNI
                            where (datediff(dd,b.FECHA_NACIMIENTO_NINO,a.fecha_atencion) between 170 and 364)
                            group by a.Numero_Documento_Paciente
                            ) as t where month(dateadd(dd,209,fecha_dx))=@mes_eval and YEAR(dateadd(dd,209,fecha_dx))=@year

                            -- NUMERADOR
                            --*********************************************
                            --3. tratamiento.
                            select distinct fecha_atencion, Numero_Documento_Paciente, Provincia_Establecimiento, Distrito_Establecimiento, Nombre_Establecimiento
                            into BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR
                            from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR where
                            Codigo_Item in ('U310','99199.17') and
                                ( valor_lab in ('SF1','SF2','SF3','P01','P02','PO1','PO2') or try_convert(int,valor_lab) in ('1','2','3') ) and
                                id_cita in (select distinct id_cita from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR where Codigo_Item in ('D500','D508','D509','D649') and Tipo_Diagnostico in ('D','R')
                                    )and
                            fecha_atencion<=@fec_eval_2

                            -- 3.1 Tratamiento Oportuno
                            select a.Numero_Documento_Paciente, min(a.fecha_atencion) fecha_t1
                            into BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR from BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente
                            where (datediff(dd,b.fecha_dx,a.fecha_atencion) between 0 and 7)
                            group by a.Numero_Documento_Paciente

                            -- 3.2 Continua Tratamiento por 6 meses (Entrega entre 25 a 100 dias [Maximo 2 entregas])
                            select a.Numero_Documento_Paciente, min(a.fecha_atencion) fecha_t2
                            into BDHIS_MINSA_EXTERNO_V2.dbo.TRAT2_PR from BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente
                            where (datediff(dd,b.fecha_t1,a.fecha_atencion) between 25 and 100)
                            group by a.Numero_Documento_Paciente

                            --4. Recuperación y Dosaje de HB entre 180 a 209 dias.
                            select a.Numero_Documento_Paciente, max(a.fecha_atencion) fecha_recup
                            into BDHIS_MINSA_EXTERNO_V2.dbo.RECUP_PR
                            from (
                                    select distinct fecha_atencion, Numero_Documento_Paciente from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR
                                    where valor_lab='PR' and Codigo_Item in ('D500''D508','D509','D649') and Tipo_Diagnostico='R'
                                    and fecha_atencion<=@fec_eval_2
                                )a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente
                            where (datediff(dd,b.fecha_dx,a.fecha_atencion) between 180 and 209)
                            group by a.Numero_Documento_Paciente

                            select a.Numero_Documento_Paciente, MAX(a.fecha_atencion) fecha_dosaje
                            into BDHIS_MINSA_EXTERNO_V2.dbo.DOSAJE_PR
                            from (
                                    select distinct fecha_atencion, Numero_Documento_Paciente from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR
                                    where Numero_Documento_Paciente in ('Z017','85018') and Tipo_Diagnostico='D' and fecha_atencion<=@fec_eval_2
                                )a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente 
                            where (datediff(dd,b.fecha_dx,a.fecha_atencion) between 180 and 209)
                            group by a.Numero_Documento_Paciente

                            --BASE FINAL
                            select *
                            , IIF(num_t1=1 and num_t2=1 and num_recup=1 and num_dosaje=1,1,0) num
                            into BDHIS_MINSA_EXTERNO_V2.dbo.BASE_PR
                            from (
                                select a.*, b.fecha_dx, iif(b.fecha_dx is null,0,1) den, c.fecha_t1, iif(c.fecha_t1 is null,0,1) num_t1
                                , d.fecha_t2, iif(d.fecha_t2 is null,0,1) num_t2, f.fecha_recup, iif(f.fecha_recup is null,0,1) num_recup
                                , g.fecha_dosaje, iif(g.fecha_dosaje is null,0,1) num_dosaje
                                from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR a
                                inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on    a.NUM_DNI=b.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR c on  a.NUM_DNI=c.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.TRAT2_PR d on  a.NUM_DNI=d.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.RECUP_PR f on  a.NUM_DNI=f.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.DOSAJE_PR g on a.NUM_DNI=g.Numero_Documento_Paciente
                            ) as t

                            insert into BDHIS_MINSA_EXTERNO_V2.dbo.tabla_pr
                            select z.Provincia_Establecimiento, z.Distrito_Establecimiento, z.Nombre_Establecimiento, NUM_DNI, FECHA_NACIMIENTO_NINO,
                            seguro, ubigeo, mes, año, fecha_dx, den, num, fecha_t1, num_t1, fecha_t2, num_t2
                            , fecha_recup, num_recup, fecha_dosaje, num_dosaje
                            from BDHIS_MINSA_EXTERNO_V2.dbo.BASE_PR a left join BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR z
                            on a.NUM_DNI=z.Numero_Documento_Paciente;

                            with c as ( select num_doc, ROW_NUMBER() over(partition by num_doc order by num_doc) as duplicado
                            from BDHIS_MINSA_EXTERNO_V2.dbo.tabla_pr)
                            delete  from c
                            where duplicado >1;

                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR

                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TRAT2_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.RECUP_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.DOSAJE_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.BASE_PR
                            set @mes_eval = @mes_eval + 1
                        end");
        }else{
            $query2 = DB::connection('BDHIS_MINSA')
                        ->statement("declare @mes_eval int, @mes_final int, @year int, @fec_eval_1 date, @fec_eval_2 date
                        set @mes_eval=". $mes ."
                        set @mes_final=". $mes ."
                        set @year=". $anio ."
                        while @mes_eval<=@mes_final
                        begin
                            set @fec_eval_1=try_convert(date,try_convert(varchar(4),@YEAR)+'-'+right('00'+try_convert(varchar(2),@mes_eval),2)+'-'+right('00'+try_convert(varchar(2),1),2))                      --2021-01-01
                            set @fec_eval_2=EOMONTH( try_convert(date, try_convert(varchar(4),@YEAR)+'-'+right('00'+try_convert(varchar(2),@mes_eval),2)+'-'+right('00'+try_convert(varchar(2),1),2)))           --2021-01-31

                            --DENOMINADOR
                            --*********
                            --1.Reducción de padrón nominal. (Niños con 350 y 573 dias de edad en el mes de evaluación).
                            select NUM_DNI, FECHA_NACIMIENTO_NINO, seguro , ubigeo ,@mes_eval mes, @year año
                            into BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR
                            from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_NOMINAL_FOR_PR
                            where ( ( @fec_eval_2 between dateadd(dd,350,FECHA_NACIMIENTO_NINO) and dateadd(dd,573,FECHA_NACIMIENTO_NINO) )OR (  @fec_eval_1 between dateadd(dd,350,FECHA_NACIMIENTO_NINO) and dateadd(dd,573,FECHA_NACIMIENTO_NINO) ) )

                            -- 2. búsqueda de niños con anemia entre 170 y 364 dias, que cumplen en el mes de evaluación 209 dias adicionales a partir del DX.
                            select Numero_Documento_Paciente, fecha_dx
                            into BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR
                            from (
                            select a.Numero_Documento_Paciente, MAX(a.fecha_atencion) fecha_dx
                            from (
                                    select distinct fecha_atencion, Numero_Documento_Paciente from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR
                                    where Codigo_Item in ('D500','D508','D509','D649') and Tipo_Diagnostico='D'
                                    and fecha_atencion<=@fec_eval_2
                                ) a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR b on a.Numero_Documento_Paciente=b.NUM_DNI
                            where (datediff(dd,b.FECHA_NACIMIENTO_NINO,a.fecha_atencion) between 170 and 364)
                            group by a.Numero_Documento_Paciente
                            ) as t where month(dateadd(dd,209,fecha_dx))=@mes_eval and YEAR(dateadd(dd,209,fecha_dx))=@year

                            -- NUMERADOR
                            --*********************************************
                            --3. tratamiento.
                            select distinct fecha_atencion, Numero_Documento_Paciente, Provincia_Establecimiento, Distrito_Establecimiento, Nombre_Establecimiento
                            into BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR
                            from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR where
                            Codigo_Item in ('U310','99199.17') and
                                ( valor_lab in ('SF1','SF2','SF3','P01','P02','PO1','PO2') or try_convert(int,valor_lab) in ('1','2','3') ) and
                                id_cita in (select distinct id_cita from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR where Codigo_Item in ('D500','D508','D509','D649') and Tipo_Diagnostico in ('D','R')
                                    )and
                            fecha_atencion<=@fec_eval_2

                            -- 3.1 Tratamiento Oportuno
                            select a.Numero_Documento_Paciente, min(a.fecha_atencion) fecha_t1
                            into BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR from BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente
                            where (datediff(dd,b.fecha_dx,a.fecha_atencion) between 0 and 7)
                            group by a.Numero_Documento_Paciente

                            -- 3.2 Continua Tratamiento por 6 meses (Entrega entre 25 a 100 dias [Maximo 2 entregas])
                            select a.Numero_Documento_Paciente, min(a.fecha_atencion) fecha_t2
                            into BDHIS_MINSA_EXTERNO_V2.dbo.TRAT2_PR from BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente
                            where (datediff(dd,b.fecha_t1,a.fecha_atencion) between 25 and 100)
                            group by a.Numero_Documento_Paciente

                            --4. Recuperación y Dosaje de HB entre 180 a 209 dias.
                            select a.Numero_Documento_Paciente, max(a.fecha_atencion) fecha_recup
                            into BDHIS_MINSA_EXTERNO_V2.dbo.RECUP_PR
                            from (
                                    select distinct fecha_atencion, Numero_Documento_Paciente from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR
                                    where valor_lab='PR' and Codigo_Item in ('D500''D508','D509','D649') and Tipo_Diagnostico='R'
                                    and fecha_atencion<=@fec_eval_2
                                )a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente
                            where (datediff(dd,b.fecha_dx,a.fecha_atencion) between 180 and 209)
                            group by a.Numero_Documento_Paciente

                            select a.Numero_Documento_Paciente, MAX(a.fecha_atencion) fecha_dosaje
                            into BDHIS_MINSA_EXTERNO_V2.dbo.DOSAJE_PR
                            from (
                                    select distinct fecha_atencion, Numero_Documento_Paciente from BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR
                                    where Numero_Documento_Paciente in ('Z017','85018') and Tipo_Diagnostico='D' and fecha_atencion<=@fec_eval_2
                                )a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on a.Numero_Documento_Paciente=b.Numero_Documento_Paciente 
                            where (datediff(dd,b.fecha_dx,a.fecha_atencion) between 180 and 209)
                            group by a.Numero_Documento_Paciente

                            --BASE FINAL
                            select *
                            , IIF(num_t1=1 and num_t2=1 and num_recup=1 and num_dosaje=1,1,0) num
                            into BDHIS_MINSA_EXTERNO_V2.dbo.BASE_PR
                            from (
                                select a.*, b.fecha_dx, iif(b.fecha_dx is null,0,1) den, c.fecha_t1, iif(c.fecha_t1 is null,0,1) num_t1
                                , d.fecha_t2, iif(d.fecha_t2 is null,0,1) num_t2, f.fecha_recup, iif(f.fecha_recup is null,0,1) num_recup
                                , g.fecha_dosaje, iif(g.fecha_dosaje is null,0,1) num_dosaje
                                from BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR a
                                inner join BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR b on    a.NUM_DNI=b.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR c on  a.NUM_DNI=c.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.TRAT2_PR d on  a.NUM_DNI=d.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.RECUP_PR f on  a.NUM_DNI=f.Numero_Documento_Paciente
                                left join BDHIS_MINSA_EXTERNO_V2.dbo.DOSAJE_PR g on a.NUM_DNI=g.Numero_Documento_Paciente
                            ) as t

                            insert into BDHIS_MINSA_EXTERNO_V2.dbo.tabla_pr
                            select z.Provincia_Establecimiento, z.Distrito_Establecimiento, z.Nombre_Establecimiento, NUM_DNI, FECHA_NACIMIENTO_NINO,
                            seguro, ubigeo, mes, año, fecha_dx, den, num, fecha_t1, num_t1, fecha_t2, num_t2
                            , fecha_recup, num_recup, fecha_dosaje, num_dosaje
                            from BDHIS_MINSA_EXTERNO_V2.dbo.BASE_PR a left join BDHIS_MINSA_EXTERNO_V2.dbo.HIS_FOR_PR z
                            on a.NUM_DNI=z.Numero_Documento_Paciente;

                            with c as ( select num_doc, ROW_NUMBER() over(partition by num_doc order by num_doc) as duplicado
                            from BDHIS_MINSA_EXTERNO_V2.dbo.tabla_pr)
                            delete  from c
                            where duplicado >1;

                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.DX_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TRATAMIENTO_PR

                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TRAT1_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TRAT2_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.RECUP_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.DOSAJE_PR
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.BASE_PR
                            set @mes_eval = @mes_eval + 1
                        end");
        }

        $nominal = DB::table('dbo.tabla_pr') ->select('*')
                    ->orderBy('Provincia_Establecimiento', 'ASC') ->orderBy('Distrito_Establecimiento', 'ASC')
                    ->get();

        $query4 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.PADRON_NOMINAL_FOR_PR
                                         DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.tabla_pr"));

	    return Excel::download(new RecoveredPatientExport($nominal, $anio, $request->nameMonth), 'DEIT_PASCO PACIENTES_RECUPERADOS.xlsx');
    }

    public function printTwoCtrlCred(Request $request){
        $red_1 = $request->r; $dist = $request->d;
        $anio = $request->a; $mes = $request->m;

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($mes == 'TODOS'){
            $anio = date("Y");
            $mes = date("n");
        }
        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        $query = DB::connection('BD_PADRON_NOMINAL')
                    ->statement("SELECT *
                    into BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATA_PN_2CTRL
                    from BD_PADRON_NOMINAL.DBO.PADRON_NOMINAL_CONSOLIDADO with (nolock)
                    where CORTE_PADRON='". $anio ."". $mes2 ."' and TIPO_SEGURO='MINSA'");

        $query1 = DB::statement("create table BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_2CTRL (
                    año int, mes int, departam nvarchar(255), provincia nvarchar(255), distrito nvarchar(255),
                    DNI nvarchar(50), fecha_nac date, seguro VARCHAR(5), ubi_res varchar(6), fecha_cred1 date,
                    cred1 int, fecha_cred2 date, cred2  int, num int, den int)");

        if($request->m == 'TODOS'){
            $query2 = DB::connection('BDHIS_MINSA')
                        ->statement("declare @anio_eval int, @mes_eval int, @mes_final int
                        declare @fec_eval date
                        set @anio_eval=". $anio ."
                        set @mes_final=12
                        set @mes_eval=1
                        while @mes_eval <= @mes_final
                        begin
                            set @fec_eval=convert(date,try_convert(varchar(4),@anio_eval)+'-'+RIGHT('0'+try_convert(varchar(2),@mes_eval,2),2)+'-'+RIGHT('0'+try_convert(varchar(2),1,2),2))--2022-01-01

                            --DENOMINADOR
                            select distinct dni , fecha_nac, fecha_fin, seguro, ubi_res, MONTH(Convert(date,DATEADD(DD,14,fecha_nac))) mes
                            , YEAR(Convert(date,DATEADD(DD,14,fecha_nac))) año
                            into BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL
                            from (
                                select distinct case when a.NUM_DNI is null or a.NUM_DNI in ('','NULL') then a.NUM_CNV
                                else a.NUM_DNI end dni, a.NUM_CNV,a.NUM_DNI, TRY_CONVERT(DATE,a.FECHA_NACIMIENTO_NINO) fecha_nac,
                                Convert(date,DATEADD(DD,14,FECHA_NACIMIENTO_NINO)) fecha_fin,TIPO_SEGURO as seguro,a.COD_UBIGEO ubi_res
                                from BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATA_PN_2CTRL a where (datediff(dd,a.FECHA_NACIMIENTO_NINO,@fec_eval)<=14
                                and datediff(dd,a.FECHA_NACIMIENTO_NINO,eomonth(@fec_eval))>=14)
                            ) as t

                            select a.num_doc
                            into BDHIS_MINSA_EXTERNO_V2.dbo.BPN_2CTRL
                            FROM BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATAHIS_2CTRL a
                            INNER join BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL b on a.num_doc collate database_default = b.dni collate database_default
                            where ( try_convert(date,a.periodo) between b.fecha_nac and b.fecha_fin )
                            and a.COD_ITEM IN ('P070','P0711','P0712')

                            select *
                            into BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL
                            from BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL
                            where dni collate database_default not in (select distinct num_doc from BDHIS_MINSA_EXTERNO_V2.dbo.BPN_2CTRL)

                            -- NUMERADOR
                            -- CRED1
                            select distinct a.dni, a.fecha_nac, a.fecha_fin, convert(date,b.periodo) fecha_cred1
                            into BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL
                            from BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATAHIS_2CTRL b on a.dni=b.num_doc collate Modern_Spanish_CI_AS
                            where b.cod_item='99381.01'
                            and (convert(date,b.periodo) between dateadd(dd,3,a.fecha_nac) and a.fecha_fin)

                            -- CRED2
                            select distinct a.dni, a.fecha_nac, a.fecha_fin, a.fecha_cred1, convert(date,b.periodo)fecha_cred2
                            into BDHIS_MINSA_EXTERNO_V2.dbo.CRED2_03_2CTRL
                            from BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATAHIS_2CTRL b on a.dni=b.num_doc collate Modern_Spanish_CI_AS
                            where b.cod_item='99381.01'
                            and (convert(date,b.periodo) between dateadd(dd,3,a.fecha_nac) and a.fecha_fin)
                            and DATEDIFF(dd,a.fecha_cred1,convert(date,b.periodo))>=3

                            select dni, min(fecha_cred1) fecha_cred1, cred1=1 into BDHIS_MINSA_EXTERNO_V2.dbo.TMP01_2CTRL from BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL group by dni
                            select dni, min(fecha_cred2) fecha_cred2, cred2=1 into BDHIS_MINSA_EXTERNO_V2.dbo.TMP02_2CTRL from BDHIS_MINSA_EXTERNO_V2.dbo.CRED2_03_2CTRL group by dni

                            insert into BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_2CTRL
                            select CONVERT(INT,p.año) año, convert(int,p.mes) mes,u.Departamento, u.provincia, u.distrito ,p.dni, try_convert(date,p.fecha_nac) FECHA_NAC, p.seguro, p.ubi_res AS UBIGEO_RESIDENCIA,
                            try_convert(date,t1.fecha_cred1) fecha_Cred1, isnull(cred1,0) cred1, try_convert(date,t2.fecha_cred2) fecha_cred2, isnull(cred2,0) cred2, isnull(cred2,0) num,den=1
                            from BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL p
                            left join BDHIS_MINSA_EXTERNO_V2.dbo.TMP01_2CTRL t1 on p.dni collate database_default=t1.dni collate database_default
                            left join BDHIS_MINSA_EXTERNO_V2.dbo.TMP02_2CTRL t2 on p.dni collate database_default=t2.dni collate database_default
                            left join BDHIS_MINSA.DBO.MAESTRO_HIS_UBIGEO_INEI_RENIEC u on p.ubi_res=try_convert(int,u.Id_Ubigueo_Inei)

                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TMP01_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TMP02_2CTRL
                            DROP table BDHIS_MINSA_EXTERNO_V2.dbo.BPN_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.CRED2_03_2CTRL
                            set @mes_eval = @mes_eval + 1
                        end");
        }else{
            $query2 = DB::connection('BDHIS_MINSA')
                        ->statement("declare @anio_eval int, @mes_eval int, @mes_final int
                        declare @fec_eval date
                        set @anio_eval=". $anio ."
                        set @mes_final=". $mes ."
                        set @mes_eval=". $mes ."
                        while @mes_eval <= @mes_final
                        begin
                            set @fec_eval=convert(date,try_convert(varchar(4),@anio_eval)+'-'+RIGHT('0'+try_convert(varchar(2),@mes_eval,2),2)+'-'+RIGHT('0'+try_convert(varchar(2),1,2),2))--2022-01-01

                            --DENOMINADOR
                            select distinct dni , fecha_nac, fecha_fin, seguro, ubi_res, MONTH(Convert(date,DATEADD(DD,14,fecha_nac))) mes
                            , YEAR(Convert(date,DATEADD(DD,14,fecha_nac))) año
                            into BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL
                            from (
                                select distinct case when a.NUM_DNI is null or a.NUM_DNI in ('','NULL') then a.NUM_CNV
                                else a.NUM_DNI end dni, a.NUM_CNV,a.NUM_DNI, TRY_CONVERT(DATE,a.FECHA_NACIMIENTO_NINO) fecha_nac,
                                Convert(date,DATEADD(DD,14,FECHA_NACIMIENTO_NINO)) fecha_fin,TIPO_SEGURO as seguro,a.COD_UBIGEO ubi_res
                                from BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATA_PN_2CTRL a where (datediff(dd,a.FECHA_NACIMIENTO_NINO,@fec_eval)<=14
                                and datediff(dd,a.FECHA_NACIMIENTO_NINO,eomonth(@fec_eval))>=14)
                            ) as t

                            select a.num_doc
                            into BDHIS_MINSA_EXTERNO_V2.dbo.BPN_2CTRL
                            FROM BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATAHIS_2CTRL a
                            INNER join BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL b on a.num_doc collate database_default = b.dni collate database_default
                            where ( try_convert(date,a.periodo) between b.fecha_nac and b.fecha_fin )
                            and a.COD_ITEM IN ('P070','P0711','P0712')

                            select *
                            into BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL
                            from BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL
                            where dni collate database_default not in (select distinct num_doc from BDHIS_MINSA_EXTERNO_V2.dbo.BPN_2CTRL)

                            -- NUMERADOR
                            -- CRED1
                            select distinct a.dni, a.fecha_nac, a.fecha_fin, convert(date,b.periodo) fecha_cred1
                            into BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL
                            from BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATAHIS_2CTRL b on a.dni=b.num_doc collate Modern_Spanish_CI_AS
                            where b.cod_item='99381.01'
                            and (convert(date,b.periodo) between dateadd(dd,3,a.fecha_nac) and a.fecha_fin)

                            -- CRED2
                            select distinct a.dni, a.fecha_nac, a.fecha_fin, a.fecha_cred1, convert(date,b.periodo)fecha_cred2
                            into BDHIS_MINSA_EXTERNO_V2.dbo.CRED2_03_2CTRL
                            from BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL a
                            inner join BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATAHIS_2CTRL b on a.dni=b.num_doc collate Modern_Spanish_CI_AS
                            where b.cod_item='99381.01'
                            and (convert(date,b.periodo) between dateadd(dd,3,a.fecha_nac) and a.fecha_fin)
                            and DATEDIFF(dd,a.fecha_cred1,convert(date,b.periodo))>=3

                            select dni, min(fecha_cred1) fecha_cred1, cred1=1 into BDHIS_MINSA_EXTERNO_V2.dbo.TMP01_2CTRL from BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL group by dni
                            select dni, min(fecha_cred2) fecha_cred2, cred2=1 into BDHIS_MINSA_EXTERNO_V2.dbo.TMP02_2CTRL from BDHIS_MINSA_EXTERNO_V2.dbo.CRED2_03_2CTRL group by dni

                            insert into BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_2CTRL
                            select CONVERT(INT,p.año) año, convert(int,p.mes) mes,u.Departamento, u.provincia, u.distrito ,p.dni, try_convert(date,p.fecha_nac) FECHA_NAC, p.seguro, p.ubi_res AS UBIGEO_RESIDENCIA,
                            try_convert(date,t1.fecha_cred1) fecha_Cred1, isnull(cred1,0) cred1, try_convert(date,t2.fecha_cred2) fecha_cred2, isnull(cred2,0) cred2, isnull(cred2,0) num,den=1
                            from BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL p
                            left join BDHIS_MINSA_EXTERNO_V2.dbo.TMP01_2CTRL t1 on p.dni collate database_default=t1.dni collate database_default
                            left join BDHIS_MINSA_EXTERNO_V2.dbo.TMP02_2CTRL t2 on p.dni collate database_default=t2.dni collate database_default
                            left join BDHIS_MINSA.DBO.MAESTRO_HIS_UBIGEO_INEI_RENIEC u on p.ubi_res=try_convert(int,u.Id_Ubigueo_Inei)

                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.PADRONF03_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.DATAPN_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TMP01_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.TMP02_2CTRL
                            DROP table BDHIS_MINSA_EXTERNO_V2.dbo.BPN_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.CRED103_2CTRL
                            drop table BDHIS_MINSA_EXTERNO_V2.dbo.CRED2_03_2CTRL
                            set @mes_eval = @mes_eval + 1
                        end");
        }

        if($red_1 == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_2CTRL')
                        ->select('año', DB::raw("CONCAT(año,'-', MES) AS PERIODO"), 'ubi_res as UBIGEO_RESIDENCIA', 'PROVINCIA',
                        'DISTRITO', 'DNI', 'FECHA_NAC', 'SEGURO', 'FECHA_CRED1', 'FECHA_CRED2')
                        ->orderBy('PERIODO', 'ASC') ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();
        }
        else if($red_1 != 'TODOS' && $dist == 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_2CTRL')
                        ->select('año', DB::raw("CONCAT(año,'-', MES) AS PERIODO"), 'ubi_res as UBIGEO_RESIDENCIA', 'PROVINCIA',
                        'DISTRITO', 'DNI', 'FECHA_NAC', 'SEGURO', 'FECHA_CRED1', 'FECHA_CRED2') ->where('PROVINCIA', $red)
                        ->orderBy('PERIODO', 'ASC') ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();
        }
        else if($dist != 'TODOS'){
            $nominal = DB::table('dbo.CONSOL_2CTRL')
                        ->select('año', DB::raw("CONCAT(año,'-', MES) AS PERIODO"), 'ubi_res as UBIGEO_RESIDENCIA', 'PROVINCIA',
                        'DISTRITO', 'DNI', 'FECHA_NAC', 'SEGURO', 'FECHA_CRED1', 'FECHA_CRED2') ->where('DISTRITO', $dist)
                        ->orderBy('PERIODO', 'ASC') ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC')
                        ->get();
        }


        $query4 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.OBT_DATA_PN_2CTRL
                                        DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_2CTRL"));

	    return Excel::download(new TwoCtrlCredExport($nominal, $anio, $request->nameMonth), 'DEIT_PASCO DOS CONTROLES CRED.xlsx');
    }
}