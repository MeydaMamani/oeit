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
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio1" name="customRadio">
                                                <label for="customRadio1" class="custom-control-label font-14 font-weight-normal" @click="Adolescents">Adolescente con Hierro y Ácido Fólico</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio2" name="customRadio">
                                                <label for="customRadio2" class="custom-control-label font-14 font-weight-normal" @click="twoCtrlsCred">2 Controles Cred</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio3" name="customRadio">
                                                <label for="customRadio3" class="custom-control-label font-14 font-weight-normal">Alta Básica Odontológica</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio4" name="customRadio">
                                                <label for="customRadio4" class="custom-control-label font-14 font-weight-normal" @click="bcgHvb">Vacunas BCG y HVB</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio5" name="customRadio">
                                                <label for="customRadio5" class="custom-control-label font-14 font-weight-normal">Cáncer de Cuello Uterino</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="customRadio6" name="customRadio">
                                                <label for="customRadio6" class="custom-control-label font-14 font-weight-normal" @click="RecovPatients">Pacientes Recuperados</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FORMULARIO PARA FILTROS -->
                        <div class="col-md-4">
                            {{-- <div class="card card-success" v-show="AdolescentsFolicAcid">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-15">Adolescente con Hierro y Ácido Fólico</h3>
                                </div>
                                <form method="post" id="formulario">
                                    <div class="card-body pt-2 pb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="red" name="red2" id="red2" @change="filtersDistricts" v-select2>
                                                        <option value="">Seleccione Red</option>
                                                        <option v-for="format in provinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito" name="distrito2" id="distrito2" @change="filtersEstablishment" v-select2>
                                                    <option value="">Seleccione Distrito</option>
                                                    <option v-for="format in districts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control select2 show-tick" style="width: 100%;" v-model="stablishment" name="stablishment2" id="stablishment2" v-select2>
                                                    <option value="">Seleccione Establecimiento</option>
                                                    <option v-for="format in listEstablishment" :value="format.Nombre_Establecimiento">[[ format.Nombre_Establecimiento ]]</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio" id="anio2" name="anio2" v-select2>
                                                        <option value="">Seleccione año</option>
                                                        <option value="2019">2019</option>
                                                        <option value="2020">2020</option>
                                                        <option value="2021">2021</option>
                                                        <option value="2022">2022</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="mes" id="mes2" name="mes2" v-select2>
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
                                            <button class="btn btn-outline-success btn-sm m-1 font-11" id="search" type="button" @click="listVaccineBcgHvb"><i class="fa fa-print"></i> Descargar Excel</button>
                                            <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                            <div class="card card-success" v-show="TwoCtrlCred">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-15">2 Controles Cred</h3>
                                </div>
                                <form method="post" id="formulario">
                                    <div class="card-body pt-2 pb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="red3" name="red3" id="red3" @change="filtersDistricts3" v-select2>
                                                        <option value="">Seleccione Red</option>
                                                        <option v-for="format in provinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito3" name="distrito3" id="distrito3" v-select2>
                                                    <option value="">Seleccione Distrito</option>
                                                    <option v-for="format in districts3" :value="format.Distrito">[[ format.Distrito ]]</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio3" id="anio3" name="anio3" v-select2>
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
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="mes3" id="mes3" name="mes3" v-select2>
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
                                            <button class="btn btn-outline-success btn-sm m-1 font-11" id="search" type="button" @click="printTwoCtrlCred"><i class="fa fa-print"></i> Descargar Excel</button>
                                            <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card card-info" v-show="vaccineBcgHvb">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-15">Vacunas BCG HVB</h3>
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
                                            <button class="btn btn-outline-primary btn-sm m-1 font-11" id="search" type="button" @click="listVaccineBcgHvb"><i class="fa fa-search"></i> Buscar</button>
                                            <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card card-success" v-show="recoveredPatients">
                                <div class="card-header pl-3 pr-3 pt-2 pb-1">
                                    <h3 class="card-title font-15">Pacientes Recuperados</h3>
                                </div>
                                <form method="post" id="formulario">
                                    <div class="card-body pt-2 pb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio2" id="anio2" name="anio2" v-select2>
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
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="mes2" id="mes2" name="mes2" v-select2>
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
                                            <button class="btn btn-outline-success btn-sm m-1 font-11" id="search" type="button" @click="printRecovPtient"><i class="fa fa-print"></i> Descargar Excel</button>
                                            <button class="btn btn-outline-secondary btn-sm m-1 font-11" type="button" id="clear2"><i class="fa fa-broom"></i> Limpiar</button>
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
                        @include('diresaIndicators.Conventions.VaccineBcgHvb.index')
                    </div>
                </div>
            </section>
        </section>
    </div>
    <script src="plugins/chart.js/Chart.min.js"></script>
    <script src="./js/Conventions.js"></script>
@endsection

@section('javascript')
@endsection