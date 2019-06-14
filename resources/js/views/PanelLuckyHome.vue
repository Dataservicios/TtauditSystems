<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-link" type="button"  v-on:click="verForm = !verForm">
                                <strong>Importar </strong> Datos para analizar
                            </button>
                        </div>
                        <div class="card-body" v-if="verForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="fullname" v-model="fullname">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input class="form-control" type="file" id="file" ref="file" v-on:change="handleFileUpload()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary" v-on:click="submitFile()">
                                            <i class="fa fa-dot-circle-o"></i> Importar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                    <div class="card">
                        <div class="card-header">
                            Información Subida: <strong>{{title_upload}}</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
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
                                    <div class="callout callout-warning">
                                        <small class="text-muted">Facturas por oficina</small>
                                        <br>
                                        <strong class="h4">{{nro_office_invoice}}</strong>
                                        <div class="chart-wrapper">
                                            <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <h4 class="card-title mb-0">Registros por Oficina</h4>
                                    <div class="small text-muted"></div>
                                </div>

                                <div class="col-sm-7 d-none d-md-block">

                                </div>

                            </div>
                            <div  id="chartBar" style="height: 250px">
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <h4 class="card-title mb-0">Facturas por Oficina</h4>
                                    <div class="small text-muted"></div>
                                </div>

                                <div class="col-sm-7 d-none d-md-block">

                                </div>

                            </div>
                            <div  id="chartBarInvoice" style="height: 250px">
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
    am4core.useTheme(am4themes_animated);

    export default {
        name: "PanelLucky",
        components: {
            Loading
        },
        data(){
            return{
                verForm:true,
                isLoading1: false,
                file: '',
                fullname:'',
                valores:false,
                datos:[],
                title_upload:'',
                nro_reg_upload:0,
                nro_office_invoice:0,
                nro_office:0
            }
        },
        mounted(){
            this.sidebarToggle();
        },
        methods:{
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
                categoryAxis.renderer.labels.template.rotation = 45;

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

                // label bullet
                /*var labelBullet = new am4charts.LabelBullet();
                series1.bullets.push(labelBullet);*/
                //labelBullet.label.text = "{valueY.value.formatNumber('#')}";
                // labelBullet.label.text = " {valueY.value.formatNumber('#')} -  ";
                /*labelBullet.label.text = " {valueY.value.formatNumber('#')} - {porcen.formatNumber('#.')} %";
                labelBullet.strokeOpacity = 0;
                labelBullet.stroke = am4core.color("#dadada");*/

                //
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            submitFile(){
                this.isLoading1 = true;
                this.valores=false;
                let formData = new FormData();

                /*
                    Add the form data we need to submit
                */
                formData.append('file', this.file);
                formData.append('fullname', this.fullname);
                /*
                  Make the request to the POST /single-file URL
                */
                let urlUploadFile = '/uploadFileLucky';
                axios.post(urlUploadFile, formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => {
                        this.isLoading1 = false;
                        this.verForm=false;
                        this.datos= response.data;
                        this.title_upload = this.datos.reg.fullname;
                        this.nro_reg_upload = this.datos.nro_reg;
                        this.nro_office = this.datos.count_offices;
                        this.nro_office_invoice = this.datos.count_office_invoices;console.log(this.datos.temp);
                        this.valores=true;
                        this.createGraphBar(this.datos.graph_office,"Nro. Registros","chartBar");
                        this.createGraphBar(this.datos.graph_office_invoice,"Nro. Facturas","chartBarInvoice");


                    })
                    .catch(function (error) {
                        this.isLoading1 = false;
                        console.log(error);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
