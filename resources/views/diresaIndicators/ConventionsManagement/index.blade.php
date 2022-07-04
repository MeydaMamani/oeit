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
                            <div class="card card-primary">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-16">Lista de Indicadores</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check text-start check_covid">
                                                <input class="form-check-input" type="radio" name="myradio" id="radio1" value="adolescHierro">
                                                <label class="form-check-label font-14" for="myradio">Adolescente con Hierro y Ácido Fólico</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline text-start check_covid">
                                                <input class="form-check-input" type="radio" name="myradio" id="radio2" value="dosControlsCred">
                                                <label class="form-check-label font-14" for="myradio">2 Controles Cred</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline text-start check_covid">
                                                <input class="form-check-input" type="radio" name="myradio" id="radio3" value="altaBasicaOdonto">
                                                <label class="form-check-label font-14" for="myradio">Alta Básica Odontológica</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline text-start check_covid">
                                                <input class="form-check-input" type="radio" name="myradio" id="radio4" value="bcbHvb">
                                                <label class="form-check-label font-14" for="myradio">Vacunas BCG y HVB</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline text-start check_covid">
                                                <input class="form-check-input" type="radio" name="myradio" id="radio4" value="cancerUterino">
                                                <label class="form-check-label font-14" for="myradio">Cáncer de Cuello Uterino</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline text-start check_covid">
                                                <input class="form-check-input" type="radio" name="myradio" id="radio3" value="recoveredPatients">
                                                <label class="form-check-label font-14" for="myradio">Pacientes Recuperados</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FORMULARIO PARA FILTROS -->
                        <div class="col-md-4">
                            <div class="card card-info">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-16">Vacunas BCG HVB</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <form method="post" id="formulario">
                                    <div class="card-body pt-2 pb-2">
                                        <div class="row">
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
                                        </div>
                                        <div class="row">
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
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-primary btn-sm m-1 font-11" id="search" type="button" @click="listVaccineBcgHvb"><i class="fa fa-search"></i> Buscar</button>
                                            <button class="btn btn-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top: -5px; margin-bottom: 8px;">
                    <div class="content-conventions" v-show="bcg_hvb">
                        {{-- <div class="overlay-wrapper">
                            <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>
                        </div> --}}
                        @include('diresaIndicators.ConventionsManagement.VaccineBcgHvb.index')
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
    <script src="plugins/chart.js/Chart.min.js"></script>
    <script src="./js/Conventions.js"></script>
@endsection

@section('javascript')
@endsection