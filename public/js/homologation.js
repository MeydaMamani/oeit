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

// Vue.component("select", {
//     props: ["options", "value"],
//     template: "#select2-template",
//     mounted: function() {
//       var vm = this;
//       $(this.$el)
//         // init select2
//         .select2({ data: this.options })
//         .val(this.value)
//         .trigger("change")
//         // emit event on change.
//         .on("change", function() {
//           vm.$emit("input", this.value);
//         });
//     },
//     watch: {
//       value: function(value) {
//         // update value
//         $(this.$el)
//           .val(value)
//           .trigger("change");
//       },
//       options: function(options) {
//         // update options
//         $(this.$el)
//           .empty()
//           .select2({ data: options });
//       }
//     },
//     destroyed: function() {
//       $(this.$el)
//         .off()
//         .select2("destroy");
//     }
//   });

const appPrematuros = new Vue({
    delimiters: ['[[', ']]'],
    el: '#appHomologation',
    data: {
        errors: [],
        listDepartment: [],
        listProvinces: [],
        listDistricts: [],
        // PARA RESIDENCIA ACTUAL
        listProvinces2: [],
        listDistricts2: [],
        doc: '',
        lists: [],

        listColumnMonth: [],

        mes: '',
        form: {},
        formulary: false,
    },
    created: function() {
        this.filterDepartment();
    },
    methods: {
        listPatient: function() {
            const formData = $("#formulario").serialize();
            if (this.doc == '') { toastr.error('Ingrese su Documento', null, { "closeButton": true, "progressBar": true }); }
            else{
                axios({
                    method: 'POST',
                    url: 'metals/listDni',
                    data: formData,
                })
                .then(response => {
                    this.form = response.data[0];
                    console.log(this.form.N);
                    this.formulary = true
                    $('.footable-page a').filter('[data-page="0"]').trigger('click');

                }).catch(e => {
                    this.errors.push(e)
                })
            }
        },

        filterDepartment: function(){
            axios.post('department')
            .then(respuesta => {
                this.listDepartment = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersProvinces() {
            this.listProvinces = [];
            axios({
                method: 'POST',
                url: 'provinces',
                data: { "id": this.form.REGION_ANTERIOR },
            })
            .then(respuesta => {
                this.listProvinces = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersDistricts() {
            this.listDistricts = [];
            axios({
                method: 'POST',
                url: 'districts',
                data: { "id": this.form.PROVINCIA_ANTERIOR },
            })
            .then(respuesta => {
                this.listDistricts = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersProvinces2() {
            this.listProvinces2 = [];
            axios({
                method: 'POST',
                url: 'provinces',
                data: { "id": this.form.REGION_ACTUAL },
            })
            .then(respuesta => {
                this.listProvinces2 = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        filtersDistricts2() {
            this.listDistricts2 = [];
            axios({
                method: 'POST',
                url: 'districts',
                data: { "id": this.form.DISTRITO_ACTUAL },
            })
            .then(respuesta => {
                this.listDistricts2 = respuesta.data;
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        },

        PrintPDF: function(){
            var doc = $('#doc').val();
            console.log(doc);
            url_ = window.location.origin + window.location.pathname + '/printPdf?d=' + (doc);
            window.open(url_,'_blank');
        },

        PrintExcel: function(){
            var doc = $('#doc').val();
            console.log(doc);
            url_ = window.location.origin + window.location.pathname + '/printExcel?d=' + (doc);
            window.open(url_,'_blank');
        },

        sendForm: function(e){
            // var csrfmiddlewaretoken = document.getElementsByName('csrfmiddlewaretoken')[0].value
            // var body = new FormData(e.target);
            const formData = $("#formulario2").serialize();
            console.log(formData);
            axios({
                // headers: { 'X-CSRFToken': csrfmiddlewaretoken, 'Content-Type': 'multipart/form-data' },
                method: 'PUT',
                url: 'homologation/put/',
                data: formData,

            }).then(response => {
                console.log(response.data);
                toastr.success('Actualizado correctamente', null, { "closeButton": true });
                setInterval("location.reload()",2000);
                setTimeout(() => $('.show-tick').selectpicker('refresh'));
            }).catch(e => {
                this.errors.push(e)
            })
        },

        SelectMonth: function(){
            axios({
                method: 'POST',
                url: 'homologation/month',
                data: { "id": this.mes, "doc": this.doc },
            })
            .then(respuesta => {
                this.listColumnMonth = respuesta.data[0];
                // console.log(this.listColumnMonth);
                setTimeout(() => $('.show-tick').selectpicker('refresh'));

            }).catch(e => {
                this.errors.push(e)
            })
        }

    }
})