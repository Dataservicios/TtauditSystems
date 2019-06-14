<template>
    <div>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header" >
                            <div class="row align-items-center mt-3">
                                <div class="col-lg-2 col-sm-2 col-md-2 mb-2 mb-xl-0">
                                    <button class="btn btn-link" type="button"  v-on:click="verForm = !verForm">
                                        <strong>Inconsistencias</strong>  Lucky
                                    </button>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-3 mb-2 mb-xl-0 text-center">
                                    <button class="btn btn-primary" type="link"><i class="icon-home"></i> Base:
                                        <span class="badge badge-light badge-pill" style="position: static;">{{base}}</span>
                                        Verificados:
                                        <span class="badge badge-light badge-pill" style="position: static;">{{analizados}}</span>
                                        Con Inconsistencias:
                                        <span class="badge badge-light badge-pill" style="position: static;">{{conInconsistencias}}</span>
                                    </button>
                                </div>
                                <div class="col-lg-7 col-sm-7 col-md-7 mb-2 mb-xl-0 text-right">
                                    <span class="badge badge-pill badge-success" v-if="selectedCompany != ''">{{selectedCompany.fullname}}</span>
                                    <span class="badge badge-success" v-if="selectedUbigeo != ''">{{selectedUbigeo}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" v-if="verForm" id="collapseOne" data-parent="#accordion">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label"><strong>Campaña</strong></label>
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" v-model="selectedCompany" @change="loadCombos">
                                        <option value="" disabled>Seleccionar</option>
                                        <option v-for="company in companies"  :value='company'>{{ company.fullname }} </option>
                                    </select>
                                    <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                </div>
                                <label class="col-md-2 col-form-label"><strong>Oficinas</strong></label>
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" v-model="selectedUbigeo" @change="loadFilters">
                                        <option value="" >TODOS</option>
                                        <option v-for="ubigeo in ubigeos"  :value="ubigeo.office">{{ ubigeo.office }} </option>
                                    </select>
                                    <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
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

            <div class="row" v-if="resumen">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                    <div class="accordion" id="accordionPolls">
                        <div class="card" >
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" href="#multiCollapseExample1" aria-expanded="true" aria-controls="multiCollapseExample1">
                                                Resumen
                                            </button>
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div  class="multi-collapse collapse show" id="multiCollapseExample1">
                                <div class="card-body">
                                    <BlockGraphInconsistency :company_id="selectedCompany.id" :ubigeo="selectedUbigeo"></BlockGraphInconsistency>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionPolls1">
                        <div class="card" >
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" href="#multiCollapseExample1" aria-expanded="true" aria-controls="multiCollapseExample1">
                                                Inconsistencias
                                            </button>
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div  class="multi-collapse collapse show" id="multiCollapseExample11">
                                <div class="card-body">
                                    <BlockGraphInconsistencyDetail :company_id="selectedCompany.id" :ubigeo="selectedUbigeo" :ubigeos="ubigeos"></BlockGraphInconsistencyDetail>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;overflow-y: scroll;" aria-hidden="true">
            <div class="modal-dialog full_modal-dialog" role="document">
                <div class="modal-content full_modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Detalle {{ nombInconsistency}}</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button class="btn btn-primary" type="button">
                                    <JsonExcel
                                        class   = "fa fa-lightbulb-o"
                                        :data   = "json_data"
                                        :fields = "json_fields"
                                        worksheet = "My Worksheet"
                                        :name    = "nombExcel">
                                    </JsonExcel>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered datatable dataTable no-footer"
                                           id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info"
                                           style="border-collapse: collapse !important">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                #
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">Nombres
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1" >Oficina
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                Mercado
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                PDV
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                # Factura
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                Campaña
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                Producto
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1">
                                                Monto
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1" >Foto
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                                colspan="1" >Comentario
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr role="row" class="odd" v-for="(storeM,index) in storesMedias" v-if="viewStores">
                                            <td class="sorting_1">{{index + 1}}</td>
                                            <td>{{storeM.fullname}}</td>
                                            <td>{{storeM.office}}</td>
                                            <td>{{storeM.market}}</td>
                                            <td>{{storeM.pdv_description}}</td>
                                            <td>{{storeM.invoice_number}}</td>
                                            <td>{{storeM.campaigne}}</td>
                                            <td>{{storeM.product}}</td>
                                            <td>{{storeM.amount}}</td>
                                            <td v-if="storeM.photo_url !=''">
                                                <img :src="storeM.photo_url"  class="rounded mx-auto d-block img-fluid" data-toggle="modal" data-target="#modalImage" @click="loadImageModal(storeM.photo_url)" style="width: 20px; height: 40px">
                                            </td>
                                            <td v-else></td>
                                            <td>{{storeM.comment}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>

            </div>

        </div>

        <div class="modal fade" id="modalImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;overflow-y: scroll;" aria-hidden="true">
            <div class="modal-dialog modal-warning modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Imagen</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img :src="imageRoute" class="img-fluid">
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import BlockGraphInconsistency from "./components/BlockGraphInconsistency";
    import BlockGraphInconsistencyDetail from "./components/BlockGraphInconsistencyDetail";
    import EventBus from '../bus';
    import JsonExcel from 'vue-json-excel';

    export default {
        name: "LuckyInconsistency",
        components: {
            Loading,
            BlockGraphInconsistency,
            BlockGraphInconsistencyDetail,
            JsonExcel
        },
        mounted(){
            
            this.sidebarToggle();
            this.getCampaignes();
            EventBus.$on('refreshResumens', (item) => {
                this.valuesResume=item;
                //console.log(this.valuesResume);
                this.base = this.valuesResume[0];
                this.analizados = this.valuesResume[1];
                this.conInconsistencias = this.valuesResume[2];
            });
            EventBus.$on('loadValuesModal', (item) => {
                console.log(item)
                this.json_fields = new Object();
                this.storesMedias=item;
                let inconsistency_id;
                var archivo_foto ='';
                this.json_data = [];
                for (var indice in this.storesMedias) {
                    if (this.storesMedias.photo_url !='')
                    {
                        archivo_foto = "<a href='http://ttaudit.com/media/fotos/'>" + this.storesMedias[indice]['photo_url'] + "</a>";
                    }else{
                        archivo_foto = "";
                    }
                    inconsistency_id = this.storesMedias[indice].inconsistency_id;
                    this.nombInconsistency = this.storesMedias[indice].fullname;
                    this.json_data.push({id: this.storesMedias[indice].id,fullname: this.storesMedias[indice]['fullname'], office: this.storesMedias[indice]['office'], market: this.storesMedias[indice]['market'],pdv_description: this.storesMedias[indice]['pdv_description'], invoice_number: this.storesMedias[indice]['invoice_number'], campaigne: this.storesMedias[indice]['campaigne'], product: this.storesMedias[indice]['product'], amount: this.storesMedias[indice]['amount'], archivo: archivo_foto, comment: this.storesMedias[indice]['comment'], created_at: this.storesMedias[indice]['created_at']});
                }
                this.nombExcel = this.selectedCompany.id + '_Inconsistencies_' + inconsistency_id + '.xls';
                this.json_fields.Id = "id";
                this.json_fields.Nombres = "fullname";
                this.json_fields.Oficina = "office";
                this.json_fields.Mercado = "market";
                this.json_fields.Pdv = "pdv_description";
                this.json_fields.Factura = "invoice_number";
                this.json_fields.Mercadeo = "campaigne";
                this.json_fields.Producto = "product";
                this.json_fields.Monto = "amount";
                this.json_fields.Foto = "archivo";
                this.json_fields.Comentario = "comment";
                this.json_fields.Fecha = "created_at";
                //this.json_fields.push('Id':'id','Nombres':'fullname','Mercado':'region','Modulo':'district','Oficina':'ubigeo','foto':'archivo','Comentario':'comentario','Audio':'audio');
                this.json_meta = [
                    {
                        'key': 'charset',
                        'value': 'utf-8'
                    }
                ];
                this.viewStores = true;

            });
        },
        data() {
            return {
                resumen:false,
                verForm:true,
                mensaje:'',
                base:0,
                analizados:0,
                conInconsistencias:0,
                selectedCompany:'',
                selectedUbigeo:'',
                companies: [],
                ubigeos: [],
                isLoading: false,
                isLoading1: false,
                isLoading2: false,
                isLoading3: false,
                fullPage: false,
                dismissSecs: 2,
                dismissCountDown: 0,
                customer_id:4,
                study_id:20,
                filters:[],
                valuesResume:[],
                storesMedias:[],
                viewStores:false,
                imageRoute:'',
                buscarCompany:0,
                json_data:[],
                json_fields:new Object(),
                json_meta:[],
                nombExcel:'',
                nombInconsistency:''
            }
        },
        methods:{
            loadImageModal(Image) {
                let ImageLink= Image;

                this.imageRoute=ImageLink;
            },
            sidebarToggle (e) {
                document.body.classList.toggle('sidebar-minimized');
            },
            countDownChanged (dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            getCampaignes()
            {
                this.companies = [];
                this.isLoading = true;
                let urlCombo = '/api/getCampaignes/'+ this.customer_id + '/1/' + this.study_id + '/T/';
                axios.get(urlCombo)
                    .then((response) => {
                        //console.log(urlCombo,this.customer_id,this.study_id,'data: ',response.data)
                        this.companies = response.data;
                        this.isLoading = false;
                    })
                    .catch((response) => {
                        this.isLoading = false;
                        console.log('Error', error);
                    })
            },
            getUbigeos()
            {
                this.ubigeos = [];
                this.isLoading2 = true;
                let urlCombo = '/api/getOfficeForCampaigneWithInconsistency/'+ this.selectedCompany.id;
                axios.get(urlCombo)
                    .then((response) => {
                        this.ubigeos = response.data
                        this.isLoading2 = false;
                        //this.getCategories();
                    })
                    .catch((response) => {
                        this.isLoading2 = false;
                        console.log('Error', error);
                    })
            },
            loadCombos(){
                this.ubigeos = [];
                this.selectedUbigeo='';
                this.getUbigeos();
                this.base = 0;
                this.analizados = 0;
                this.resumen=true;
                this.buscarCompany=1;
                this.loadFilters();
            },
            loadFilters()
            {
                this.filters = [this.selectedUbigeo];
                EventBus.$emit('filtersRefresh', this.filters);
                this.buscarCompany=0;
            }
        }
    }
</script>

<style scoped>

</style>
