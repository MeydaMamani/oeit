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
    el: '#appFlatFile',
    data: {
        errors: [],
        lists: [],
        listsResum: [],
        listProvinces: [],
        districts: {},
        listEstablishment: [],
        listUps: [],
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        stablishment: '',
        name_ups: '',
    },
    created: function() {
        this.filtersProv();
        this.filtersUps();
        this.listYears();
    },
    methods: {
        filtersProv: function() {
            axios.post('prov')
            .then(respuesta => {
                this.listProvinces = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersUps: function() {
            axios.post('ups')
            .then(respuesta => {
                this.listUps = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        listYears: function(){
            var n = (new Date()).getFullYear()
            var select = document.getElementById("anio");
            for(var i = 2021; i<=n; i++)select.options.add(new Option(i,i));
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

        PrintNominal: function(){
            var red = $('#red').val();
            var dist = $('#distrito').val();
            var anio = $('#anio').val();
            var mes = $('#mes').val();
            var estab = $('#stablishment').val();
            var ups = $('#name_ups').val();
            console.log(ups);

            const getDate = new Date();
            red == '' ? red = "TODOS" : red;    dist == '' ? dist = "TODOS" : dist;
            anio == '' ? anio = getDate.getFullYear() : anio;     mes == '' ? mes = getDate.getMonth() : mes;
            url_ = window.location.origin + window.location.pathname + '/print?r=' + (red) + '&d=' + (dist) + '&e=' + (estab) + '&a=' + (anio)
            + '&m=' + (mes);
            window.open(url_,'_blank');
        },
    }
})