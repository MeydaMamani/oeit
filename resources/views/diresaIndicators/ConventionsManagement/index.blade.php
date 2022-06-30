@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appConventions">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="mb-0">Convenios de Gestión</h5>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item active"><a href="#">Convenios de Gestión</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check text-start check_covid">
                                        <input class="form-check-input" type="radio" name="myradio" id="radio1" value="adolescHierro">
                                        <label class="form-check-label font-13" for="myradio">Adolescente con Hierro y Ácido Fólico</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-inline text-start check_covid">
                                        <input class="form-check-input" type="radio" name="myradio" id="radio2" value="dosControlsCred">
                                        <label class="form-check-label font-13" for="myradio">2 Controles Cred</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-inline text-start check_covid">
                                        <input class="form-check-input" type="radio" name="myradio" id="radio3" value="altaBasicaOdonto">
                                        <label class="form-check-label font-13" for="myradio">Alta Básica Odontológica</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-inline text-start check_covid">
                                        <input class="form-check-input" type="radio" name="myradio" id="radio4" value="bcbHvb">
                                        <label class="form-check-label font-13" for="myradio">Vacunas BCG y HVB</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-inline text-start check_covid">
                                        <input class="form-check-input" type="radio" name="myradio" id="radio4" value="cancerUterino">
                                        <label class="form-check-label font-13" for="myradio">Cáncer de Cuello Uterino</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-check-inline text-start check_covid">
                                        <input class="form-check-input" type="radio" name="myradio" id="radio3" value="recoveredPatients">
                                        <label class="form-check-label font-13" for="myradio">Pacientes Recuperados</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FORMULARIO PARA FILTROS -->
                        <div class="col-md-4">
                            <div class="card" style="border-color: #337ab7;">
                                <div class="card-body p-2">
                                    <h6 class="text-primary text-center p-1 font-11 font-weight-bold">Vacunas BCG y HVB</h6>
                                    <form method="post" id="formulario">
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <select class="form-control select2 show-tick" style="width: 100%;" v-model="red" name="red" id="red" @change="filtersDistricts" v-select2>
                                                    <option value="">Seleccione Red</option>
                                                    <option v-for="format in provinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito" name="distrito" id="distrito" v-select2>
                                                    <option value="">Seleccione Distrito</option>
                                                    <option v-for="format in districts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio" id="anio" name="anio" v-select2>
                                                    <option value="">Seleccione año</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2022">2022</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
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
                                        </div>
                                        <div class="col-md-12 p-0">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-primary btn-sm m-1 font-11" id="search" type="button" @click="listVaccineBcgHvb"><i class="fa fa-search"></i> Buscar</button>
                                                <button class="btn btn-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top: -5px; margin-bottom: 8px;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row" id="cg_avance_regional">
                                <h5 class="col-md-12 text-center mb-2 font-14" style="color: #174d9d;"><span class="name_red"></span> / <span class="name_dist"></span> - <span class="name_mes"></span> <span class="name_anio"></span></h5>
                                <div class="col-md-4">
                                    <div class="border border-primary">
                                        <h6 class="p-1 text-center m-0">Avance Regional</h6>
                                        <div style="height: 104px;" id="micarga"></div>
                                    </div>
                                </div>
                                 <div class="col-md-8 p-0">
                                    <div class="row">
                                        <div class="col-md-2 text-center p-1">
                                            <button type="submit" id="export_data" name="exportarCSV" class="btn btn-outline-success m-1 btn-sm mb-2 font-11" @click=""><i class="fa fa-print"></i> Imprimir</button>
                                            <button type="button" class="btn btn-outline-danger m-1 btn-sm btn_information mb-2 font-11" data-toggle="modal" data-target="#ModalInformacion"><i class="fa fa-list"></i> Ficha</button>
                                        </div>
                                        <div class="col-sm-3 swing animated">
                                            <div class="card p-0">
                                                <div class="card-body p-1 text-center">
                                                    <input type="text" class="knob" value="0" data-readonly="true" data-width="90" data-height="90" data-fgColor="#00c0ef">
                                                    <div class="knob-label text-primary">Avance</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                           <div class="row justify-content-center">
                                                <div class="col-md-6 col-sm-4">
                                                    <div class="info-box elevation-2 p-1">
                                                        <div class="info-box-content">
                                                            <span class="info-box-text font-13 text-center">Cumplen</span>
                                                            <div class="d-flex">
                                                                <div class="col-md-6 justify-content-center align-items-center d-flex">
                                                                    <img src="./img/boy.png" width="33" alt="icon cantidad total">
                                                                </div>
                                                                <span class="info-box-number col-md-6 text-success font-20 mt-0">[[ cumple ]]</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-4">
                                                    <div class="info-box elevation-2 p-1" id="all">
                                                        <div class="info-box-content">
                                                            <span class="info-box-text font-13 text-center">No Cumplen</span>
                                                            <div class="d-flex">
                                                                <div class="col-md-6 justify-content-center align-items-center d-flex">
                                                                    <img src="./img/boy_x.png" width="33" alt="icon cantidad total">
                                                                </div>
                                                                <span class="info-box-number col-md-6 text-danger font-20 mt-0">[[ no_cumple ]]</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 col-sm-4">
                                                    <div class="info-box elevation-2 p-1">
                                                        <div class="info-box-content">
                                                            <span class="info-box-text font-13 text-center">Cantidad Registros</span>
                                                            <div class="d-flex">
                                                                <div class="col-md-6 justify-content-center align-items-center d-flex">
                                                                    <img src="./img/user_cant.png" width="33" alt="icon cantidad total">
                                                                </div>
                                                                <span class="info-box-number col-md-6 text-secondary font-20 mt-0">[[ total ]]</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table_all mb-1 mt-1" id="tmz_neonatal">
                                <table id="demo-foo-addrow2" class="table table-hover table-striped" data-page-size="20" data-limit-navigation="10">
                                    <thead>
                                        <tr class="font-10 text-center" style="background: #e0eff5;">
                                            <th class="align-middle">#</th>
                                            <th class="align-middle">Periodo</th>
                                            <th class="align-middle">Ubigeo</th>
                                            <th class="align-middle">Provincia</th>
                                            <th class="align-middle">Distrito</th>
                                            <th class="align-middle">Id Establecimiento</th>
                                            <th class="align-middle">Establecimiento</th>
                                            <th class="align-middle">Categoria</th>
                                            <th class="align-middle">Documento</th>
                                            <th class="align-middle">Fecha Nacido</th>
                                            <th class="align-middle">Num Hvb</th>
                                            <th class="align-middle">Num Bcg</th>
                                            <th class="align-middle">Den</th>
                                            <th class="align-middle">Num</th>
                                        </tr>
                                    </thead>
                                    <div class="float-right col-md-4">
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
                                            <td class="align-middle text-center">[[ format.PERIODO ]]</td>
                                            <td class="align-middle text-center">[[ format.UBIGEO ]]</td>
                                            <td class="align-middle text-center">[[ format.PROVINCIA ]]</td>
                                            <td class="align-middle text-center">[[ format.DISTRITO ]]</td>
                                            <td class="align-middle text-center">[[ format.ID_EESS ]]</td>
                                            <td class="align-middle text-center">[[ format.EESS_NOMBRE ]]</td>
                                            <td class="align-middle text-center">[[ format.Categoria ]]</td>
                                            <td class="align-middle text-center">[[ format.NUM_DOC ]]</td>
                                            <td class="align-middle text-center">[[ format.FE_NACIDO ]]</td>
                                            <td class="align-middle text-center">[[ format.NUM_HVB ]]</td>
                                            <td class="align-middle text-center">[[ format.NUM_BCG ]]</td>
                                            <td class="align-middle text-center">[[ format.DEN ]]</td>
                                            <td class="align-middle text-center">[[ format.NUM ]]</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="20">
                                                <div class="">
                                                    <ul class="pagination"></ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4" id="cg_avance_distrital">
                            <div class="text-center" id="buttons_red"></div>
                            <!-- GRAFICA POR DISTRITOS -->
                            <div class="col-md-12 mt-2">
                                <div class="border border-secondary">
                                    <h6 class="p-2 text-center m-0">Avance Distrital</h6>
                                    <div class="dac" style="height: 345px; padding-right: 10px;"> </div>
                                </div><br>
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
                        <div class="col-12 text-end">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <img src="./img/fichas/inf_cred.png" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/Conventions.js"></script>
    <script>
        $(document).ready(function(){
            $("#search").click();
        });
    </script>

@endsection

@section('javascript')
@endsection