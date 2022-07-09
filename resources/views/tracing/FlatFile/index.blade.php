@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appFlatFile">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="mb-0">Archivo Plano</span></h5>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="#">Fed</a></li>
                                <li class="breadcrumb-item active">Archivo Plano</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card card-success border border-success">
                                <div class="card-header">
                                    <h3 class="card-title">Archivo Plano</h3>
                                </div>
                                <form>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1" class="font-13">Seleccione una Red:</label>
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="red" name="red" id="red" @change="filtersDistricts" v-select2>
                                                        <option value="">Seleccione Red</option>
                                                        <option v-for="format in listProvinces" :value="format.Codigo_Red">[[ format.Red ]]</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1" class="font-13">Seleccione un Distrito:</label>
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="distrito" name="distrito" id="distrito" @change="filtersEstablishment" v-select2>
                                                        <option value="">Seleccione Distrito</option>
                                                        <option v-for="format in districts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1" class="font-13">Seleccione Establecimiento:</label>
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="stablishment" name="stablishment" id="stablishment" v-select2>
                                                        <option value="">Seleccione Establecimiento</option>
                                                        <option v-for="format in listEstablishment" :value="format.Nombre_Establecimiento">[[ format.Nombre_Establecimiento ]]</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1" class="font-13">Seleccione Ups:</label>
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="name_ups" name="name_ups" id="name_ups" v-select2>
                                                        <option value="">Seleccione Ups</option>
                                                        <option v-for="format in listUps" :value="format.Distrito">[[ format.Descripcion_Ups ]]</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1" class="font-13">Seleccione Año:</label>
                                                    <select class="form-control select2 show-tick" style="width: 100%;" v-model="anio" id="anio" name="anio" v-select2>
                                                        <option value="">Seleccione año</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="exampleInputEmail1" class="font-13">Seleccione Mes de Evaluación:</label>
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
                                        </div>
                                    </div>
                                    <div class="card-footer text-center p-1">
                                        <button type="button" id="export_data" name="exportarCSV" class="btn btn-outline-success btn-sm m-2" @click="PrintNominal"><i class="fa fa-download"></i> Descargar Excel</button>
                                    </div>
                                </form>
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
    <script src="./js/archivo_plano.js"></script>
    <script>
        $(document).ready(function(){
            $("#search").click();
        });
    </script>

@endsection

@section('javascript')
@endsection