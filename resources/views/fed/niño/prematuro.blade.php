@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appPremature">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="mb-0">Niños Prematuros CG03 - Mayo 2022</h5>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="#">Fed</a></li>
                                <li class="breadcrumb-item"><a href="#">Niños</a></li>
                                <li class="breadcrumb-item active">Prematuros</li>
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
                                <p class="font-10 text-primary"><b>Fuente: </b> BD Padrón Nominal con Fecha <?php echo '12-12-2022'; ?> a las 08:30 horas</p>
                            </marquee>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 row pl-3">
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <span class="info-box-text font-13 text-center mb-2">Cantidad Registros</span>
                                    <div class="row">
                                        <div class="col-md-6 justify-content-center align-items-center d-flex">
                                            <img src="./img/user_cant.png" width="33" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-6 text-secondary font-23">[[ total ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <span class="info-box-text font-13 text-center mb-2">Suplementados</span>
                                    <div class="row">
                                        <div class="col-md-6 justify-content-center align-items-center d-flex">
                                            <img src="./img/boy.png" width="33" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-6 text-success font-23">[[ suplementado ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <span class="info-box-text font-13 text-center mb-2">No Suplementados</span>
                                    <div class="row">
                                        <div class="col-md-6 justify-content-center align-items-center d-flex">
                                            <img src="./img/boy_x.png" width="33" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-6 text-danger font-23">[[ no_suplementado ]]</span>
                                    </div>
                                </div>
                            </div>
                            {{-- avance por region --}}
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <div class="row">
                                        <div class="col-md-6 justify-content-center text-center">
                                            <img src="./img/dac.png" width="65" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number text-muted col-md-6">1,410</span>
                                    </div>
                                    {{-- <div class="progress">
                                        <div class="progress-bar bg-danger wow animated progress-animated" id="progress_dac" style="width: 50%; height:6px;" role="progressbar"></div>
                                    </div> --}}
                                    <div class="progress">
                                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar"
                                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                          <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <div class="row">
                                        <div class="col-md-6 justify-content-center text-center">
                                            <img src="./img/oxa.png" width="65" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number text-muted col-md-6">55.67%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger wow animated progress-animated" id="progress_dac" style="width: 50%; height:6px;" role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <div class="row">
                                        <div class="col-md-6 justify-content-center text-center">
                                            <img src="./img/pasco.png" width="65" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number text-muted col-md-6">1,410</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger wow animated progress-animated" id="progress_dac" style="width: 50%; height:6px;" role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 text-center p-1">
                            <form action="print_pr3m4tur0.php" method="POST">
                                <input hidden name="red_print" id="red_print" value="">
                                <input hidden name="distrito_print" id="distrito_print" value="">
                                <input hidden name="anio_print" id="anio_print" value="">
                                <input hidden name="mes_print" id="mes_print" value="">
                                <button type="submit" id="export_data" name="exportarCSV" class="btn btn-outline-success m-1 btn-sm mb-2"><i class="fa fa-print"></i> Imprimir</button>
                            </form>
                            <button type="button" class="btn btn-outline-danger m-1 btn-sm btn_information mb-2" data-toggle="modal" data-target="#ModalInformacion"><i class="fa fa-list"></i> Ficha</button>
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm m-1 mb-2"><ion-icon name="arrow-round-back"></ion-icon> Regresar</a>
                            <button class="btn btn-outline-primary m-1 btn-sm mb-2" id="btn_all"><i class='fa fa-check'></i> Ver Todo</button>
                        </div>
                        <div class="col-md-4">
                            <div id="table_resume">
                                <div class="table-responsive" id="prematuro_resume">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr class="font-10 text-center" style="background: #e0eff5;">
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Provincia</th>
                                                <th class="align-middle">Distrito</th>
                                                <th class="align-middle">Avan</th>
                                                <th class="align-middle">Meta</th>
                                                <th class="align-middle">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card" style="border-color: #337ab7;">
                                <div class="card-body p-2">
                                    <form method="post" id="formulario" name="f1">
                                        <div class="col-md-12 mb-1 filter_fed">
                                            <select class="form-select" name="red" id="red" onchange="cambia_distrito();" aria-label="Default select example">
                                                <option value="0" selected>Seleccione Red</option>
                                                <option value="1">DANIEL ALCIDES CARRION</option>
                                                <option value="2">OXAPAMPA</option>
                                                <option value="3">PASCO</option>
                                                <option value="4">TODOS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-1 filter_fed">
                                            <select class="select_gestante form-select" name="distrito" id="distrito">
                                                <option value="-" selected>Seleccione Distrito</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-1 filter_fed">
                                            <select class="form-select" name="anio" id="anio">
                                                <option value="0">Seleccione año</option>
                                                <option value="2019">2019</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-1 filter_fed">
                                            <select class="form-select" name="mes" id="mes">
                                                <option value="0">Seleccione mes</option>
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
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-primary btn-sm m-1" type="button" onclick="buscarFed();"><i class="mdi mdi-magnify"></i> Buscar</button>
                                                <button class="btn btn-secondary btn-sm m-1" type="button" id="clear2"><i class="mdi mdi-broom"></i> Limpiar</button>
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
                                    <input type="text" class="knob" value="10" data-width="90" data-height="90" data-fgColor="#add" data-readonly="true">
                                    <div class="knob-label text-primary">Avance</div>
                                </div>
                            </div>
                            <div class="card p-0">
                                <div class="card-body p-1">
                                    <div class="row">
                                        <div class="col-md-6 pt-1 text-center">
                                            <h3 class="total text-success"><b>[[ total ]]</b>
                                            </h3>
                                            <h6 class="text-muted">Meta</h6>
                                        </div>
                                        <div class="col-md-6 pt-1 text-center">
                                            <h3 class="text-suple" style="color: #c8c817;"><b>[[ suplementado ]]</b>
                                            </h3>
                                            <h6 class="text-muted">Avance</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- TABLA NOMINAL -->
                        <div class="col-md-10">
                            <div id="mitable_all" class="text-center">
                                <div class="table-responsive table_all" id="prematuro">
                                    <table id="demo-foo-addrow2" class="table table-hover" data-page-size="20" data-limit-navigation="10">
                                        <thead>
                                            <tr class="font-10 text-center" style="background: #e0eff5;">
                                                <th class="align-middle">#</th>
                                                <th class="align-middle">Provincia</th>
                                                <th class="align-middle">Distrito</th>
                                                <th class="align-middle">Establecimiento</th>
                                                <th class="align-middle">Tipo Documento</th>
                                                <th class="align-middle">Documento</th>
                                                <th class="align-middle">Apellidos y Nombres</th>
                                                <th class="align-middle" id="color_prematuro_head">Fecha Nacido</th>
                                                <th class="align-middle" id="color_prematuro_head">Tipo Seguro</th>
                                                <th class="align-middle" id="color_prematuro_head">Menor Visitado</th>
                                                <th class="align-middle">Suplementado</th>
                                                <th class="align-middle">Prematuro</th>
                                                <th class="align-middle" id="color_prematuro_head">Se Atiende</th>
                                            </tr>
                                        </thead>
                                        <div class="float-right col-md-3">
                                            <div class="mb-3">
                                                <div class="input-wrapper input-group-sm">
                                                    <input id="demo-input-search2" class="form-control input" type="search" placeholder="Buscar por nombres o dni..." style="padding-left: 25px;">
                                                    <i class="fa fa-search input-icon font-13"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <tbody>
                                            <tr v-for="(format, key) in lists" class="font-10">
                                                <td class="align-middle text-center">[[ key+1 ]]</td>
                                                <td class="align-middle text-center">[[ format.NOMBRE_PROV ]]</td>
                                                <td class="align-middle text-center">[[ format.NOMBRE_DIST ]]</td>
                                                <td class="align-middle text-center">[[ format.NOMBRE_EESS ]]</td>
                                                <td class="align-middle text-center">[[ format.Tipo_Doc_Paciente ]]</td>
                                                <td class="align-middle text-center">[[ format.CNV_O_DNI ]]</td>
                                                <td class="align-middle text-center">[[ format.full_name ]]</td>
                                                <td class="align-middle text-center">[[ format.FECHA_NACIMIENTO_NINO ]]</td>
                                                <td class="align-middle text-center">[[ format.TIPO_SEGURO ]]</td>
                                                <td class="align-middle text-center">[[ format.MENOR_VISITADO ]]</td>
                                                <td class="align-middle text-center">[[ format.SUPLEMENTADO ]]</td>
                                                <td class="align-middle text-center">[[ format.BAJO_PESO_PREMATURO ]]</td>
                                                <td class="align-middle text-center">[[ format.Establecimiento ]]</td>
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
                        <img src="./img/fichas/inf_prematuros.png" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/prematuro.js"></script>
@endsection

@section('javascript')
@endsection