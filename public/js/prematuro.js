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
    el: '#appPremature',
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
        red: '',
        distrito: '',
        anio: '',
        mes: '',
        nameMonth: '',
    },
    created: function() {
        // this.listPremature();
        this.filtersProv();
    },
    methods: {
        listPremature: function() {
            const getDate = new Date();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": getDate.getFullYear(), "mes": getDate.getMonth() + 1 }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;

            axios({
                method: 'POST',
                url: 'prematuro/list',
                data: data,
            })
            .then(response => {
                this.lists = response.data[0];
                this.listsResum = response.data[1];
                this.advanceReg = response.data[2];
                for (let i = 0; i < this.lists.length; i++) {
                    this.total++;
                    this.lists[i].SUPLEMENTADO == 'SI' ? this.suplementado++ : this.no_suplementado++;
                }

                for (let j = 0; j < this.listsResum.length; j++) {
                    var avance = (this.listsResum[j].NUMERADOR/this.listsResum[j].DENOMINADOR)*100;
                    avance % 1 != 0 ? this.listsResum[j].AVANCE = avance.toFixed(1) : this.listsResum[j].AVANCE = avance;
                }

                for (let k = 0; k < this.advanceReg.length; k++) {
                    var a = (this.advanceReg[k].NUM/this.advanceReg[k].DEN)*100;
                    a % 1 != 0 ? this.advanceReg[k].ADVANCE = a.toFixed(1) : this.advanceReg[k].ADVANCE = a;
                }

                this.anio == '' ? this.anio = getDate.getFullYear() : this.anio;
                this.mes == '' ? this.mes = getDate.getMonth() + 1 : this.mes;
                this.nameMonth = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format( getDate.setMonth(this.mes - 1));
                this.nameMonth = this.nameMonth.charAt(0).toUpperCase() + this.nameMonth.slice(1);

                this.avance = Math.round((this.suplementado / this.total) * 100);
                // document.getElementById("avance").value = parseInt(this.avance);
                // $(".dial").knob();
                // $(".dial").val(20);
                $('.knob').val(this.avance).trigger('change');
                console.log(this.avance);

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersProv: function() {
            axios.get('prov')
            .then(respuesta => {
                this.provinces = respuesta.data;
                // setTimeout(() => $('.show-tick').selectpicker('refresh'));
                // $("#distrito").trigger('change');
                // $("#distrito").empty();
                // $('#distrito').val('').trigger('change');
            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersDistricts() {
            // $("#distrito").val(null).trigger('change.select2');
            // $("#distrito").select2('data', null, false);
            // $('#distrito').val();
            axios.get('distr', { params: { 'id': this.red } })
            .then(respuesta => {
                this.districts = respuesta.data;
                console.log(this.districts);
                // $("#distrito").empty();
                // setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        PrintNominal: function(_dats){
            url_ = window.location.origin + window.location.pathname + '/print';
            window.open(url_,'_blank');
        },
    }
})