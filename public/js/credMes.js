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
        this.dateHis();
    },
    methods: {
        listCredMensual: function() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","cred");
            this.cumple=0; this.no_cumple=0; this.total=0;
            const getDate = new Date();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": getDate.getFullYear(), "mes": getDate.getMonth()+1 }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;
            console.log(data);

            // if (this.red == '') { toastr.error('Seleccione una Red', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.distrito == '') { toastr.error('Seleccione un Distrito', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.anio == '') { toastr.error('Seleccione un Año', null, { "closeButton": true, "progressBar": true }); }
            // else if (this.mes == '') { toastr.error('Seleccione un Mes', null, { "closeButton": true, "progressBar": true }); }
            // else{
                axios({
                    method: 'POST',
                    url: 'cred/list',
                    data: data,
                })
                .then(response => {
                    console.log(response.data);
                    this.lists = response.data[0];
                    this.listsResum = response.data[1];
                    this.advanceReg = response.data[2];
                    for (let i = 0; i < this.lists.length; i++) {
                        this.total++;
                        this.lists[i].CUMPLE == 'CUMPLE' ? this.cumple++ : this.no_cumple++;
                    }

                    for (let j = 0; j < this.listsResum.length; j++) {
                        var avance = (this.listsResum[j].NUMERADOR/this.listsResum[j].DENOMINADOR)*100;
                        avance % 1 != 0 ? this.listsResum[j].AVANCE = avance.toFixed(1) : this.listsResum[j].AVANCE = avance;
                    }

                    for (let k = 0; k < this.advanceReg.length; k++) {
                        var a = (this.advanceReg[k].NUM/this.advanceReg[k].DEN)*100;
                        a % 1 != 0 ? this.advanceReg[k].ADVANCE = a.toFixed(1) : this.advanceReg[k].ADVANCE = a;
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

        listNoSuple() {
            $(".nominalTable").removeAttr("id");
            $(".nominalTable").attr("id","no_cumplen");
            this.listNoCumplen = [];
            for (let i = 0; i < this.lists.length; i++) {
                if(this.lists[i].CUMPLE == 'NOCUMPLE'){
                    this.listNoCumplen.push(this.lists[i]);
                }
            }
            this.lists = this.listNoCumplen;
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
            console.log(url_);
            window.open(url_,'_blank');
        },
    }
})