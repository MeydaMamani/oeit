@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appMetals">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-9">
                            <h5 class="mb-0">Registro de Seguimiento de Personas Expuestas a Metales Pesados, Metaloides y Otras Sustancias Químicas</span></h5>
                        </div>
                        <div class="col-sm-3">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="#">Metales Pesados</a></li>
                                <li class="breadcrumb-item active">Seguimiento</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary border border-primary">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-15">Filtro por Red</h3>
                                </div>
                                <form method="post" id="formulario">
                                    <div class="card-body pt-2 pb-2">
                                        <div class="row">
                                            <div class="col-md-10 row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <select class="form-control select2 show-tick" style="width: 100%;" v-model="red" name="red" id="red" @change="filtersDistricts" v-select2>
                                                            <option value="">Seleccione Red</option>
                                                            <option v-for="format in provinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito" name="distrito" id="distrito" v-select2>
                                                            <option value="">Seleccione Distrito</option>
                                                            <option v-for="format in districts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio" id="anio" name="anio" v-select2>
                                                            <option value="">Seleccione año</option>
                                                            <option value="2019">2019</option>
                                                            <option value="2020">2020</option>
                                                            <option value="2021">2021</option>
                                                            <option value="2022">2022</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <select class="form-control select2 show-tick" style="width: 100%;" v-model="mes" id="mes" name="mes" v-select2>
                                                            <option value="">Seleccione mes</option>
                                                            <option value="1">ENERO</option>
                                                            <option value="2">FEBRERO</option>
                                                            <option value="3">MARZO</option>
                                                            <option value="4">ABRIL</option>
                                                            <option value="5">MAYO</option>
                                                            <option value="6">JUNIO</option>
                                                            <option value="7">JULIO</option>
                                                            <option value="8">AGOSTO</option>
                                                            <option value="9">SETIEMBRE</option>
                                                            <option value="10">OCTUBRE</option>
                                                            <option value="11">NOVIEMBRE</option>
                                                            <option value="12">DICIEMBRE</option>
                                                            <option value="TODOS">TODOS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 p-0 text-center">
                                                <button class="btn btn-primary btn-sm m-1 font-11" id="search" type="button" @click="listMetals"><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" @click="clearRed"><i class="fa fa-broom"></i> Limpiar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-warning border border-warning">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1 text-center">
                                    <h3 class="card-title font-15">Filtro Paciente</h3>
                                </div>
                                <form method="POST" id="formulario2">
                                    <div class="card-body p-2">
                                        <div class="col-md-12">
                                            <input class="form-control form-control-sm" type="text" name="doc" id="doc" v-model='doc' placeholder="Ingrese su dni..." maxlength="8">
                                        </div>
                                        <div class="col-md-12 pt-1 text-center">
                                            <button class="btn btn-warning btn-sm m-1 font-11" id="search" type="button" @click="listMetalsDni"><i class="fa fa-search"></i> Buscar</button>
                                            <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" @click="clearDocumento"><i class="fa fa-broom"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </form>
                              </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-danger border border-danger">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1 text-center">
                                    <h3 class="card-title font-15">Filtro Por Categoria</h3>
                                </div>
                                <form method="POST" id="formulario3">
                                    <div class="card-body p-2">
                                        <div class="col-md-12">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="category" id="category" name="category" v-select2>
                                                <option value="">Seleccione Categoria</option>
                                                <option value="2019">Categoria I</option>
                                                <option value="2020">Categoria II</option>
                                                <option value="2021">Categoria III</option>
                                                <option value="2022">Categoria IV</option>
                                                <option value="2019">Categoria V</option>
                                                <option value="2020">Categoria VI</option>
                                                <option value="2021">Categoria VII</option>
                                                <option value="2022">Categoria VIII</option>
                                                <option value="2022">Categoria IX</option>
                                                <option value="2022">Categoria X</option>
                                                <option value="2022">Categoria XI</option>
                                                <option value="2022">Categoria XII</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 pt-1 text-center">
                                            <button class="btn btn-danger btn-sm m-1 font-11" id="search" type="button" @click="listMetalsCategory"><i class="fa fa-search"></i> Buscar</button>
                                            <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" @click="clearCategory"><i class="fa fa-broom"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </form>
                              </div>
                        </div>
                    </div>
                    <div class="col-md-12" v-show='table'>
                        <button type="submit" id="export" value="" name="export" class="btn btn-outline-success m-1 mb-2 font-13" @click="PrintNominal"><i class="fa fa-print"></i> Descargar Historial</button>
                    </div>
                    <div class="col-md-12 col-sm-12" v-show='table'>
                        <div class="table-responsive nominalTable" id="bateria_completa">
                            <table id="demo-foo-addrow2" class="table table-hover table-striped" data-page-size="20" data-limit-navigation="10">
                                <thead>
                                    <tr class="font-10 text-center" style="background: #e0eff5;">
                                        <th class="align-middle">#</th>
                                        <th class="align-middle">Apellidos y Nombres</th>
                                        <th class="align-middle">Sexo</th>
                                        <th class="align-middle">Etnia</th>
                                        <th class="align-middle">Lengua Materna</th>
                                        <th class="align-middle">Fecha Nacido</th>
                                        <th class="align-middle">Edad</th>
                                        <th class="align-middle">Tipo Documento</th>
                                        <th class="align-middle">Documento</th>
                                        <th class="align-middle">Historia Clínica</th>
                                        <th class="align-middle">Menor de Edad</th>
                                        <th class="align-middle">Tipo Doc Apoderado</th>
                                        <th class="align-middle">Documento Apoderado</th>
                                        <th class="align-middle">Nombres Apoderado</th>
                                        <th class="align-middle">Tipo Caso</th>
                                        <th class="align-middle">Fecha Ingreso Padrón</th>
                                        <th class="align-middle">Pseudónimo Código</th>
                                        <th class="align-middle">Teléfono</th>
                                        <th class="align-middle">Dirección Antigua</th>
                                        <th class="align-middle">Región Antigua</th>
                                        <th class="align-middle">Provinia Antigua</th>
                                        <th class="align-middle">Distrito Antigua</th>
                                        <th class="align-middle">Años Anterior</th>
                                        <th class="align-middle">Dirección Actual</th>
                                        <th class="align-middle">Región Actual</th>
                                        <th class="align-middle">Provinia Actual</th>
                                        <th class="align-middle">Distrito Actual</th>
                                        <th class="align-middle">Años Actual</th>
                                        <th class="align-middle">Tipo Seguro</th>
                                        <th class="align-middle">Resultado Prueba Censopas</th>

                                        <th class="align-middle">Tipo Intervención 2022</th>
                                        <th class="align-middle">Ipress Atencion 2022</th>
                                        <th class="align-middle">Servicio 2022</th>
                                        <th class="align-middle">Fecha 2022</th>
                                        <th class="align-middle">Resultados 2022</th>
                                        <th class="align-middle">Observaciones 2022</th>

                                        <th class="align-middle">Tipo Intervención 2022 02</th>
                                        <th class="align-middle">Ipress Atención 2022 02</th>
                                        <th class="align-middle">Servicio 2022 02</th>
                                        <th class="align-middle">Fecha 2022 02</th>
                                        <th class="align-middle">Resultados 2022 02</th>
                                        <th class="align-middle">Observaciones 2022 02</th>

                                        <th class="align-middle">Tipo Intervención 2022 03</th>
                                        <th class="align-middle">Ipress Atencion 2022 03</th>
                                        <th class="align-middle">Servicio 2022 03</th>
                                        <th class="align-middle">Fecha 2022 03</th>
                                        <th class="align-middle">Resultados 2022 03</th>
                                        <th class="align-middle">Observaciones 2022 03</th>

                                        <th class="align-middle">Tipo Intervención 2022 04</th>
                                        <th class="align-middle">Ipress Atencion 2022 04</th>
                                        <th class="align-middle">Servicio 2022 04</th>
                                        <th class="align-middle">Fecha 2022 04</th>
                                        <th class="align-middle">Resultados 2022 04</th>
                                        <th class="align-middle">Observaciones 2022 04</th>

                                        <th class="align-middle">Tipo Intervención 2022 05</th>
                                        <th class="align-middle">Ipress Atencion 2022 05</th>
                                        <th class="align-middle">Servicio 2022 05</th>
                                        <th class="align-middle">Fecha 2022 05</th>
                                        <th class="align-middle">Resultados 2022 05</th>
                                        <th class="align-middle">Observaciones 2022 05</th>

                                        <th class="align-middle">Tipo Intervención 2022 06</th>
                                        <th class="align-middle">Ipress Atencion 2022 06</th>
                                        <th class="align-middle">Servicio 2022 06</th>
                                        <th class="align-middle">Fecha 2022 06</th>
                                        <th class="align-middle">Resultados 2022 06</th>
                                        <th class="align-middle">Observaciones 2022 06</th>

                                        <th class="align-middle">Tipo Intervención 2022 07</th>
                                        <th class="align-middle">Ipress Atencion 2022 07</th>
                                        <th class="align-middle">Servicio 2022 07</th>
                                        <th class="align-middle">Fecha 2022 07</th>
                                        <th class="align-middle">Resultados 2022 07</th>
                                        <th class="align-middle">Observaciones 2022 07</th>

                                        <th class="align-middle">Tipo Intervención 2022 08</th>
                                        <th class="align-middle">Ipress Atencion 2022 08</th>
                                        <th class="align-middle">Servicio 2022 08</th>
                                        <th class="align-middle">Fecha 2022 08</th>
                                        <th class="align-middle">Resultados 2022 08</th>
                                        <th class="align-middle">Observaciones 2022 08</th>

                                        <th class="align-middle">Tipo Intervención 2022 09</th>
                                        <th class="align-middle">Ipress Atencion 2022 09</th>
                                        <th class="align-middle">Servicio 2022 09</th>
                                        <th class="align-middle">Fecha 2022 09</th>
                                        <th class="align-middle">Resultados 2022 09</th>
                                        <th class="align-middle">Observaciones 2022 09</th>

                                        <th class="align-middle">Tipo Intervención 2022 10</th>
                                        <th class="align-middle">Ipress Atencion 2022 10</th>
                                        <th class="align-middle">Servicio 2022 10</th>
                                        <th class="align-middle">Fecha 2022 10</th>
                                        <th class="align-middle">Resultados 2022 10</th>
                                        <th class="align-middle">Observaciones 2022 10</th>

                                        <th class="align-middle">Tipo Intervención 2022 11</th>
                                        <th class="align-middle">Ipress Atencion 2022 11</th>
                                        <th class="align-middle">Servicio 2022 11</th>
                                        <th class="align-middle">Fecha 2022 11</th>
                                        <th class="align-middle">Resultados 2022 11</th>
                                        <th class="align-middle">Observaciones 2022 11</th>

                                        <th class="align-middle">Tipo Intervención 2022 12</th>
                                        <th class="align-middle">Ipress Atencion 2022 12</th>
                                        <th class="align-middle">Servicio 2022 12</th>
                                        <th class="align-middle">Fecha 2022 12</th>
                                        <th class="align-middle">Resultados 2022 12</th>
                                        <th class="align-middle">Observaciones 2022 12</th>
                                    </tr>
                                </thead>
                                <div class="float-right col-md-3">
                                    <div class="mb-2">
                                        <div class="input-wrapper input-group-sm">
                                            <input id="demo-input-search2" class="form-control input" type="search" placeholder="Buscar por nombres o dni..." style="padding-left: 25px;">
                                            <i class="fa fa-search input-icon font-13"></i>
                                        </div>
                                    </div>
                                </div>
                                <tbody>
                                    <tr v-for="(format, key) in lists" class="font-9">
                                        <td class="align-middle text-center">[[ key+1 ]]</td>
                                        <td class="align-middle">[[ format.APELLIDOS_NOMBRES ]]</td>
                                        <td class="align-middle text-center">[[ format.SEXO ]]</td>
                                        <td class="align-middle text-center">[[ format.PUEBLO_ETNIA ]]</td>
                                        <td class="align-middle text-center">[[ format.LENGUA_MATERNA ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_NACIMIENTO ]]</td>
                                        <td class="align-middle text-center">[[ format.EDAD ]]</td>
                                        <td class="align-middle text-center">[[ format.TIPO_DOCUMENTO ]]</td>
                                        <td class="align-middle text-center">[[ format.NUMERO_DOCUMENTO ]]</td>
                                        <td class="align-middle text-center">[[ format.HISTORIA_CLINICA ]]</td>
                                        <td class="align-middle text-center">[[ format.MENOR_DE_EDAD ]]</td>
                                        <td class="align-middle text-center">[[ format.TIPO_DOC_APODERADO ]]</td>
                                        <td class="align-middle text-center">[[ format.DOCUMENTO_APODERADO ]]</td>
                                        <td class="align-middle text-center">[[ format.NOMBRE_APODERADO ]]</td>
                                        <td class="align-middle text-center">[[ format.TIPO_CASO ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_INGRESO_A_PADRON ]]</td>
                                        <td class="align-middle text-center">[[ format.PSEUDONIMO_CODIGO ]]</td>
                                        <td class="align-middle text-center">[[ format.TELEFONO ]]</td>
                                        <td class="align-middle text-center">[[ format.DIRECCION_ANTERIOR ]]</td>
                                        <td class="align-middle text-center">[[ format.REGION_ANTERIOR ]]</td>
                                        <td class="align-middle text-center">[[ format.PROVINCIA_ANTERIOR ]]</td>
                                        <td class="align-middle text-center">[[ format.DISTRITO_ANTERIOR ]]</td>
                                        <td class="align-middle text-center">[[ format.ANIOS_ANTERIOR ]]</td>
                                        <td class="align-middle text-center">[[ format.DIRECCION_ACTUAL ]]</td>
                                        <td class="align-middle text-center">[[ format.REGION_ACTUAL ]]</td>
                                        <td class="align-middle text-center">[[ format.PROVINCIA_ACTUAL ]]</td>
                                        <td class="align-middle text-center">[[ format.DISTRITO_ACTUAL ]]</td>
                                        <td class="align-middle text-center">[[ format.ANIOS_ACTUAL ]]</td>
                                        <td class="align-middle text-center">[[ format.TIPO_SEGURO ]]</td>
                                        <td class="align-middle text-center">[[ format.VALORES_RESULTADOS_PRUEBA_CENSOPAS ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_01 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_01 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_01 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_01 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_01 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_01 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_02 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_02 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_02 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_02 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_02 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_02 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_03 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_03 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_03 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_03 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_03 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_03 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_04 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_04 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_04 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_04 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_04 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_04 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_05 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_05 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_05 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_05 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_05 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_05 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_06 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_06 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_06 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_06 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_06 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_06 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_07 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_07 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_07 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_07 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_07 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_07 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_08 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_08 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_08 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_08 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_08 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_08 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_09 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_09 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_09 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_09 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_09 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_09 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_10 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_10 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_10 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_10 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_10 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_10 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_11 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_11 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_11 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_11 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_11 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_11 ]]</td>

                                        <td class="align-middle text-center">[[ format.TIPO_DE_INTERVENCION_2022_12 ]]</td>
                                        <td class="align-middle text-center">[[ format.IPRESS_ATENCION_2022_12 ]]</td>
                                        <td class="align-middle text-center">[[ format.SERVICIO_2022_12 ]]</td>
                                        <td class="align-middle text-center">[[ format.FECHA_2022_12 ]]</td>
                                        <td class="align-middle text-center">[[ format.RESULTADOS_2022_12 ]]</td>
                                        <td class="align-middle text-center">[[ format.OBSERVACIONES_2022_12 ]]</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <script src="./js/metals.js"></script>

@endsection

@section('javascript')
@endsection