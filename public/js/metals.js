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
    el: '#appMetals',
    data: {
        errors: [],
        lists: [],
        provinces: [],
        districts: {},
        date_his: '',
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonth: '',
        nameYear: '',
        doc: '',
        category: '',
        table: false,
    },
    created: function() {
        this.filtersProv();
    },
    methods: {
        listMetals: function() {
            const formData = $("#formulario").serialize();
            if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            else{
                axios({
                    method: 'POST',
                    url: 'metals/list',
                    data: formData,
                })
                .then(response => {
                    this.table = true
                    this.lists = response.data[0];
                    $("#export").val("lista");
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

                }).catch(e => {
                    this.errors.push(e)
                })
            }
        },

        listMetalsDni: function() {
            const formData = $("#formulario2").serialize();
            console.log(formData);
            if (this.doc == '') { toastr.error('Ingrese su Documento', null, { "closeButton": true, "progressBar": true }); }
            else{
                axios({
                    method: 'POST',
                    url: 'metals/listDni',
                    data: formData,
                })
                .then(response => {
                    this.table = true
                    this.lists = response.data;
                    $("#export").val("dni");
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

                }).catch(e => {
                    this.errors.push(e)
                })
            }
        },

        listMetalsCategory: function() {
            const formData = $("#formulario3").serialize();
            if (this.category == '') { toastr.error('Seleccione una Categoria', null, { "closeButton": true, "progressBar": true }); }
            else{
                axios({
                    method: 'POST',
                    url: 'metals/listDni',
                    data: formData,
                })
                .then(response => {
                    this.table = true
                    this.lists = response.data;
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

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

        PrintNominal: function(){
            const exp = $("#export").val();
            if(exp == 'dni'){
                url_ = window.location.origin + window.location.pathname + '/printXdni?d=' + (this.doc);
                window.open(url_,'_blank');
            }

            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/print?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes);
            window.open(url_,'_blank');
        },

        clearRed: function(){
            this.table = false
            $("#red").select2("val", '0');
            $("#distrito").select2("val", '0');
            $("#anio").select2("val", '0');
            $("#mes").select2("val", '0');
        },

        clearDocumento: function(){
            this.table = false
            document.getElementById('doc').value = '';
        },

        clearCategory: function(){
            this.table = false
            $("#category").select2("val", '0');
        }
    }
})