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
    el: '#appIniciOportuno',
    data: {
        errors: [],
        lists: [],
        listsResum: [],
        total: 0,
        suplementado: 0,
        no_suplementado: 0,
        avance: 0,
        advanceReg: [],
        provinces: [],
        districts: {},
        date_pn: '',
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonth: '',
        nameYear: '',
    },
    created: function() {
        this.filtersProv();
        this.datePn();
    },
    methods: {
        listSixEightMonth: function() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","cuatro_meses");
            this.suplementado=0; this.no_suplementado=0; this.total=0;
            const getDate = new Date();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": getDate.getFullYear(), "mes": getDate.getMonth()+1 }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            // if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            // else{
                axios({
                    method: 'POST',
                    url: 'iniOport/list',
                    data: data,
                })
                .then(response => {
                    this.lists = response.data[0];
                    this.listsResum = response.data[1];
                    this.advanceReg = response.data[2];
                    for (let i = 0; i < this.lists.length; i++) {
                        this.total++;
                        this.lists[i].MIDE == 'CUMPLE' ? this.suplementado++ : this.no_suplementado++;
                    }

                    for (let j = 0; j < this.listsResum.length; j++) {
                        var avance = (this.listsResum[j].NUMERADOR/this.listsResum[j].DENOMINADOR)*100;
                        avance % 1 != 0 ? this.listsResum[j].AVANCE = avance.toFixed(1) : this.listsResum[j].AVANCE = avance;
                    }

                    for (let k = 0; k < this.advanceReg.length; k++) {
                        var a = (this.advanceReg[k].NUM/this.advanceReg[k].DEN)*100;
                        a % 1 != 0 ? this.advanceReg[k].ADVANCE = a.toFixed(1) : this.advanceReg[k].ADVANCE = a;
                    }

                    this.avance = ((this.suplementado / this.total) * 100).toFixed(1);
                    console.log(this.avance);
                    console.log('AQUI TOY');
                    $('.knob').val(this.avance + '%').trigger('change');
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

                    this.anio == '' ? this.nameYear = getDate.getFullYear() : this.nameYear = this.anio;
                    this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                    this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                    this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

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

        datePn: function() {
            axios.post('pn')
            .then(respuesta => {
                this.date_pn = respuesta.data[0].DATE_MODIFY;
                getDate = new Date();
                this.date_his = getDate.toISOString().split('T')[0];
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

        listNoCumplen() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","no_cumplen");
            this.listNoSuplement = [];
            for (let i = 0; i < this.lists.length; i++) {
                if(this.lists[i].MIDE == 'NO CUMPLE'){
                    this.listNoSuplement.push(this.lists[i]);
                }
            }
            this.lists = this.listNoSuplement;
            $('#demo-foo-addrow2').footable();
            $('#demo-foo-addrow2').data('footable').redraw();
            $('#demo-foo-filtering').data('footable').redraw();
            $('#demo-foo-filtering').footable();
            $('#demo-foo-addrow2').footable();
            $('.table').footable();
        },

        PrintNominal: function(){
            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/print?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes)  + '&nameMonth=' + (this.nameMonth) + '&pn=' + (this.date_pn) + '&his=' + (this.date_his);
            window.open(url_,'_blank');
        },
    }
})