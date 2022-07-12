<div class="row">
    <div class="col-md-8">
        <div class="row" id="cg_avance_regional">
            <h5 class="col-md-12 text-center mb-3 font-17" style="color: #174d9d;">
                [[ nameRedView ]] / [[ distrito ]] - [[ nameMonth ]] [[ nameYear ]]
            </h5>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="d-flex justify-content-center">
                            <div class="col-md-4 col-sm-4">
                                <div class="info-box elevation-2 p-1 info-box-not-dflex">
                                    <div class="info-box-content">
                                        <span class="info-box-text font-16 text-center">Total</span>
                                        <div class="col-md-12 justify-content-center align-items-center d-flex">
                                            <img src="./img/user_cant.png" width="40" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-12 text-center text-secondary font-26 mt-0">[[ total ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="info-box elevation-2 p-1 info-box-not-dflex">
                                    <div class="info-box-content">
                                        <span class="info-box-text font-16 text-center">Cumplen</span>
                                        <div class="col-md-12 justify-content-center align-items-center d-flex">
                                            <img src="./img/boy.png" width="40" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-12 text-center text-success font-26 mt-0">[[ cumple ]]</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="info-box elevation-2 p-1 info-box-not-dflex" id="all">
                                    <div class="info-box-content">
                                        <span class="info-box-text font-16 text-center">No Cumplen</span>
                                        <div class="col-md-12 justify-content-center align-items-center d-flex">
                                            <img src="./img/boy_x.png" width="40" alt="icon cantidad total">
                                        </div>
                                        <span class="info-box-number col-md-12 text-center text-danger font-26 mt-0">[[ no_cumple ]]</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    <div class="col-md-3 col-sm-3 swing animated">
                        <div class="card p-0">
                            <div class="card-body p-1 text-center">
                                <input type="text" class="knob" value="0" data-readonly="true" data-width="90" data-height="90" data-fgColor="#00c0ef">
                                <div class="knob-label text-primary">Avance</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-center p-1">
                        <button type="submit" id="export_data" name="exportarCSV" class="btn btn-outline-success m-1 btn-sm mb-2 font-11" @click="PrintVaccineBcgHvb"><i class="fa fa-print"></i> Imprimir</button>
                        <button type="button" class="btn btn-outline-danger m-1 btn-sm btn_information mb-2 font-11"><i class="fa fa-list"></i> Ficha</button>
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
    <div class="col-md-4 mt-3">
        <div id="table_resume">
            <div class="table-responsive" id="prematuro_resume">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr class="font-9 text-center" style="background: #e0eff5;">
                            <th class="align-middle">#</th>
                            <th class="align-middle">Periodo</th>
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
                            <td class="align-middle text-center">[[ format.PERIODO ]]</td>
                            <td class="align-middle">[[ format.PROVINCIA ]]</td>
                            <td class="align-middle text-center">[[ format.DISTRITO ]]</td>
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
        </div><br>
        <div class="card mb-2">
            <h6 class="pt-2 text-center m-0 font-weight-bold">Avance Regional</h6>
            <div class="card-body p-2 pr-3">
                <div class="chart">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>