<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="card">
        <div class="card-header bg-gray">
            <h3 class="card-title">Datos del Paciente</h3>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Apellidos y Nombres</label>
                        Masculino
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-13">Sexo</label>
                        Masculino
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Etnia</label>
                        Masculino
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Lengua Materna</label>
                        Masculino
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Fecha Nacimiento</label>
                        Masculino
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Edad</label>
                        <input type="number" class="form-control form-control-sm" name="EDAD" id="EDAD" v-model="form.EDAD">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-13">Menor Edad</label>
                        <select class="custom-select custom-select-sm" style="width: 100%;" name="MENOR_DE_EDAD" id="MENOR_DE_EDAD" v-model="form.MENOR_DE_EDAD" v-select2>
                            <option value="SI">Si</option>
                            <option value="NO">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-13">Tipo Doc</label>
                        <select class="custom-select custom-select-sm" style="width: 100%;" name="TIPO_DOCUMENTO" id="TIPO_DOCUMENTO" v-model="form.TIPO_DOCUMENTO" v-select2>
                            <option value="DNI">DNI</option>
                            <option value="CE">Carnet Extranjria</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Documento</label>
                        <input type="text" class="form-control form-control-sm" name="NUMERO_DOCUMENTO" id="NUMERO_DOCUMENTO" v-model="form.NUMERO_DOCUMENTO">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Historia Clínica</label>
                        <input type="text" class="form-control form-control-sm" name="HISTORIA_CLINICA" id="HISTORIA_CLINICA" v-model="form.HISTORIA_CLINICA">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Fecha Ingreso Padrón</label>
                        <input type="date" class="form-control form-control-sm" name="FECHA_INGRESO_A_PADRON" id="FECHA_INGRESO_A_PADRON" v-model="form.FECHA_INGRESO_A_PADRON">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-13">Tipo Caso</label>
                        <select class="custom-select custom-select-sm" style="width: 100%;" name="TIPO_CASO" id="TIPO_CASO" v-model="form.TIPO_CASO" v-select2>
                            <option value="EXPUESTO">Expuesto</option>
                            <option value="INTOXICADO">Intoxicado</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Teléfono/Celular</label>
                        <input type="text" class="form-control form-control-sm" name="TELEFONO" id="TELEFONO" v-model="form.TELEFONO">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Pseudónimo/Código</label>
                        <input type="text" class="form-control form-control-sm" name="PSEUDONIMO_CODIGO" id="PSEUDONIMO_CODIGO" v-model="form.PSEUDONIMO_CODIGO">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Tipo Seguro</label>
                        <select class="custom-select custom-select-sm" style="width: 100%;" name="TIPO_SEGURO" id="TIPO_SEGURO" v-model="form.TIPO_SEGURO" v-select2>
                            <option value="SIS">SIS</option>
                            <option value="ESSALUD">ESSALUD</option>
                            <option value="SALUDPOL">SALUDPOL</option>
                            <option value="OTRO">OTRO</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Residencia Anterior</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-2 pb-2">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Región</label>
                                <select class="form-control select2 show-tick" style="width: 100%;" name="REGION_ANTERIOR" id="REGION_ANTERIOR" v-model="form.REGION_ANTERIOR" @change="filtersProvinces" v-select2>
                                    <option v-for="format in listDepartment" :value="format.Departamento">[[ format.Departamento ]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Provincia</label>
                                <select class="form-control select2 show-tick" style="width: 100%;" name="PROVINCIA_ANTERIOR" id="PROVINCIA_ANTERIOR" v-model="form.PROVINCIA_ANTERIOR" @change="filtersDistricts" v-select2>
                                    <option v-for="format in listProvinces" :value="format.Provincia">[[ format.Provincia ]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Distrito</label>
                                <select class="form-control select2 show-tick" style="width: 100%;" name="DISTRITO_ANTERIOR" id="DISTRITO_ANTERIOR" v-model="form.DISTRITO_ANTERIOR" v-select2>
                                    <option v-for="format in listDistricts" :value="format.Distrito">[[ format.Distrito ]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Dirección</label>
                                <input type="text" class="form-control form-control-sm" name="DIRECCION_ANTERIOR" id="DIRECCION_ANTERIOR" v-model="form.DIRECCION_ANTERIOR">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Edad</label>
                                <input type="number" class="form-control form-control-sm" name="ANIOS_ANTERIOR" id="ANIOS_ANTERIOR" v-model="form.ANIOS_ANTERIOR">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Residencia Actual</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-2 pb-2">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Región</label>
                                <select class="form-control select2 show-tick" style="width: 100%;" name="REGION_ACTUAL" id="REGION_ACTUAL" v-model="form.REGION_ACTUAL" @change="filtersProvinces2" v-select2>
                                    <option v-for="format in listDepartment" :value="format.Departamento">[[ format.Departamento ]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Provincia</label>
                                <select class="form-control select2 show-tick" style="width: 100%;" name="PROVINCIA_ACTUAL" id="PROVINCIA_ACTUAL" v-model="form.PROVINCIA_ACTUAL" @change="filtersDistricts2" v-select2>
                                    <option v-for="format in listProvinces2" :value="format.Provincia">[[ format.Provincia ]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Distrito</label>
                                <select class="form-control select2 show-tick" style="width: 100%;" name="DISTRITO_ACTUAL" id="DISTRITO_ACTUAL" v-model="form.DISTRITO_ACTUAL" v-select2>
                                    <option v-for="format in listDistricts2" :value="format.Distrito">[[ format.Distrito ]]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Dirección</label>
                                <input type="text" class="form-control form-control-sm" name="DIRECCION_ACTUAL" id="DIRECCION_ACTUAL" v-model="form.DIRECCION_ACTUAL">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="font-13">Edad</label>
                                <input type="number" class="form-control form-control-sm" name="ANIOS_ACTUAL" id="ANIOS_ACTUAL" v-model="form.ANIOS_ACTUAL">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <h3 class="card-title">Datos del Apoderado</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body pt-2 pb-2">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Tipo Documento</label>
                        <select class="custom-select custom-select-sm" style="width: 100%;" name="TIPO_DOC_APODERADO" id="TIPO_DOC_APODERADO" v-model="form.TIPO_DOC_APODERADO" v-select2>
                            <option value="DNI">DNI</option>
                            <option value="CE">Carnet Extranjeria</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Documento</label>
                        <input type="text" class="form-control form-control-sm" name="DOCUMENTO_APODERADO" id="DOCUMENTO_APODERADO" v-model="form.DOCUMENTO_APODERADO">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Apellidos y Nombres</label>
                        <input type="text" class="form-control form-control-sm" name="NOMBRE_APODERADO" id="NOMBRE_APODERADO" v-model="form.NOMBRE_APODERADO">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Teléfono/Celular</label>
                        <input type="text" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <h3 class="card-title">Valores/Resultados Prueba</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body pt-2 pb-2">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Pb (µg/dl)</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">As (µg/g creatinina)</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Cd (µg/g creatinina)</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Hg (µg/g creatinina)</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Otro. </label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <h3 class="card-title">Datos Mensual</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body pt-2 pb-2">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Seleccione Mes</label>
                        <select class="form-control select2 show-tick" style="width: 100%;" id="mes" name="mes" v-select2>
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Tipo</label>
                        <select class="form-control select2 show-tick" style="width: 100%;" name="y" id="y" v-select2>
                            <option value="DNI">Pb (µg/dl)</option>
                            <option value="CE">As (µg/g creatinina)</option>
                            <option value="DNI">Cd (µg/g creatinina)</option>
                            <option value="CE">Hg (µg/g creatinina)</option>
                            <option value="CE">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Atención salud Presencial</label>
                        <select class="form-control select2 show-tick" style="width: 100%;" name="prov2" id="prov2" v-select2>
                            <option value="DNI">Integral</option>
                            <option value="CE">Especializada</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Atención de Salud por Telemedicina</label>
                        <select class="form-control select2 show-tick" style="width: 100%;" name="dis2" id="dis2" v-select2>
                            <option value="DNI">Integral</option>
                            <option value="CE">Especializada</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Ipress</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Servicio</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Fecha</label>
                        <input type="date" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Resultados</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="font-13">Observaciones</label>
                        <input type="email" class="form-control form-control-sm" id="exampleInputEmail1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>