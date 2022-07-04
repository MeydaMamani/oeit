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
        districts: {},
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonth: '',
        nameYear: '',
    },
    created: function() {
        this.filtersProv();
    },
    methods: {
        listVaccineBcgHvb: function() {
            this.cumple=0; this.no_cumple=0; this.total=0;
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            else{
                axios({
                    method: 'POST',
                    url: 'conventions/list',
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
                    console.log(this.avance);
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
                    this.anio == '' ? this.nameYear = getDate.getFullYear() : this.nameYear = this.anio;
                    this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                    this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                    this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

                }).catch(e => {
                    this.errors.push(e)
                })
            }
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


    }
})