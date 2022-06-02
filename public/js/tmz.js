const appPrematuros = new Vue({
    delimiters: ['[[', ']]'],
    el: '#appTmz',
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
        red: '',
        distrito: '',
        anio: '',
        mes: '',
    },
    created: function() {
        // this.listPremature();
        this.filtersProv();
    },
    methods: {
        listPremature: function(e) {
            const date = new Date();
            this.anio = date.getFullYear(); this.mes = date.getMonth();
            const currentData = { "red": "TODOS", "distrito": "TODOS", "anio": this.anio, "mes": this.mes }
            const formData = $("#formulario").serialize();
            this.red == '' ? data = currentData : data = formData;
            axios({
                method: 'POST',
                url: 'tmz/list',
                data: data,
            })
            .then(response => {
                this.lists = response.data[0];
                console.log(this.lists);
                this.listsResum = response.data[1];
                this.advanceReg = response.data[2];
                for (let i = 0; i < this.lists.length; i++) {
                    this.total++;
                    this.lists[i].TAMIZADO == 'SI' ? this.cumple++ : this.no_cumple++;
                }

                for (let j = 0; j < this.listsResum.length; j++) {
                    var avance = (this.listsResum[j].NUMERADOR/this.listsResum[j].DENOMINADOR)*100;
                    avance % 1 != 0 ? this.listsResum[j].AVANCE = avance.toFixed(1) : this.listsResum[j].AVANCE = avance;
                }

                for (let k = 0; k < this.advanceReg.length; k++) {
                    var a = (this.advanceReg[k].NUM/this.advanceReg[k].DEN)*100;
                    a % 1 != 0 ? this.advanceReg[k].ADVANCE = a.toFixed(1) : this.advanceReg[k].ADVANCE = a;
                }

                this.avance = Math.round((this.cumple / this.total) * 100);
                document.getElementById("avance").value = parseInt(this.avance);

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersProv: function() {
            axios.get('prov')
            .then(respuesta => {
                this.provinces = respuesta.data;
                // setTimeout(() => $('.show-tick').selectpicker('refresh'));
            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersDistricts() {
            // $("#distrito").empty();
            axios.get('distr', { params: { 'id': this.red } })
            .then(respuesta => {
                this.districts = respuesta.data;
                console.log(this.districts);
                // setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },
    }
})