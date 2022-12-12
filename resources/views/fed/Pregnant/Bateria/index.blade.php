@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appBateria">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="mb-0">Gestantes de Bateria Completa CG01 - <span class="name_mes">[[ nameMonth ]]</span> <span class="name_anio">[[ nameYear ]]</span></h5>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="#">Fed</a></li>
                                <li class="breadcrumb-item active">Bateria Cmpleta</li>
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
                        <div class="col-md-5 row pl-3">
                            <div class="col-md-4 col-sm-4">
                                <div class="info-box elevation-2 p-1">
                                    <div class="info-box-content">
                                        <span class="info-box-text font-13 text-center">Cantidad Registros</span>
                                        <div class="d-flex">
                                            <div class="col-md-6 justify-content-center align-items-center d-flex">
                                                <img src="./img/pregnant_total.png" width="33" alt="icon cantidad total">
                                            </div>
                                            <span class="info-box-number col-md-6 text-secondary font-23">[[ total ]]</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="info-box elevation-2 p-1" id="all">
                                    <div class="info-box-content">
                                        <span class="info-box-text font-13 text-center">Cumplen</span>
                                        <div class="d-flex">
                                            <div class="col-md-6 justify-content-center align-items-center d-flex">
                                                <img src="./img/pregnant_correct.png" width="33" alt="icon cantidad total">
                                            </div>
                                            <span class="info-box-number col-md-6 text-success font-23">[[ cumple ]]</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="info-box elevation-2 p-1" id="all">
                                    <div class="info-box-content">
                                        <div id="info" class="d-flex justify-content-center align-items-center">
                                            <button class="btn btn-sm btn-outline-light" @click="listNoCumplen">Ver</button>
                                        </div>
                                        <span class="info-box-text font-13 text-center">No Cumplen</span>
                                        <div class="d-flex">
                                            <div class="col-md-6 justify-content-center align-items-center d-flex">
                                                <img src="./img/pregnant_incorrect.png" width="33" height="45" alt="icon cantidad total">
                                            </div>
                                            <span class="info-box-number col-md-6 text-danger font-23">[[ no_cumple ]]</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- avance por region --}}
                            <div class="col-md-4 col-sm-4" v-for="(format, key) in advanceReg">
                                <div class="info-box elevation-2">
                                    <div class="info-box-content">
                                        <div class="d-flex">
                                            <div class="col-md-6 justify-content-center text-center p-0">
                                                <img :src='"./img/" + format.PROVINCIA + ".png"' width="50" alt="icon cantidad total">
                                            </div>
                                            <span class="info-box-number text-muted col-md-6">[[ format.ADVANCE ]]%</span>
                                        </div>
                                        <div class="progress">
                                            <template v-if="format.ADVANCE <= 49">
                                                <div class="progress-bar bg-danger wow animated progress-animated" :style='"width:" + format.ADVANCE + "%"' role="progressbar"></div>
                                            </template>
                                            <template v-else-if="format.ADVANCE > 49 && format.AVANCE <= 59">
                                                <div class="progress-bar bg-warning wow animated progress-animated" :style='"width:" + format.ADVANCE + "%"' role="progressbar"></div>
                                            </template>
                                            <template v-else>
                                                <div class="progress-bar bg-success wow animated progress-animated" :style='"width:" + format.ADVANCE + "%"' role="progressbar"></div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-4 text-center p-1">
                            <button type="submit" id="export_data" name="exportarCSV" class="btn btn-outline-success m-1 btn-sm mb-2 font-11" @click="PrintNominal"><i class="fa fa-print"></i> Imprimir</button>
                            <button type="button" class="btn btn-outline-danger m-1 btn-sm btn_information mb-2 font-11" data-toggle="modal" data-target="#ModalInformacion"><i class="fa fa-list"></i> Ficha</button>
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm m-1 mb-2 font-11"><i class="fa fa-reply"></i> Regresar</a>
                            <button class="btn btn-outline-primary m-1 btn-sm mb-2 font-11" @click="listBateria"><i class="fa fa-check"></i> Ver Todo</button>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div id="table_resume">
                                <div class="table-responsive" id="prematuro_resume">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr class="font-9 text-center" style="background: #e0eff5;">
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Provincia</th>
                                                <th class="align-middle">Distrito</th>
                                                <th class="align-middle">Avan</th>
                                                <th class="align-middle">Meta</th>
                                                <th class="align-middle">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(format, key) in listsResum" class="font-8">
                                                <td class="align-middle text-center">[[ key+1 ]]</td>
                                                <td class="align-middle">
                                                    <template v-if="format.PROVINCIA == 'DANIEL ALCIDES CARRION'">DANIEL A. CARRION</template>
                                                    <template v-else>[[ format.PROVINCIA ]]</template>
                                                </td>
                                                <td class="align-middle">[[ format.DISTRITO ]]</td>
                                                <td class="align-middle text-center">[[ format.NUMERADOR ]]</td>
                                                <td class="align-middle text-center">[[ format.DENOMINADOR ]]</td>
                                                <template v-if="format.AVANCE > 59">
                                                    <td class="bg-success text-white align-middle text-center">[[ format.AVANCE ]]%</td>
                                                </template>
                                                <template v-else-if="format.AVANCE <= 49">
                                                    <td class="bg-danger text-white align-middle text-center">[[ format.AVANCE ]]%</td>
                                                </template>
                                                <template v-else-if="format.AVANCE > 49 && format.AVANCE <= 59">
                                                    <td class="bg-warning text-white align-middle text-center">[[ format.AVANCE ]]%</td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <div class="card" style="border-color: #337ab7;">
                                <div class="card-body p-2">
                                    <form method="post" id="formulario">
                                        <div class="col-md-12 mb-1 p-0">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="red" name="red" id="red" @change="filtersDistricts" v-select2>
                                                <option value="">Seleccione Red</option>
                                                <option v-for="format in provinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-1 p-0">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito" name="distrito" id="distrito" v-select2>
                                                <option value="">Seleccione Distrito</option>
                                                <option v-for="format in districts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-1 p-0">
                                            <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio" id="anio" name="anio" v-select2>
                                                <option value="">Seleccione año</option>
                                                <option value="2019">2019</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-1 p-0">
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
                                                <button class="btn btn-primary btn-sm m-1 font-11" id="search" type="button" @click="listBateria"><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn btn-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- MAPA AVANCE REGIONAL -->
                        <div class="col-md-2 pl-3"><br>
                            <div class="card p-0">
                                <div class="card-body p-1 text-center">
                                    <input type="text" class="knob" value="0" data-readonly="true" data-width="90" data-height="90" data-fgColor="#00c0ef">
                                    <div class="knob-label text-primary">Avance</div>
                                </div>
                            </div>
                            <div class="card p-0">
                                <div class="card-body p-1">
                                    <div class="d-flex">
                                        <div class="col-md-6 pt-1 text-center">
                                            <h3 class="total text-success"><b>[[ total ]]</b>
                                            </h3>
                                            <h6 class="text-muted">Meta</h6>
                                        </div>
                                        <div class="col-md-6 pt-1 text-center">
                                            <h3 class="text-suple" style="color: #c8c817;"><b>[[ cumple ]]</b>
                                            </h3>
                                            <h6 class="text-muted">Avance</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- TABLA NOMINAL -->
                        <div class="col-md-10 mb-1">
                            <div class="table-responsive nominalTable" id="bateria_completa">
                                <table id="demo-foo-addrow2" class="table table-hover table-striped" data-page-size="20" data-limit-navigation="10">
                                    <thead>
                                        <tr class="font-10 text-center" style="background: #d9d9d9;">
                                            <th class="align-middle">#</th>
                                            <th class="align-middle">Provincia</th>
                                            <th class="align-middle">Distrito</th>
                                            <th class="align-middle">Establecimiento</th>
                                            <th class="align-middle">Tipo Documento</th>
                                            <th class="align-middle">Documento</th>
                                            <th class="align-middle">Fecha Nacimiento</th>
                                            <th class="align-middle">Captada</th>
                                            <th class="align-middle">TMZ VIF</th>
                                            <th class="align-middle" id="fields_bateria_head">Tmz Anemia</th>
                                            <th class="align-middle" id="fields_bateria_head">Sifilis</th>
                                            <th class="align-middle" id="fields_bateria_head">VIH</th>
                                            <th class="align-middle" id="fields_bateria_head">Bacteriuria</th>
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
                                            <td class="align-middle text-center">[[ format.PROVINCIA ]]</td>
                                            <td class="align-middle text-center">[[ format.DISTRITO ]]</td>
                                            <td class="align-middle text-center">[[ format.IPRESS ]]</td>
                                            <td class="align-middle text-center">[[ format.TIPO_DOC ]]</td>
                                            <td class="align-middle text-center">[[ format.DOCUMENTO ]]</td>
                                            <td class="align-middle text-center">[[ format.Fecha_Nacimiento_Paciente ]]</td>
                                            <td class="align-middle text-center">[[ format.CAPTADA ]]</td>
                                            <td class="align-middle text-center">[[ format.TMZ_VIF ]]</td>
                                            <td class="align-middle text-center">[[ format.TMZ_ANEMIA ]]</td>
                                            <td class="align-middle text-center">[[ format.SIFILIS ]]</td>
                                            <td class="align-middle text-center">[[ format.VIH ]]</td>
                                            <td class="align-middle text-center">[[ format.BACTERIURIA ]]</td>
                                            <template v-if="format.MIDE == 'SI'">
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
        <!-- MODAL INFORMACION-->
        <div class="modal fade" id="ModalInformacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="col-12 atext-end">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <img src="./img/fichas/inf_bateria.png" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/bateria.js"></script>
    <script>
        $(document).ready(function(){
            $("#search").click();
        });
    </script>

@endsection

@section('javascript')
@endsection