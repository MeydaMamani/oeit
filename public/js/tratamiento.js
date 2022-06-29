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
    el: '#appTratamiento',
    data: {
        errors: [],
        lists: [],
        listsResum: [],
        total: 0,
        cumple: 0,
        no_cumple: 0,
        avance: 0,
        advanceReg: [],
        provinces: [],
        districts: [],
        districts2: [],
        date_his: '',
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonthSupcion: '',
        nameYearSupcion: '',

        nameMonthTreatment: '',
        nameYearTreatment: '',
        red2: '',
        distrito2: '',
        anio2: '',
        mes2: '',
        total2: 0,
        cumple2: 0,
        no_cumple2: 0,
        avance: 0,
        advanceReg2: [],
        lists2: [],
        listsResum2: [],
    },
    created: function() {
        this.filtersProv();
        this.dateHis();
    },
    methods: {
        listSospecha: function() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","bateria_completa");
            this.cumple=0; this.no_cumple=0; this.total=0;
            const getDate = new Date();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": getDate.getFullYear(), "mes": getDate.getMonth()-1 }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            // if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            // else{
                axios({
                    method: 'POST',
                    url: 'tratamiento/listSos',
                    data: data,
                })
                .then(response => {
                    this.lists = response.data[0];
                    this.listsResum = response.data[1];
                    this.advanceReg = response.data[2];
                    for (let i = 0; i < this.lists.length; i++) {
                        this.total++;
                        this.lists[i].MIDE == 'SI' ? this.cumple++ : this.no_cumple++;
                    }

                    for (let j = 0; j < this.listsResum.length; j++) {
                        var avance = (this.listsResum[j].NUMERADOR/this.listsResum[j].DENOMINADOR)*100;
                        avance % 1 != 0 ? this.listsResum[j].AVANCE = avance.toFixed(1) : this.listsResum[j].AVANCE = avance;
                    }

                    for (let k = 0; k < this.advanceReg.length; k++) {
                        var a = (this.advanceReg[k].NUM/this.advanceReg[k].DEN)*100;
                        a % 1 != 0 ? this.advanceReg[k].ADVANCE = a.toFixed(1) : this.advanceReg[k].ADVANCE = a;
                    }

                    this.anio == '' ? this.nameYearSupcion = getDate.getFullYear() : this.nameYearSupcion = this.anio;
                    this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                    this.nameMonthSupcion = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                    this.nameMonthSupcion = this.nameMonthSupcion.charAt(0).toUpperCase() + this.nameMonthSupcion.slice(1);

                    this.avance = ((this.cumple / this.total) * 100).toFixed(1);
                    $('.knob').val(this.avance + '%').trigger('change');
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

                }).catch(e => {
                    this.errors.push(e)
                })
            // }
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

        dateHis: function() {
            getDate = new Date();
            this.date_his = getDate.toISOString().split('T')[0];
            setTimeout(() => $('.show-tick').selectpicker('refresh'));
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

        listNoCumplenSospecha() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","no_cumplen");
            this.listCumplen = [];
            for (let i = 0; i < this.lists.length; i++) {
                if(this.lists[i].MIDE == 'NO'){
                    this.listCumplen.push(this.lists[i]);
                }
            }

            this.lists = this.listCumplen;
            $('#demo-foo-addrow2').footable();
            $('#demo-foo-addrow2').data('footable').redraw();
            $('#demo-foo-filtering').data('footable').redraw();
            $('#demo-foo-filtering').footable();
            $('#demo-foo-addrow2').footable();
            $('.table').footable();
        },

        PrintSospecha: function(){
            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/printSos?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes)  + '&nameMonth=' + (this.nameMonthSupcion) + '&his=' + (this.date_his);
            window.open(url_,'_blank');
        },

        // PARA INICIO DE TRATAMIENTO
        listTratamiento: function() {
            console.log('ESTOY EN INICIO E TRATAMIENTO');
            // $(".nominalTable").removeAttr("id");
            // $(".nominalTable").attr("id","bateria_completa");
            this.cumple2=0; this.no_cumple2=0; this.total2=0;
            const getDate2 = new Date();
            const currentData2 = { "red2": "TODOS", "distrito2": "TODOS", "anio2": getDate2.getFullYear(), "mes2": getDate2.getMonth()-1 }
            const formData2 = $("#formulario1").serialize();
            this.red2 == '' ? data = currentData2 : data = formData2;

            // if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            // else{
                axios({
                    method: 'POST',
                    url: 'tratamiento/listTrat',
                    data: data,
                })
                .then(respuesta => {
                    this.lists2 = respuesta.data[0];
                    this.listsResum2 = respuesta.data[1];
                    this.advanceReg2 = respuesta.data[2];
                    console.log(this.advanceReg2);
                    for (let i = 0; i < this.lists2.length; i++) {
                        this.total2++;
                        this.lists2[i].MIDE == 'SI' ? this.cumple2++ : this.no_cumple2++;
                    }

                    for (let j = 0; j < this.listsResum2.length; j++) {
                        var avance2 = (this.listsResum2[j].NUMERADOR/this.listsResum2[j].DENOMINADOR)*100;
                        avance2 % 1 != 0 ? this.listsResum2[j].AVANCE = avance2.toFixed(1) : this.listsResum2[j].AVANCE = avance2;
                    }

                    for (let k = 0; k < this.advanceReg2.length; k++) {
                        var a = (this.advanceReg2[k].NUM/this.advanceReg2[k].DEN)*100;
                        a % 1 != 0 ? this.advanceReg2[k].ADVANCES = a.toFixed(1) : this.advanceReg2[k].ADVANCES = a;
                    }

                    this.anio2 == '' ? this.nameYearTreatment = getDate.getFullYear() : this.nameYearTreatment = this.anio;
                    this.mes2 == '' ? this.mes2 = getDate2.getMonth() + 1 : this.mes2;
                    this.nameMonthTreatment = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate2.setMonth(this.mes2 - 1));
                    this.nameMonthTreatment = this.nameMonthTreatment.charAt(0).toUpperCase() + this.nameMonthTreatment.slice(1);

                    this.avance2 = ((this.cumple2 / this.total2) * 100).toFixed(1);
                    $('#knob').val(this.avance2 + '%').trigger('change');
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

                }).catch(e => {
                    this.errors.push(e)
                })
            // }
        },

        listNoCumplenTratamiento() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","no_cumplen");
            this.listCumplen2 = [];
            for (let i = 0; i < this.lists2.length; i++) {
                if(this.lists2[i].MIDE == 'NO'){
                    this.listCumplen2.push(this.lists2[i]);
                }
            }

            this.lists2 = this.listCumplen2;
        },

        filtersDistricts2() {
            this.districts2 = [];
            axios({
                method: 'POST',
                url: 'distr',
                data: { "id": this.red2 },
            })
            .then(respuesta => {
                this.districts2 = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },
    }
})