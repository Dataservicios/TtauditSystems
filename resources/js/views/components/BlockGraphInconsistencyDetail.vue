<template>
    <div>
        <div id="chartdiv" style="height: 500px"></div>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    import * as am4core from "@amcharts/amcharts4/core";
    import * as am4charts from "@amcharts/amcharts4/charts";
    import am4themes_animated from "@amcharts/amcharts4/themes/animated";
    import EventBus from '../../bus';

    am4core.useTheme(am4themes_animated);

    export default {
        name: "BlockGraphInconsistencyDetail",
        props:['company_id','ubigeo','ubigeos'],
        components: {
            Loading
        },
        data(){
            return{
                valuesInconsistencies:[],
                valuesProcessing:[]
            }
        },
        methods:{
            processingData(){
                let acumCiudIncon=0;let stringArray="";
                for (let i = 0; i<this.ubigeos.length; i++) {
                    let office= this.ubigeos[i].office;
                    for (let j = 0; j<this.valuesInconsistencies[0].length; j++) {
                        let inconsistency_id= this.valuesInconsistencies[0][j].id;
                        let inconsistency_name= this.valuesInconsistencies[0][j].fullname;
                        acumCiudIncon=0;
                        for (let k = 0; k<this.valuesInconsistencies[1].length; k++) {
                            if ((office == this.valuesInconsistencies[1][k].office) && (inconsistency_id == this.valuesInconsistencies[1][k].inconsistency_id)){
                                acumCiudIncon ++;
                            }
                        }
                        if (j==0){
                            stringArray +='{office:"' + office + '",';
                        }
                        stringArray +='inconsistency' + inconsistency_id + ':"' + acumCiudIncon;
                        if (j+1 == this.valuesInconsistencies[0].length){
                            stringArray +='"},';
                        }else{
                            stringArray +='"' + ',';
                        }
                        //this.valuesProcessing.push({'office':office,inconsistency_name:acumCiudIncon})
                    }

                }
                //console.log(stringArray);
                var objectStringArray = (new Function("return [" + stringArray+ "];")());

                //this.valuesProcessing = objectStringArray;
                this.valuesProcessing = objectStringArray;
                //console.log(objectStringArray);
            },
            generateGraph(){
                var chart = am4core.create("chartdiv", am4charts.XYChart);
                console.log(this.valuesInconsistencies[0]);
                console.log(this.valuesInconsistencies[0].length);
                chart.data = this.valuesProcessing;
                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "office";
                categoryAxis.title.text = "Inconsistencias por Oficina";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 20;


                var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "Inconsistencias (%)";
                valueAxis.calculateTotals = true;
                valueAxis.min = 0;
                valueAxis.max = 100;
                valueAxis.strictMinMax = true;
                valueAxis.renderer.labels.template.adapter.add("text", function(text) {
                    return text + "%";
                });

// Create series
                for (let j = 0; j<this.valuesInconsistencies[0].length; j++) {
                    var series = chart.series.push(new am4charts.ColumnSeries());
                    series.dataFields.valueY = "inconsistency" + this.valuesInconsistencies[0][j].id;
                    series.dataFields.valueYShow = "totalPercent";
                    series.dataFields.categoryX = "office";
                    series.name = this.valuesInconsistencies[0][j].fullname;
                    series.tooltipText = "{name}: [bold]{valueY}[/]";
                    series.stacked = true;
                }


// Add cursor
                chart.cursor = new am4charts.XYCursor();
                // Add legend
                chart.legend = new am4charts.Legend();
            }
        },
        mounted() {

            EventBus.$on('sendInconsistencies', (item) => {
                this.valuesInconsistencies=item;
                //console.log("Inconsistencies pasado: ",this.valuesInconsistencies);
                this.processingData();
                this.generateGraph();
            });


            //chart.data =  [{office:"Ayacucho",inconsistency1:"0",inconsistency2:"15",inconsistency3:"0",inconsistency4:"0"},{office:"Chiclayo",inconsistency1:"0",inconsistency2:"7",inconsistency3:"0",inconsistency4:"0"},{office:"Huacho",inconsistency1:"5",inconsistency2:"40",inconsistency3:"36",inconsistency4:"0"},{office:"Lima",inconsistency1:"0",inconsistency2:"0",inconsistency3:"0",inconsistency4:"4"},];

// Add data
            /*chart.data = [{
                "country": "Lithuania",
                "research": 501.9,
                "marketing": 250,
                "sales": 199
            }, {
                "country": "Czech Republic",
                "research": 301.9,
                "marketing": 222,
                "sales": 251
            }, {
                "country": "Ireland",
                "research": 201.1,
                "marketing": 170,
                "sales": 199
            }, {
                "country": "Germany",
                "research": 165.8,
                "marketing": 122,
                "sales": 90
            }, {
                "country": "Australia",
                "research": 139.9,
                "marketing": 99,
                "sales": 252
            }, {
                "country": "Austria",
                "research": 128.3,
                "marketing": 85,
                "sales": 84
            }, {
                "country": "UK",
                "research": 99,
                "marketing": 93,
                "sales": 142
            }, {
                "country": "Belgium",
                "research": 60,
                "marketing": 50,
                "sales": 55
            }, {
                "country": "The Netherlands",
                "research": 50,
                "marketing": 42,
                "sales": 25
            }];*/



        }
    }
</script>

<style scoped>

</style>
