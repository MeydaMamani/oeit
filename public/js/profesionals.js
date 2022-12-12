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
    el: '#appProfessionals',
    data: {
        errors: [],
        lists: [],
        listsResum: [],
        total: 0,
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
        listProfessionals: function() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","profesional");
            const getDate = new Date();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": getDate.getFullYear(), "mes": getDate.getMonth() }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            // if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            // else{
                axios({
                    method: 'POST',
                    url: 'professionals/list',
                    data: data,
                })
                .then(response => {
                    this.lists = response.data;
                    console.log(this.lists);
                    for (let i = 0; i < this.lists.length; i++) {
                        this.total++;
                    }

                    this.anio == '' ? this.nameYear = getDate.getFullYear() : this.nameYear = this.anio;
                    this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                    this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                    this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

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

        PrintNominal: function(){
            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/print?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes-1)  + '&nameMonth=' + (this.nameMonth) + '&his=' + (this.date_his);
            window.open(url_,'_blank');
        },
    }
})