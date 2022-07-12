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
    el: '#appCredMensual',
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
        date_pn: '',
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
        this.listYears();
    },
    methods: {
        filtersProv: function() {
            axios.post('prov')
            .then(respuesta => {
                this.provinces = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        listYears: function(){
            var n = (new Date()).getFullYear()
            var select = document.getElementById("anio");
            for(var i = 2019; i<=n; i++)select.options.add(new Option(i,i));
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
            var type = $('#type').val();

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/print?r=' + (red) + '&d=' + (dist) + '&a=' + (anio)
            + '&m=' + (mes)  + '&type=' + (type)  + '&nameMonth=' + (this.nameMonth) + '&pn=' + (this.date_pn) + '&his=' + (this.date_his);
            console.log(url_);
            window.open(url_,'_blank');
        },
    }
})