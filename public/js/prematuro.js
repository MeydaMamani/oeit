const appPrematuros = new Vue({
    delimiters: ['[[', ']]'],
    el: '#appPremature',
    data:{
        errors: [],
        view: true,
        lists: [],
        total: '99',
        suplementado: '56',
        no_suplementado: '15',
        avance: '12'
    },
    created: function () {
        this.listPremature();
    },
    methods: {
        listPremature: function(){
            axios({
                method: 'get',
                url: 'fed/list/',
                responseType:'json'

            }).then(response =>{
                this.lists = response.data;
                // console.log(this.lists);
                // this.listaVacunas[0].CUARTA_GRUPO != null ? fourth = this.listaVacunas[0].CUARTA_GRUPO.replace('ni', 'ñ') : fourth = null;
                // if($consulta['SUPLEMENTADO'] == 'SI' ){ $correctos_new++; }
                    // else{ $incorrectos_new++; }
                for (let i = 0; i < this.lists.length; i++) {
                    this.total++;
                    this.lists[i].SUPLEMENTADO == 'SI' ? this.suplementado++ : this.no_suplementado++;
                }
                this.avance = Math.round((this.suplementado/this.total)*100);
                document.getElementById("avance").value = parseInt(this.avance);

            }).catch(e => {
                this.errors.push(e)
            })
        }
    }
})