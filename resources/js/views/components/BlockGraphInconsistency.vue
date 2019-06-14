<template>
    <div class="chart-wrapper" >
        <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
        <div class="row">
            <div class="col-sm-6 col-lg-6">
                <div  id="chartBarValidado" style="height: 250px">
                </div>
                <div class="table-responsive">
                    <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Porcentaje</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(data,index) in datosCiudad ">
                                        <th scope="row">{{index + 1}}</th>
                                        <td>{{data.opcion}}</td>
                                        <td>{{data.valor}}</td>
                                        <td style="text-align: center">{{data.porcen.toFixed() + '%'}}</td>
                                    </tr>
                                    </tbody>
                    </table>
                </div>
                <div  id="chartDonout" style="height: 300px">
                </div>
                
            </div>
            <div class="col-sm-6 col-lg-6">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div  id="chartBar" style="height: 250px">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Porcentaje</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(data,index) in datosGraphInconsistency ">
                                    <th scope="row">{{index + 1}}</th>
                                    <td><span class="btn-link" data-toggle="modal" data-target="#infoModal" @click="loadValuesModal(data.obj)">{{data.opcion}}</span></td>
                                    <td>{{data.valor}}</td>
                                    <td style="text-align: center">{{data.porcen.toFixed() + '%'}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    import * as am4core from "@amcharts/amcharts4/core";
    import * as am4charts from "@amcharts/amcharts4/charts";
    import am4themes_animated from "@amcharts/amcharts4/themes/animated";
    import EventBus from '../../bus';
    import BlockGraphGeneral from "./BlockGraphGeneral";

    am4core.useTheme(am4themes_animated);
    export default {
        name: "BlockGraphInconsistency",
        props:['company_id','ubigeo'],
        components: {
            Loading,
            BlockGraphGeneral
        },
        data(){
            return{
                idGet:0,
                isLoading2: false,
                parameters:[],
                datos:[],
                objOrigenInconsistencies:[],
                datosInconsistencyExchange:[],
                datosInconsistencyExchangeFilter:[],
                datosGraphVerify:[],
                objOrigenInconsistencyDetail:[],
                datosInconsistency:[],
                datosInconsistencyFilter:[],
                datosGraphInconsistency:[],
                objInconsistencies:[],
                valuesResume:[],
                objVerifyExchange:[],
                datosVerifyExchange:[],
                datosVerifyExchangeFilter:[],
                datosCiudad:[]
            }
        },
        mounted() {
            this.parameters = [this.ubigeo];
            EventBus.$on('filtersRefresh', (item) => {
                this.parameters=item;console.log('parameters: ',this.parameters);
                if (this.parameters[0] !== "")
                {
                    this.filterData();
                }
            });
            this.getResponses();
        },
        watch: {
            // cuando 'question' cambie, se ejecutará esta función
            company_id: function (newQuestion) {
                this.getResponses()
            }
        },
        methods:{
            loadValuesModal(index) {

                EventBus.$emit('loadValuesModal', index);
            },
            createdBar(DiV,tiTle,DaTos,LabelX){
                // Use themes
                am4core.useTheme(am4themes_animated);
                // Create chart instance
                var chart = am4core.create(DiV, am4charts.XYChart3D);

                chart.data = DaTos;
                chart.exporting.menu = new am4core.ExportMenu();
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "opcion";
                //categoryAxis.title.text = "Opciones";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.labels.template.disabled = LabelX


                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = tiTle;

                /* Create series */
                var series1 = chart.series.push(new am4charts.ColumnSeries3D());
                series1.columns.template.width = am4core.percent(80);
                series1.columns.template.tooltipText = "{categoryX}: [bold]{valueY} - {porcen.formatNumber('#.')} % [/]";
                //series1.name = this.datos[indice]['opcion'];
                series1.dataFields.categoryX = "opcion";
                series1.dataFields.valueY = "valor";
                
                //series1.calculateTotals = true;
                series1.stacked = true;
                //
            },
            createdDonout(DiV,Label,Quantity,Legend){

                let chart = am4core.create(DiV, am4charts.PieChart3D);

                chart.data = this.datosGraphVerify;
                chart.exporting.menu = new am4core.ExportMenu();
                chart.numberFormatter.outputFormat = "#.";

                let pieSeries = chart.series.push(new am4charts.PieSeries3D());
                chart.innerRadius = am4core.percent(40);
                pieSeries.dataFields.value = Quantity;
                pieSeries.dataFields.category = Label;
                pieSeries.labels.template.text = "{value.percent.formatNumber('#.')} %";
                if (Legend){
                    chart.legend = new am4charts.Legend();
                }

                //this.datos=[];
            },
            checkAdult(age){
                return age >= 33;
            },
            getNameInconsistency(inconsis){
                return inconsis.id === this.idGet;
            },
            filterData(){
                this.datosGraphInconsistency=[];

                this.datosInconsistencyExchange = this.objOrigenInconsistencies;
                this.datosInconsistencyExchangeFilter=[];

                this.datosInconsistency = this.objOrigenInconsistencyDetail;
                this.datosInconsistencyFilter=[];


                this.datosVerifyExchange = this.objVerifyExchange;//reg verificados en exchange
                this.datosVerifyExchangeFilter = [];


                for (var i = 0; i < this.parameters.length; i++) {
                    if (this.parameters[i] !== "")
                    {
                        for (var j = 0; j < this.datosInconsistencyExchange.length; j++) {
                            if (i==0){
                                //console.log(this.datosInconsistencyExchange[j].office);
                                if (this.datosInconsistencyExchange[j].office==this.parameters[i]){
                                    this.datosInconsistencyExchangeFilter.push(this.datosInconsistencyExchange[j]);
                                }
                            }

                        }
                        this.datosInconsistencyExchange = this.datosInconsistencyExchangeFilter;this.datosInconsistencyExchangeFilter=[];

                        for (var j = 0; j < this.datosInconsistency.length; j++) {
                            if (i==0){
                                //console.log(this.datosVerify[j].office);
                                if (this.datosInconsistency[j].office==this.parameters[i]){
                                    this.datosInconsistencyFilter.push(this.datosInconsistency[j]);
                                }
                            }

                        }
                        this.datosInconsistency = this.datosInconsistencyFilter;this.datosInconsistencyFilter=[];

                        for (var j = 0; j < this.datosVerifyExchange.length; j++) {
                            if (i==0){
                                //console.log(this.datosVerify[j].office);
                                if (this.datosVerifyExchange[j].office==this.parameters[i]){
                                    this.datosVerifyExchangeFilter.push(this.datosVerifyExchange[j]);
                                }
                            }

                        }
                        this.datosVerifyExchange = this.datosVerifyExchangeFilter;this.datosVerifyExchangeFilter=[];


                    }

                }
                this.datosGraphVerify = [{
                    "Verificado": "Con Inconsistencias",
                    "cantidad": this.datosInconsistencyExchange.length
                }, {
                    "Verificado": "Sin Inconsistencias",
                    "cantidad": this.datosVerifyExchange.length - this.datosInconsistencyExchange.length
                }];
                this.createdDonout("chartDonout","Verificado","cantidad",true);

                let objNameInconsistency = "";

                let acumIncon =0;let acumObjInconsistencies=[];let datosGraphIncon=[];
                for (let i = 0; i<this.objInconsistencies.length; i++) {
                    this.idGet= this.objInconsistencies[i].id;
                    for (let j = 0; j<this.datosInconsistency.length; j++) {
                        if (this.idGet === parseInt(this.datosInconsistency[j].inconsistency_id)) {
                            acumIncon ++;acumObjInconsistencies.push(this.datosInconsistency[j]);
                        }
                    }
                    objNameInconsistency = this.objInconsistencies.find(this.getNameInconsistency);
                    datosGraphIncon.push({id: objNameInconsistency.id,opcion: objNameInconsistency.fullname, valor: acumIncon,porcen: (acumIncon/this.datosInconsistency.length)*100,obj:acumObjInconsistencies});
                    acumIncon=0;acumObjInconsistencies=[];
                }
                //ordenar datos con inconsistencias this.datosGraphInconsistency
                function deMayorAMenor(elem1, elem2) {return elem2-elem1;}

                let arrayOrdenar=[];
                for (let i = 0; i<datosGraphIncon.length; i++) {
                    arrayOrdenar[i] = datosGraphIncon[i].porcen;
                }

                arrayOrdenar.sort(deMayorAMenor);

                for (let i = 0; i<arrayOrdenar.length; i++) {
                    for (let j = 0; j<datosGraphIncon.length; j++) {
                        if (arrayOrdenar[i] == datosGraphIncon[j].porcen){
                            this.datosGraphInconsistency.push(datosGraphIncon[j]);
                        }
                    }
                }

                this.valuesResume = [this.datos.base,this.datos.numVerify, this.datosInconsistencyExchange.length];
                EventBus.$emit('refreshResumens', this.valuesResume);
                let valuesSend = [this.datos.objInconsistencies,this.datosInconsistency]
                EventBus.$emit('sendInconsistencies', valuesSend);
                this.createdBar("chartBar","Inconsistencias",this.datosGraphInconsistency,true);

                let datosPorCiudad=[];let ciudad='';
                for (let i = 0; i<this.datosVerifyExchange.length; i++) {
                    datosPorCiudad[this.datosVerifyExchange[i].office] = this.datosVerifyExchange[i].office;
                }
                let nombre='';let acumCiudad=[];let acumTotalOffice=0;
                /* Se recorre un array con indices del mismo valor Ejm: Chiclayo: "Chiclayo",Lima: "Lima"*/ 
                for (nombre in datosPorCiudad) {
                    var valor = datosPorCiudad[nombre];
                    console.log("pos=", nombre, "valor=", valor);
                    let acumOffice=0;
                    for (let i = 0; i<this.datosVerifyExchange.length; i++) {
                        if (valor == this.datosVerifyExchange[i].office)
                        {
                            acumOffice ++;acumTotalOffice ++;
                        }
                    }
                    acumCiudad.push({opcion: valor,valor:acumOffice,porcen:0});acumOffice=0;
                }
                /* Hallando porcentajes de acumuladores por ciudad*/ 
                for (let i = 0; i<acumCiudad.length; i++) {
                    acumCiudad[i].porcen = Math.round((acumCiudad[i].valor/acumTotalOffice)*100);
                }
                this.datosCiudad = acumCiudad;
                this.createdBar("chartBarValidado","Validados",acumCiudad,false);

                console.log('datosPorCiudad: ',datosPorCiudad,'graph:',acumCiudad,'total:',acumTotalOffice);
            },
            getResponses(){
                this.isLoading2 = true;
                let urlResponse = "";
                let ciudad = this.ubigeo;
                let companyId = this.company_id;console.log(this.company_id);
                if (ciudad != '') {
                    urlResponse = '/api/getResultsVerifyForCompanyId/' + companyId +  '/' + ciudad;
                } else {
                    urlResponse = '/api/getResultsVerifyForCompanyId/' + companyId;
                }

                axios.get(urlResponse)
                    .then((response) => {
                        this.isLoading2 = false;
                        console.log('getResponse: ',urlResponse,response);
                        this.datos = response.data;
                        this.objOrigenInconsistencies = this.datos.objInconsistencyReg;//reg con inconsistencias en exchange
                        this.objOrigenInconsistencyDetail =this.datos.objInconsistencyDetails;//detalle inconsistencias
                        this.objInconsistencies = this.datos.objInconsistencies;//inconsistencias
                        this.objVerifyExchange = this.datos.objVerifyReg;//reg verificados en exchange
                        //var ages = [32, 33, 16, 40];
                        //console.log(ages.filter(this.checkAdult));
                        //console.log(this.objOrigenInconsistencies);
                        this.filterData();

                    })
                    .catch((response) => {
                        this.isLoading2 = false;
                        console.log('Error', response);

                    })
            }
        }
    }
</script>

<style scoped>

</style>
