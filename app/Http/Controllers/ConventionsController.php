<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;

class ConventionsController extends Controller
{
    public function index(Request $request) {
        return view('diresaIndicators/ConventionsManagement/index');
    }

    public function listVaccineBcgHvb(Request $request){
        $red_1 = $request->red;
        $dist = $request->distrito;
        $anio = $request->anio;
        $mes = $request->mes;

        if (strlen($mes) == 1){ $mes2 = '0'.$mes; }
        else{ $mes2 = $mes; }

        if ($red_1 == '01') { $red = 'PASCO'; }
        elseif ($red_1 == '02') { $red = 'DANIEL CARRION'; }
        elseif ($red_1 == '03') { $red = 'OXAPAMPA'; }

        if($red_1 == 'TODOS'){
            $nominal0 = DB::statement("DECLARE @MES_INICIO INT, @MES_FINAL INT, @YEAR INT, @FECHA_FIN DATE
                        SET @MES_INICIO=1
                        SET @MES_FINAL=9
                        SET @YEAR=2022
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

            $nominal = DB::table('dbo.REPORTE_BCG_HVB')
                    ->select('*', DB::raw("IIF (NUM_HVB=1 AND NUM_BCG=1,1,0) NUM"))
                    ->orderBy('PROVINCIA', 'ASC') ->orderBy('DISTRITO', 'ASC') ->orderBy('EESS_NOMBRE', 'ASC')
                    ->get();

            
            $query1 = DB::statement(DB::raw("DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_CNV_BCG_HVB
                                            DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CONSOL_HISMINSA_BCG_HVB
                                            DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.CNV_FINAL_BCG_HVB
                                            DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.ATENDIDOS_BCG_HVB
                                            DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_BCG
                                            DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.DOSIS1_HVB
                                            DROP TABLE BDHIS_MINSA_EXTERNO_V2.dbo.REPORTE_BCG_HVB"));

            $t_resume = '';

            $resum_red = '';
        }

        $q[] = json_decode($nominal, true);
        $q[] = json_decode($t_resume, true);
        $q[] = json_decode($resum_red, true);
        $r = json_encode($q);
        return response(($r), 200);
    }
}