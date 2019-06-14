<template>
    <div>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                    <div class="card">
                        <div class="card-body" id="collapseOne" data-parent="#accordion">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label"><strong>Campaña</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedCompany" >
                                        <option value="0" selected="selected">Todos</option>
                                        <option v-for="company in companies"  :value='company'>{{ company.campaigne }} </option>
                                    </select>
                                </div>
                            </div>
                            <b-alert :show="dismissCountDown"
                                     dismissible
                                     fade
                                     variant="info"
                                     @dismissed="dismissCountDown=0"
                                     @dismiss-count-down="countDownChanged">
                                {{mensaje}}
                            </b-alert>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6 col-lg-6">
                    <span class="help-block">Fecha Inicio</span>
                    <input class="form-control" id="date-input1" type="date" name="date-input1" placeholder="date">
                </div>
                <div class="col-sm-6 col-lg-6">
                    <span class="help-block">Fecha Fin</span>
                    <input class="form-control" id="date-input2" type="date" name="date-input2" placeholder="date">
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "PanelAdmin",
        components: {
            Loading
        },
        data() {
            return {
                selectedCompany:0,
                companies:[],
                isLoading: false,
                dismissSecs: 2,
                dismissCountDown: 0,
                mensaje:''
            }
        },
        mounted() {
            this.getData();
        },
        methods:{
            getData()
            {
                let urlBase = '/api/getVersions';
                this.isLoading = true;
                axios.post(urlBase, {
                    vigente: 1
                })
                    .then((response2) => {
                        this.companies = response2.data.comboCompany;
                        console.log(this.companies);
                        this.isLoading = false;

                    })
                    .catch(function (error2) {
                        console.log(error2);
                        this.isLoading = false;
                    });
            },
            sidebarToggle (e) {
                document.body.classList.toggle('sidebar-minimized');
            },
            countDownChanged (dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
        }
    }
</script>

<style scoped>

</style>
