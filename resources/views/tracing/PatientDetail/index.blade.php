@extends('layouts.base')

@section('content')
    <div class="content-wrapper" id="appFlatFile">
        <section class="content">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="mb-0">Detalle Paciente</span></h5>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right font-14">
                                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="#">Fed</a></li>
                                <li class="breadcrumb-item active">Paciente</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-7 text-end">
                            <div class="card">
                                <div class="card-body p-2">
                                    <form method="POST" class="formulario">
                                        <div class="row">
                                            <div class="col-md-4 mb-2 mt-1">
                                                <input class="form-control input_search validanumericos" type="text" name="doc" id="doc" placeholder="Ingrese su dni..." maxlength="8">
                                            </div>
                                            <div class="col-md-4 mt-1">
                                                <select class="custom-select rounded-2 show-tick" style="width: 100%;">
                                                    <option value="">Seleccione Red</option>
                                                    <option value="vacciness">Vacunas</option>
                                                    <option value="child">Niño</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mt-1 p-0">
                                                <div class="d-flex justify-content-center">
                                                    <button class="btn btn-primary btn-sm m-1" id="btn_buscar" type="button"><i class="fa fa-search"></i> Buscar</button>
                                                    <button class="btn btn-secondary btn-sm m-1" type="button" id="clear"><i class="fa fa-broom"></i> Limpiar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="info_head"></div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <script src="./js/PatientDetail.js"></script>
    <script>
        $(document).ready(function(){
            $("#search").click();
        });
    </script>

@endsection

@section('javascript')
@endsection