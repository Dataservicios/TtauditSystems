<template>
    <div>
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                    <div class="card">
                        <div class="card-header">
                            
                            <div class="row align-items-center mt-3">
                                <div class="col-lg-5 col-sm-5 col-md-3 mb-3 mb-xl-3">
                                    Información Subida:
                                    <button class="btn btn-link" type="button"  v-on:click="verForm = !verForm">
                                         <strong>{{title_upload}}</strong>  
                                    </button>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-2 mb-2 mb-xl-2 text-right">
                                    <span class="badge badge-pill badge-success" v-if="selectedCompany != ''">{{selectedCompany.fullname}}</span>
                                    <span class="badge badge-success" v-if="selectedUbigeo != '0'">{{selectedUbigeo}}</span>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-1 mb-1 mb-xl-1 text-right">
                                    <router-link to="/inconsistencyLucky" class="btn btn-primary">
                                        <i class="icon-share-alt"></i>&nbsp;Ver Inconsistencias
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                    <select class="form-control form-control-sm" v-model="selectedUbigeo" @change="gatDataValidation">
                                        <option value="" >TODOS</option>
                                        <option v-for="ubigeo in ubigeos"  :value="ubigeo.office">{{ ubigeo.office }} </option>
                                    </select>
                                    <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col">
                                    <BlockGraphGeneralV2 :type="4"  div="graphPruebas" :dataGraph=divPruebas divLegend="legenddiv"></BlockGraphGeneralV2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <BlockGraphGeneralV2 :type="4"  div="graphPruebas1" :dataGraph=divPruebas1 divLegend="legenddiv1"></BlockGraphGeneralV2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4" id="legenddiv">

                                </div>
                                <div class="col-sm-4" id="legenddiv1">
                                    
                                </div>
                            </div> -->
                            <div class="row" v-if="resumen">
                                <div class="col-sm-4">
                                    <div class="callout callout-info">
                                        <small class="text-muted">Número Registros</small>
                                        <br>
                                        <strong class="h4">{{nro_reg_upload}}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="callout callout-danger">
                                        <small class="text-muted">Número de oficinas</small>
                                        <br>
                                        <strong class="h4">{{nro_office}}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="callout callout-danger">
                                        <small class="text-muted">Número de Documentos</small>
                                        <br>
                                        <strong class="h4">{{nro_office_invoice}}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="resumen">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                                                <li class="nav-item">
                                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Resumen</a>
                                                </li>
                                                <li class="nav-item">
                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Categorias</a>
                                                </li>
                                                <li class="nav-item">
                                                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false" @click="loadClients">Clientes</a>
                                                </li>
                                                <li class="nav-item">
                                                <a class="nav-link" id="pills-scala-tab" data-toggle="pill" href="#pills-scala" role="tab" aria-controls="pills-scala" aria-selected="false" @click="loadScala">Scala</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Registros por Oficina
                                                                </div>
                                                                <div class="card-body">
                                                                    <div  id="chartBar" style="height: 250px"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Documentos por Oficina
                                                                </div>
                                                                <div class="card-body">
                                                                    <div  id="chartBarInvoice" style="height: 250px"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Conteo de Comprobantes
                                                                </div>
                                                                <div class="card-body">
                                                                    <BlockGraphGeneralV1 :type="2"  div="graph1"></BlockGraphGeneralV1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Ticket Promedio
                                                                </div>
                                                                <div class="card-body">
                                                                    <BlockGraphGeneralV1 :type="2"  div="graph2"></BlockGraphGeneralV1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Ranking Clientes
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <!-- <BlockGraphGeneralV1 :type="4"  div="graph3"></BlockGraphGeneralV1> -->
                                                                            <BlockGraphGeneralV2 :type="4"  div="graph3" :dataGraph=graphScalas2 divLegend="legenddiv" :series=series2 :config=configGraphSacala2></BlockGraphGeneralV2>
                                                                        </div>
                                                                        <div class="col-sm-4" id="legenddiv">
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-scala" role="tabpanel" aria-labelledby="pills-scala-tab">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Ranking de Scalas
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <!-- <BlockGraphGeneralV1 :type="4"  div="graphScala1"></BlockGraphGeneralV1> -->
                                                                            <BlockGraphGeneralV2 :type="4"  div="graphScala1" :dataGraph=graphScalas1 divLegend="legenddiv1" :series=series1 :config=configGraphSacala1></BlockGraphGeneralV2>
                                                                        </div>
                                                                        <div class="col-sm-4" id="legenddiv1">
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    Ranking de Scalas por Cliente
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <!-- <BlockGraphGeneralV1 :type="4"  div="graphScala1"></BlockGraphGeneralV1> -->
                                                                            <BlockGraphGeneralV2 :type="4"  div="graphScala12" :dataGraph=graphScalas12 divLegend="legenddiv12" :series=series12 :config=configGraphSacala3></BlockGraphGeneralV2>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12" id="legenddiv12">
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loading :active.sync="isLoading1" :can-cancel="false" :is-full-page="false" :height="20"></loading>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import * as am4core from "@amcharts/amcharts4/core";
    import * as am4charts from "@amcharts/amcharts4/charts";
    import am4themes_animated from "@amcharts/amcharts4/themes/animated";
    import BlockGraphGeneralV1 from "./components/BlockGraphGeneralV1";
    import BlockGraphGeneralV2 from "./components/BlockGraphGeneralV2";
    import EventBus from '../bus'
    am4core.useTheme(am4themes_animated);

    export default {
        name: "PanelValidacionCanjes",
        components: {
            Loading,
            BlockGraphGeneralV1,
            BlockGraphGeneralV2
        },
        data(){
            return{
                verForm:true,
                isLoading: false,
                isLoading1: false,
                isLoading2:false,
                file: '',
                fullname:'',
                valores:false,
                datos:[],
                datosScala:[],
                title_upload:'Seleccionar Filtros',
                nro_reg_upload:0,
                nro_office_invoice:0,
                nro_office:0,
                customer_id:4,
                study_id:20,
                companies: [],
                ubigeos: [],
                selectedCompany:'',
                selectedUbigeo:'',
                resumen:false,
                graphScalas1:[],
                graphScalas2:[],
                graphScalas12:[],
                series1:[],
                series2:[],
                series12:[],
                configGraphSacala1:[],
                configGraphSacala2:[],
                configGraphSacala3:[]
                // divPruebas: [],
                // divPruebas1: [],
            }
        },
        mounted(){
            this.sidebarToggle();
            this.getCampaignes();
        },
        watch: {
            ubigeo: function (newQuestion) {
                this.createGraphBar(this.datos.graph_office,"Nro. Registros","chartBar");
                this.createGraphBar(this.datos.graph_office_invoice,"Nro. Documentos","chartBarInvoice");
            }
        },
        methods:{
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
                this.resumen=true;
                this.gatDataValidation();
            },
            sidebarToggle (e) {
                document.body.classList.toggle('sidebar-minimized');
            },
            createGraphBar(ValuesGraph,NombreEje,DiV){
                //var chart = am4core.create(this.$refs.chartBar, am4charts.XYChart3D);
                var chart = am4core.create(DiV, am4charts.XYChart3D);

                chart.data =ValuesGraph;
                chart.exporting.menu = new am4core.ExportMenu();
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "opcion";
                //categoryAxis.title.text = "Opciones";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;
                //categoryAxis.renderer.labels.template.rotation = 45;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = NombreEje;

                /* Create series */
                var series1 = chart.series.push(new am4charts.ColumnSeries3D());
                series1.columns.template.width = am4core.percent(80);
                series1.columns.template.tooltipText = "{categoryX}: [bold]{valueY} - {porcen.formatNumber('#.')} %[/]";
                series1.dataFields.categoryX = "opcion";
                series1.dataFields.valueY = "valor";
                //series1.calculateTotals = true;
                series1.stacked = true;

                chart.exporting.menu.items = [
                    {
                        "label": "...",
                        "menu": [
                            {
                                "label": "Image",
                                "menu": [
                                    { "type": "png", "label": "PNG" },
                                    { "type": "jpg", "label": "JPG" },
                                    { "type": "pdf", "label": "PDF" }
                                ]
                            }, {
                                "label": "Data",
                                "menu": [
                                    { "type": "xlsx", "label": "XLSX" }
                                ]
                            }, {
                                "label": "Print", "type": "print"
                            }
                        ]
                    }
                ];
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            gatDataValidation(){
                this.isLoading1 = true;
                this.valores=false;

                let urlBase = '/api/validacionCanjes';
                let ubigeoSearch;

                this.isLoading1 = true;
                if (this.selectedUbigeo == '')
                {
                    ubigeoSearch = 0;
                }else{
                    ubigeoSearch = this.selectedUbigeo;
                }
                axios.post(urlBase, {
                        company_id: this.selectedCompany.id,
                        ubigeo: ubigeoSearch
                    })
                    .then((response) => {
                        this.isLoading1 = false;
                        this.datos= response.data;
                        this.title_upload = this.datos.reg;
                        this.nro_reg_upload = this.datos.nro_reg;
                        this.nro_office = this.datos.count_offices;
                        this.nro_office_invoice = this.datos.count_office_invoices;
                        this.valores=true;

                        this.createGraphBar(this.datos.graph_office,"Nro. Registros","chartBar");//resumen
                        this.createGraphBar(this.datos.graph_office_invoice,"Nro. Documentos","chartBarInvoice");//resumen

                        EventBus.$emit('sendDataGraphGeneral', [this.datos.graph1,'graph1',"Nro. Comprobantes",0,1,10]);//resumen

                        EventBus.$emit('sendDataGraphGeneral', [this.datos.graph2,'graph2',"Monto por Facturas",45,0,8]);//categorias
                        this.loadClients();
                        this.loadScala();
                        
                    })
                    .catch(function (error2) {
                        console.log(error2);
                    });

            },
            loadScala()
            {
                this.isLoading1 = true;
                this.valores=false;

                let urlBase = '/api/scalaCanjes';
                let ubigeoSearch;

                this.isLoading1 = true;
                if (this.selectedUbigeo == '')
                {
                    ubigeoSearch = 0;
                }else{
                    ubigeoSearch = this.selectedUbigeo;
                }
                axios.post(urlBase, {
                        company_id: this.selectedCompany.id,
                        ubigeo: ubigeoSearch
                    })
                    .then((response) => {
                        this.isLoading1 = false;
                        this.datosScala= response.data;
                        this.valores=true;

                        //EventBus.$emit('sendDataGraphGeneralStacked', [this.datosScala.graph1,'graphScala1',"Monto por Facturas",45,0,8,"legenddiv1"]);
                        this.configGraphSacala1.push({stacked:true});
                        this.graphScalas1 = this.datosScala.graph1.datos;
                        this.series1 = this.datosScala.graph1.series;

                        this.configGraphSacala3.push({stacked:false});
                        this.graphScalas12 = this.datosScala.graph2.datos;
                        this.series12 = this.datosScala.graph2.series;
                    })
                    .catch(function (error2) {
                        console.log(error2);
                    });
            },
            loadClients()
            {
                //EventBus.$emit('sendDataGraphGeneralStacked', [this.datos.graph3,'graph3',"Monto por Facturas",45,0,8,"legenddiv"]);
                this.configGraphSacala2.push({stacked:true});
                this.graphScalas2 = this.datos.graph3.datos;
                this.series2 = this.datos.graph3.series;
            }
        }
    }
</script>

<style scoped>

</style>
