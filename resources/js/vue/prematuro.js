const appPer = new Vue({
    delimiters: ['[[', ']]'],
    el: '#appPremature',
    data:{
        errors: [],
        view: true,
        lists: [],
        data: 'hola'
    },
    created: function () {
        this.listPremature();
    },
    methods: {

        listPremature: function(){
            axios({
                method:'get',
                url:'fed/',
                responseType:'json'

            }).then(response =>{
                this.lists = response.data;
                console.log(this.lists);

            }).catch(e => {
                this.errors.push(e)
            })
        },

        searchVaccine(){
            if(this.buscador.length < 8){
                toastr.warning('La cantidad de dígitos es incorrecto', null, { "closeButton": true, "progressBar": true });
            }else{
                axios.get('searchdoc/', { params: { 'dni': this.buscador } })
                .then(response => {
                    this.listaVacunas = response.data;
                    this.view_data = true;
                    this.buscador = '';
                    this.listaVacunas[0].PRIMERA_GRUPO != null ? first = this.listaVacunas[0].PRIMERA_GRUPO.replace('ni', 'ñ') : first = null;
                    this.listaVacunas[0].SEGUNDA_GRUPO != null ? second = this.listaVacunas[0].SEGUNDA_GRUPO.replace('ni', 'ñ') : second = null;
                    this.listaVacunas[0].TERCERA_GRUPO != null ? third = this.listaVacunas[0].TERCERA_GRUPO.replace('ni', 'ñ') : third = null;
                    this.listaVacunas[0].CUARTA_GRUPO != null ? fourth = this.listaVacunas[0].CUARTA_GRUPO.replace('ni', 'ñ') : fourth = null;
                    this.listaVacunas[0].first = first;
                    this.listaVacunas[0].second = second;
                    this.listaVacunas[0].thirds = third;
                    this.listaVacunas[0].fourth = fourth;

                }).catch(er => {
                    this.errors.push(er)
                });
            }
        },
    }
})