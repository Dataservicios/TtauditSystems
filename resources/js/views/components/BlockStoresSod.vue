<template>
    <div v-if="verComponente">
        <div class="row">
            <div class="col-sm-4">
                <div class="callout callout-info">
                    <small class="text-muted">Publicidad</small>
                    <br>
                    <strong class="h4">{{this.selectedCategory.fullname + "(" + this.selectedCategory.id + ")"}}</strong>
                    <div class="chart-wrapper">
                        <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="callout callout-warning">
                    <small class="text-muted">Total Base</small>
                    <br>
                    <strong class="h4">{{this.totalBase}}</strong>
                    <div class="chart-wrapper">
                        <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="callout callout-danger">
                    <small class="text-muted">Avance No Medidos</small>
                    <br>
                    <strong class="h4">{{this.totalNoMedidos}} ({{this.porcentNoMedidos + ' %'}})</strong>
                    <div class="progress progress-xs my-2">
                        <div class="progress-bar bg-info" role="progressbar" v-bind:style="'width:' + this.porcentNoMedidos + '%' " v-bind:aria-valuenow="this.porcentNoMedidos" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                    </div>
                </div>
            </div>

        </div>
        <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            {{stores_indice[indice].store_id}} - {{stores_indice[indice].fullname}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div class="callout callout-warning d-inline-flex col-auto" v-for="product in productsPublicities">
                                    <label class="col-form-label pr-1">{{product.product}}</label>
                                    <input class="form-control" type="text" :id="'valor' + product.product_id">
                                </div>
                                <button class="btn btn-sm btn-warning" type="button" @click="avanza()" :disabled="disabledButton">Grabar</button>
                                <b-alert :show="dismissCountDown"
                                         dismissible
                                         fade
                                         variant="danger"
                                         @dismissed="dismissCountDown=0"
                                         @dismiss-count-down="countDownChanged">
                                    {{mensaje}}
                                </b-alert>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-lg-6">
                                <img :src="'http://ttaudit.com/media/fotos/'+stores_indice[indice].foto" class="rounded img-fluid img-thumbnail" data-toggle="modal" data-target="#modalImage" @click="loadImageModalSod(stores_indice[indice].foto)">
                            </div>
                            <div class="col-sm-6 col-lg-6">
                                <BlockResponseSod></BlockResponseSod>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</template>

<script>
    import EventBus from '../../bus';
    import BlockResponseSod from "./BlockResponseSod";
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "BlockStoresSod",
        components: {BlockResponseSod,Loading},
        props:[],
        data(){
            return{
                isLoading2: false,
                dismissSecs: 2,
                dismissCountDown: 0,
                mensaje:'',
                selectedCompany:'',
                selectedUbigeo: '',
                selectedCategory:'',
                trabajado:0,
                tipo:'',
                selectedAuditor:'',
                stores:[],
                verComponente:false,
                totalBase:0,
                totalNoMedidos:0,
                indice:0,
                stores_indice:[],
                productsPublicities:[],
                disabledButton:false,
                valuesProducts:[],
                sw:0,
                porcentNoMedidos:0,
                valuePolls:[]
            }
        },
        mounted(){
            EventBus.$on('sendValuesSod', (item) => {
                this.verComponente = false;
                this.isLoading2 = true;
                this.indice=0;
                this.stores_indice=[];
                this.selectedCompany = item[0].objCompany;
                this.selectedUbigeo = item[0].ubigeo;
                this.selectedAuditor = item[0].objAuditor;
                this.selectedCategory = item[0].objCategory;
                //this.trabajado = item[0].trabajado;
                this.tipo = item[0].tipo;
                if (item[0].trabajado=="Sod Trabajado")
                {
                    this.trabajado =1;
                }else{
                    this.trabajado =0;
                }
                this.getSods();
            });
        },
        methods:{
            loadImageModalSod(Image) {
                let ImageLink= 'http://ttaudit.com/media/fotos/' + Image;

                EventBus.$emit('loadImageModalSod', ImageLink);
            },
            countDownChanged (dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            showAlert () {
                this.dismissCountDown = this.dismissSecs
            },
            avanza(){
                for (var indice1 in this.productsPublicities) {
                    if ((document.getElementById("valor" + this.productsPublicities[indice1]['product_id']).value) !== "" ){
                        this.sw =1;
                        this.valuesProducts.push(this.productsPublicities[indice1]['product_id']+ "|" +document.getElementById("valor" + this.productsPublicities[indice1]['product_id']).value);
                    }
                }
                if (this.sw == 0)
                {
                    this.valuesProducts =[];
                    this.mensaje="Ingresar Medición de Productos";this.showAlert();
                }else{
                    /*console.log("company_id: ",this.selectedCompany.id);
                    console.log("indice: ",this.indice);
                    console.log("publicity_details_id: ",this.stores_indice[this.indice].publicity_details_id);
                    console.log("valuesProducts: ",this.valuesProducts);
                    console.log(this.stores_indice[this.indice].foto);
                    console.log(this.stores_indice[this.indice].publicity_id);*/
                    this.sw=0;
                    this.mensaje="Datos guardandose un momento por favor ...";this.showAlert();
                    this.disabledButton=true;
                    this.saveSod();
                    /*if ((this.indice+1)<this.totalNoMedidos)
                    {
                        this.indice = this.indice + 1;
                    }*/
                }

            },
            saveSod(){
                this.isLoading2 = true;
                let urlBase = '/api/saveSOD';
                axios.post(urlBase, {
                    company_id: this.selectedCompany.id,
                    publicityDetail_id: this.stores_indice[this.indice].publicity_details_id,
                    sod: this.valuesProducts,
                    foto: this.stores_indice[this.indice].foto,
                    publicity_id: this.stores_indice[this.indice].publicity_id
                })
                    .then((response) => {
                        this.isLoading2 = false;
                        this.mensaje="datos guardados ...";this.showAlert();
                        for (var indice1 in this.productsPublicities) {
                            if ((document.getElementById("valor" + this.productsPublicities[indice1]['product_id']).value) !== "" ){
                                document.getElementById("valor" + this.productsPublicities[indice1]['product_id']).value="";
                            }
                        }
                        this.totalNoMedidos = this.totalNoMedidos -1;
                        this.porcentNoMedidos = Math.round(this.totalNoMedidos*100/this.totalBase);
                        if ((this.indice+1)<this.totalNoMedidos)
                        {
                            this.indice = this.indice + 1;this.disabledButton = false;
                        }else{
                            this.mensaje="Se acabo la medición de esta selección ...";this.showAlert();
                        }

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getSods() {
                this.isLoading2 = true;
                let urlBase = '/api/listStoresPublicities';
                axios.post(urlBase, {
                    company_id: this.selectedCompany.id,
                    ciudad: this.selectedUbigeo,
                    auditor: this.selectedAuditor.id,
                    publicity: this.selectedCategory.id,
                    tipo: this.tipo,
                    trabajada: this.trabajado
                })
                    .then((response) => {
                        this.stores = response.data.BaseNoMedidos;
                        this.verComponente = true;
                        this.totalBase = response.data.TotalBase;
                        this.totalNoMedidos = response.data.TotalNoMedidos;
                        this.porcentNoMedidos = Math.round(this.totalNoMedidos*100/this.totalBase);
                        //console.log(response);
                        //console.log(this.stores);
                        //console.log(this.totalBase," - ",this.totalNoMedidos);
                        var storeReor = this.stores;
                        for (var indice1 in storeReor) {
                            //console.log("En el índice " + indice + " hay este valor: " + valor);
                            this.stores_indice.push({
                                store_id: indice1,
                                fullname: storeReor[indice1][0]['fullname'],
                                foto: storeReor[indice1][0]['Foto'],
                                publicity_id: storeReor[indice1][0]['publicity_id'],
                                publicity_details_id: storeReor[indice1][0]['publicity_details_id']
                            });
                        }
                        //console.log(this.stores_indice[this.indice].publicity_id);
                        this.getProducts();
                    })
                    .catch(function (error) {
                        this.isLoading2 = false;
                        console.log(error);
                    });
            },
            getProducts(){
                let urlResponse = '/api/getProductsPublicity/' + this.selectedCompany.id + '/' + this.stores_indice[this.indice].publicity_id;

                axios.get(urlResponse)
                    .then((response) => {
                        // handle success
                        this.isLoading2 = false;
                        this.productsPublicities = response.data;
                        this.valuePolls.push({store_id:this.stores_indice[this.indice].store_id,company_id:this.selectedCompany.id,publicity_id:this.stores_indice[this.indice].publicity_id});
                        EventBus.$emit('loadResponseSod', this.valuePolls);
                        //console.log(this.valuePolls);
                    })
                    .catch(function (error) {
                        // handle error
                        this.isLoading2 = false;
                        console.log(error);
                    })
            }
        }
    }
</script>

<style scoped>

</style>