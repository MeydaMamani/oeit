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
        // this.filtersProv();
    },
    methods: {

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