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
    el: '#appSisCovid',
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
        districts: {},
        date_his: '',
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonth: '',
        nameYear: '',
    },
    created: function() {
        this.filtersProv();
        this.dateHis();
    },
    methods: {
        listSisCovid: function() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","profesional");
            this.cumple=0; this.no_cumple=0; this.total=0;
            const getDate = new Date();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": getDate.getFullYear(), "mes": getDate.getMonth() + 1 }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            // if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            // else{
                axios({
                    method: 'POST',
                    url: 'sisCovid/list',
                    data: data,
                })
                .then(response => {
                    this.lists = response.data;
                    console.log(this.lists);
                    for (let i = 0; i < this.lists.length; i++) {
                        this.total++;
                        this.lists[i].FED == 'CUMPLE' ? this.cumple++ : this.no_cumple++;
                    }

                    this.anio == '' ? this.nameYear = getDate.getFullYear() : this.nameYear = this.anio;
                    this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                    this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                    this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

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
            axios.post('pn')
            .then(respuesta => {
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

        PrintNominal: function(){
            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/print?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes)  + '&nameMonth=' + (this.nameMonth) + '&his=' + (this.date_his);
            window.open(url_,'_blank');
        },
    }
})