@extends('layouts.base')

@section('content')
    <div class="content-wrapper">
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
                                    <span class="info-box-text font-13 text-center">Cantidad Registros</span>
                                    <div class="row">
                                        <img src="./img/user_cant.png" width="33" alt="icon cantidad total" class="col-md-5">
                                        <span class="info-box-number col-md-7">1,410</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <span class="info-box-text font-13 text-center">Suplementados</span>
                                    <div class="row">
                                        <img src="./img/boy.png" width="33" alt="icon cantidad total" class="col-md-5">
                                        <span class="info-box-number col-md-7 text-success">1,410</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <span class="info-box-text font-13 text-center">No Suplementados</span>
                                    <div class="row">
                                        <img src="./img/boy_x.png" width="33" alt="icon cantidad total" class="col-md-5">
                                        <span class="info-box-number col-md-7 text-danger">1,410</span>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <div class="row">
                                        <img src="./img/dac.png" width="33" alt="icon cantidad total" class="col-md-5">
                                        <span class="info-box-number col-md-7">1,410</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger wow animated progress-animated" id="progress_dac" style="width: 50%; height:6px;" role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <div class="row">
                                        <img src="./img/pasco.png" width="33" alt="icon cantidad total" class="col-md-5">
                                        <span class="info-box-number col-md-7">55.67%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger wow animated progress-animated" id="progress_dac" style="width: 50%; height:6px;" role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="info-box col-md-4">
                                <div class="info-box-content">
                                    <div class="row">
                                        <img src="./img/oxa.png" width="33" alt="icon cantidad total" class="col-md-5">
                                        <span class="info-box-number col-md-7">1,410</span>
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
                            <button type="button" class="btn btn-outline-danger m-1 btn-sm btn_information mb-2" data-bs-toggle="modal" data-bs-target="#ModalInformacion"><i class="fa fa-list"></i> Ficha</button>
                            <button type="button" name="Limpiar" class="btn btn-outline-secondary m-1 btn-sm mb-2" onclick="location.href='index2.php';"><i class='fa fa-rotate-left'></i> Regresar</button>
                            <button class="btn btn-outline-primary m-1 btn-sm mb-2" id="btn_all"><i class="mdi mdi-checkbox-multiple-blank"></i> Ver Todo</button>
                        </div>
                    </div>
                    <div class="row">
                        <!-- MAPA AVANCE REGIONAL -->
                        <div class="col-md-2 pl-3"><br>
                            <div class="card p-0">
                                <div class="card-body p-1">
                                    <div class="row">
                                        {{-- <div class="col-md-7 pt-2 text-center">
                                            <h5 class="advance text-primary"><span> 48.90%</span></h5>
                                            <h6 class="text-muted">Avance</h6>
                                        </div> --}}
                                        <div class="col-12 col-md-12 text-center">
                                            <input type="text" class="knob" value="58.3" data-width="90" data-height="90" data-fgColor="#3c8dbc" data-readonly="true">
                                            <div class="knob-label text-primary">Avance</div>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card p-0">
                                <div class="card-body p-1">
                                    <div class="row">
                                        <div class="col-md-6 pt-1 text-center">
                                            <h3 class="total text-success"><b>100</b>
                                            </h3>
                                            <h6 class="text-muted">Meta</h6>
                                        </div>
                                        <div class="col-md-6 pt-1 text-center">
                                            <h3 class="text-suple" style="color: #c8c817;"><b>550</b>
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
                                    <table id="demo-foo-addrow" class="table table-hover" data-page-size="20" data-limit-navigation="10">
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
                                        <div class="float-end col-md-3">
                                            <div class="mb-3">
                                                <div class="input-wrapper input-group-sm">
                                                    <input id="demo-input-search" class="form-control input" type="search" placeholder="Buscar por nombres o dni..." style="padding-left: 25px;">
                                                    <i class="mdi mdi-magnify input-icon font-15"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <tbody>

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
    </div>
@endsection