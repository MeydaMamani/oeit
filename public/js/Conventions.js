Vue.directive('select2', {
    inserted(el) {
        $(el).on('select2:select', () => {
            const event = new Event('change', { bubbles: true, cancelable: true });
            el.dispatchEvent(event);
        });

        $(el).on('select2:unselect', () => {
            const event = new Event('change', {bubbles: true, cancelable: true})
            el.dispatchEvent(event)
        })
    },
});

const appPrematuros = new Vue({
    delimiters: ['[[', ']]'],
    el: '#appConventions',
    data: {
        errors: [],
        lists: [],
        listsResum: [],
        total: 0,
        cumple: 0,
        no_cumple: 0,
        avance: 0,
        advanceReg: [],
        bcg_hvb: false,
        provinces: [],

        // PARA VACUNAS BCG HVB
        districts: [],
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonth: '',
        nameYear: '',
        nameRedView: '',

        listEstablishment: [],
        stablishment: '',

        // PARA PACIENTES RECUPERADOS
        anio2: '',
        mes2: '',

        // PARA 2 CONTROLES CRED
        districts3: [],
        red3: '',
        distrito3: '',
        anio3: '',
        mes3: '',

        vaccineBcgHvb: false,
        AdolescentsFolicAcid: false,
        recoveredPatients: false,
        TwoCtrlCred: false
    },
    created: function() {
        this.filtersProv();
    },
    methods: {
        bcgHvb: function(){
            this.vaccineBcgHvb = true
            this.AdolescentsFolicAcid = false
            this.TwoCtrlCred = false
            this.recoveredPatients = false
        },

        Adolescents: function(){
            this.AdolescentsFolicAcid = true
            this.TwoCtrlCred = false
            this.vaccineBcgHvb = false
            this.recoveredPatients = false
        },

        RecovPatients: function(){
            this.recoveredPatients = true
            this.AdolescentsFolicAcid = false
            this.TwoCtrlCred = false
            this.vaccineBcgHvb = false
        },

        twoCtrlsCred: function(){
            this.TwoCtrlCred = true
            this.AdolescentsFolicAcid = false
            this.vaccineBcgHvb = false
            this.recoveredPatients = false
        },


        listVaccineBcgHvb: function() {
            this.cumple=0; this.no_cumple=0; this.total=0;
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            else{
                toastr.success('Buscando...', null, { "closeButton": true, "progressBar": true, "timeOut": "10000", });
                axios({
                    method: 'POST',
                    url: 'conventions/listBcgHvb',
                    data: data,
                })
                .then(response => {
                    this.bcg_hvb = true;
                    this.lists = response.data[0];
                    this.listsResum = response.data[1];
                    this.advanceReg = response.data[2];
                    console.log(this.advanceReg);
                    for (let i = 0; i < this.lists.length; i++) {
                        this.total++;
                        this.lists[i].NUM == this.lists[i].DEN ? this.cumple++ : this.no_cumple++;
                    }

                    for (let j = 0; j < this.listsResum.length; j++) {
                        var avance = (this.listsResum[j].NUMERADOR/this.listsResum[j].DENOMINADOR)*100;
                        avance % 1 != 0 ? this.listsResum[j].AVANCE = avance.toFixed(1) : this.listsResum[j].AVANCE = avance;
                    }

                    this.avance = ((this.cumple / this.total) * 100).toFixed(1);
                    $('.knob').val(this.avance + '%').trigger('change');

                    // PARA GRAFICO AVANCE REGIONAL
                    const nameRed = [];
                    const dataRed = [];
                    for (let k = 0; k < this.advanceReg.length; k++) {
                        nameRed.push(this.advanceReg[k].PROVINCIA);
                        dataRed.push(this.advanceReg[k].AVANCE);
                    }

                    var areaChartData = {
                        labels  : nameRed,
                        datasets: [
                            {
                                label               : 'Avance',
                                backgroundColor     : [ 'rgb(255 99 132 / 56%)', 'rgb(255 159 64 / 54%)' ],
                                borderColor         : [ 'rgb(255 99 132 / 56%)', 'rgb(255 159 64 / 54%)' ],
                                pointRadius         :  true,
                                pointColor          : '#3b8bba',
                                pointStrokeColor    : [ 'rgb(255 99 132 / 56%)', 'rgb(255 159 64 / 54%)' ],
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: [ 'rgb(255 99 132 / 56%)', 'rgb(255 159 64 / 54%)' ],
                                data                :  dataRed,
                            },
                        ]
                    }

                    var barChartCanvas = $('#barChart').get(0).getContext('2d')
                    var barChartData = $.extend(true, {}, areaChartData)
                    var temp0 = areaChartData.datasets[0]
                    barChartData.datasets[0] = temp0

                    var barChartOptions = {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: 100,
                                }
                            }]
                        },
                    }

                    new Chart(barChartCanvas, {
                        type: 'bar',
                        data: barChartData,
                        options: barChartOptions
                    })

                    $('.footable-page a').filter('[data-page="0"]').trigger('click');
                    if(this.red == '01'){ this.nameRedView = 'PASCO'; }
                    else if (this.red == '02'){ this.nameRedView = 'DANIEL CARRION'; }
                    else if(this.red == '03'){ this.nameRedView = 'OXAPAMPA'; }
                    else{ this.nameRedView = 'TODOS'; }

                    this.anio == '' ? this.nameYear = getDate.getFullYear() : this.nameYear = this.anio;
                    if(this.mes == 'TODOS') {
                        this.nameMonth = 'TODOS'
                    }else{
                        this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                        this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                        this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);
                    }

                }).catch(e => {
                    this.errors.push(e)
                })
            }
        },

        PrintVaccineBcgHvb: function(){
            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/printBcgHvb?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes)  + '&nameMonth=' + (this.nameMonth);
            window.open(url_,'_blank');
        },

        filtersProv: function() {
            axios.post('prov')
            .then(respuesta => {
                this.provinces = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersDistricts() {
            this.districts = [];
            axios({
                method: 'POST',
                url: 'distr',
                data: { "id": this.red },
            })
            .then(respuesta => {
                this.districts = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersEstablishment() {
            this.listEstablishment = [];
            axios({
                method: 'POST',
                url: 'stablishment',
                data: { "id": this.distrito },
            })
            .then(respuesta => {
                this.listEstablishment = respuesta.data;
                console.log(this.listEstablishment);
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        // PARA PACIENTES RECUPERADOS
        printRecovPtient: function(){
            var anio = $('#anio2').val();
            var mes = $('#mes2').val();
            const getDate = new Date();
            mes == 'TODOS' ? this.nameMonth = 'Todos' : this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1)); this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

            url_ = window.location.origin + window.location.pathname + '/printRecovPatient?a=' + (anio)
            + '&m=' + (mes)  + '&nameMonth=' + (this.nameMonth);
            window.open(url_,'_blank');
        },

        // PARA DOS CONTROLES CRED
        filtersDistricts3() {
            this.districts3 = [];
            axios({
                method: 'POST',
                url: 'distr',
                data: { "id": this.red3 },
            })
            .then(respuesta => {
                this.districts3 = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        printTwoCtrlCred: function(){
            var red = $('#red3').val();
            var dist = $('#distrito3').val();
            var anio = $('#anio3').val();
            var mes = $('#mes3').val();
            console.log(red, '-', dist, '-', anio, '-', mes);
            const getDate = new Date();
            mes == 'TODOS' ? this.nameMonth = 'Todos' : this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1)); this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

            url_ = window.location.origin + window.location.pathname + '/printTwoCtrlCred?r=' + (red)
            + '&d=' + (dist) + '&a=' + (anio) + '&m=' + (mes) + '&nameMonth=' + (this.nameMonth);
            window.open(url_,'_blank');
        },
    }
})