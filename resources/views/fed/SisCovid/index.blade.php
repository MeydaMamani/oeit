@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appSisCovid">
        <section class="content">
            <section class="content-header pb-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="mb-0">Sis Covid - <span class="name_mes">[[ nameMonth ]]</span> <span class="name_anio">[[ nameYear ]]</span></h5>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="#">Fed</a></li>
                                <li class="breadcrumb-item active">Profesionales</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-9"></div>
                        <div class="col-3">
                            <marquee width="100%" direction="left" height="18px">
                                <p class="font-10 text-primary"><b>Fuente: </b> BD HisMinsa con Fecha [[ date_his ]] a las 08:30 horas</p>
                            </marquee>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 pl-3">
                            <div class="card p-0">
                                <div class="card-body p-1 text-center">
                                    <input type="text" class="knob" value="0" data-readonly="true" data-width="90" data-height="90" data-fgColor="#00c0ef">
                                    <div class="knob-label text-primary">Avance</div>
                                </div>
                            </div>
                            <div class="card" style="border-color: #337ab7;">
                                <div class="card-body p-2">
                                    <form method="post" id="formulario">
                                        <div class="col-md-12 mb-2 p-0">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="red" name="red" id="red" @change="filtersDistricts" v-select2>
                                                <option value="">Seleccione Red</option>
                                                <option v-for="format in provinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-2 p-0">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito" name="distrito" id="distrito" v-select2>
                                                <option value="">Seleccione Distrito</option>
                                                <option v-for="format in districts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-2 p-0">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio" id="anio" name="anio" v-select2>
                                                <option value="">Seleccione año</option>
                                                <option value="2019">2019</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-2 p-0">
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
                                            </select>
                                        </div>
                                        <div class="col-md-12 p-0">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-primary btn-sm m-1 font-11" id="search" type="button" @click="listSisCovid"> Buscar</button>
                                                <button class="btn btn-secondary btn-sm m-1 font-11" type="button" id="clear2"> Limpiar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 pl-3">
                            <div class="info-box elevation-2 p-2">
                                <div class="info-box-content">
                                    <span class="info-box-text font-15 text-center">Cantidad Registros</span>
                                    <div class="d-flex">
                                        <div class="col-md-6 justify-content-center align-items-center d-flex">
                                            <img src="./img/user_cant.png" width="38" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-6 text-secondary font-27">[[ total ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box elevation-2 p-2" id="all">
                                <div class="info-box-content">
                                    <span class="info-box-text font-15 text-center">Cumplen</span>
                                    <div class="d-flex">
                                        <div class="col-md-6 justify-content-center align-items-center d-flex">
                                            <img src="./img/boy.png" width="38" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-6 text-success font-27">[[ cumple ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box elevation-2 p-2" id="all">
                                <div class="info-box-content">
                                    <div id="info" class="d-flex justify-content-center align-items-center">
                                        <button class="btn btn-sm btn-outline-light" @click="listNoCumplen">Ver</button>
                                    </div>
                                    <span class="info-box-text font-15 text-center">No Cumplen</span>
                                    <div class="d-flex">
                                        <div class="col-md-6 justify-content-center align-items-center d-flex">
                                            <img src="./img/boy_x.png" width="38" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-6 text-danger font-27">[[ no_cumple ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" id="export_data" name="exportarCSV" class="btn btn-outline-success btn-sm font-14 w-100" @click="PrintNominal"><i class="fa fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                        <div class="col-md-8 mb-1">
                            <div class="table-responsive nominalTable" id="profesional">
                                <table id="demo-foo-addrow2" class="table table-hover table-striped" data-page-size="20" data-limit-navigation="10">
                                    <thead>
                                        <tr class="font-10 text-center" style="background: #e0eff5;">
                                            <th class="align-middle">#</th>
                                            <th class="align-middle">Provincia</th>
                                            <th class="align-middle">Distrito</th>
                                            <th class="align-middle">Establecimiento</th>
                                            <th class="align-middle">Documento</th>
                                            <th class="align-middle">Fecha Ejecución Prueba</th>
                                            <th class="align-middle">Fecha Registro</th>
                                            <th class="align-middle">Tipo</th>
                                            <th class="align-middle">Ficha 300</th>
                                            <th class="align-middle">Fecha Receta</th>
                                            <th class="align-middle">Fecha Entrega</th>
                                            <th class="align-middle">Cumple</th>
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
                                        <tr v-for="(format, key) in lists" class="font-10">
                                            <td class="align-middle text-center">[[ key+1 ]]</td>
                                            <td class="align-middle text-center">[[ format.DESC_PROV ]]</td>
                                            <td class="align-middle text-center">[[ format.DESC_DIST ]]</td>
                                            <td class="align-middle text-center">[[ format.ESTABLECIMIENTO_EJECUTA ]]</td>
                                            <td class="align-middle text-center">[[ format.NUMERO_DOCUMENTO ]]</td>
                                            <td class="align-middle text-center">[[ format.FECHA_EJECUCION_PRUEBA ]]</td>
                                            <td class="align-middle text-center">[[ format.FECHA_REGISTRO2 ]]</td>
                                            <td class="align-middle text-center">[[ format.TIPO ]]</td>
                                            <td class="align-middle text-center">[[ format.FICHA_300_FECHA_DEL_SEGUIMIENTO ]]</td>
                                            <td class="align-middle text-center">[[ format.FECHA_RECETA ]]</td>
                                            <td class="align-middle text-center">[[ format.FECHA_ENTREGA ]]</td>
                                            <template v-if="format.FED == 'CUMPLE'">
                                                <td class="align-middle text-center"><span class="badge bg-success">Cumple</span></td>
                                            </template>
                                            <template v-else>
                                                <td class="align-middle text-center"><span class="badge bg-danger">No Cumple</span></td>
                                            </template>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="15">
                                                <div class="">
                                                    <ul class="pagination"></ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <script src="./js/sisCovid.js"></script>
    <script>
        $(document).ready(function(){
            $("#search").click();
        });
    </script>

@endsection

@section('javascript')
@endsection