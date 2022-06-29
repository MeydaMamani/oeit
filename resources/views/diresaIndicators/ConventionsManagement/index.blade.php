@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appCredMensual">
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
                                                <button class="btn btn-primary btn-sm m-1 font-11" id="search" type="button" @click="listPremature"><i class="fa fa-search"></i> Buscar</button>
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
                            <div class="row" id="cg_avance_regional" style="display: none;">
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
                                            <form action="" method="POST" id="formPrint">
                                                <input hidden name="red_print" id="red_print" value="">
                                                <input hidden name="distrito_print" id="distrito_print" value="">
                                                <input hidden name="establecimiento_print" id="establecimiento_print" value="">
                                                <input hidden name="anio_print" id="anio_print" value="">
                                                <input hidden name="mes_print" id="mes_print" value="">
                                                <button type="submit" name="exportarCSV" class="btn btn-outline-success m-1 btn-sm mb-2"><i class="mdi mdi-printer"></i> Imprimir</button>
                                            </form>
                                            <button type="button" class="btn btn-outline-danger m-1 btn-sm btn_information mb-2"><i class="mdi mdi-format-list-bulleted"></i> Ficha</button>
                                        </div>
                                        <div class="col-sm-3 swing animated">
                                            <div class="card" style="box-shadow: 5px 5px 5px #999;">
                                                <div class="card-body p-2">
                                                    <div class="col-md-12 text-center">
                                                        <div id="chart_advance" class="css-bar m-b-0 css-bar-info css-bar-0"><i class="mdi mdi-receipt"></i>
                                                        </div>
                                                        <h4 class="advance text-primary mb-0"><span></span></h4>
                                                        <h5 class="text-muted m-0 font-18">Avance</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                           <div class="row justify-content-center">
                                                <div class="col-sm-6 swing animated">
                                                    <div class="card" style="box-shadow: 5px 5px 5px #999;">
                                                        <div class="card-body p-1">
                                                            <p class="card-title text-muted text-center font-13 mb-0">Cumplen</h4>
                                                            <div class="font-medium text-center justify-content-center d-flex">
                                                                <label class="text-success cg_text_cumplen font-20"> </label> <i class="mdi mdi-check text-success font-20"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 swing animated">
                                                    <div class="card" style="box-shadow: 5px 5px 5px #999;">
                                                        <div class="card-body p-1">
                                                            <p class="card-title text-muted text-center font-13 mb-0">No Cumplen</h4>
                                                            <div class="font-medium text-center justify-content-center d-flex">
                                                                <label class="text-danger cg_text_nocumplen font-20"> </label> <i class="mdi mdi-close text-danger font-20"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-7 swing animated">
                                                    <div class="card" style="box-shadow: 5px 5px 5px #999;">
                                                        <div class="card-body p-1">
                                                            <p class="card-title text-muted text-center font-13 mb-0">Total Registros</h4>
                                                            <div class="font-medium text-center justify-content-center d-flex">
                                                                <label class="text-secondary cg_text_total font-20"> </label> <i class="mdi mdi-close text-secondary font-20"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="cg_mitable_all" class="text-center"></div>
                        </div>
                        <div class="col-md-4" id="cg_avance_distrital" style="display: none;">
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
    <script src="./js/credMes.js"></script>
    <script>
        $(document).ready(function(){
            $("#search").click();
        });
    </script>

@endsection

@section('javascript')
@endsection