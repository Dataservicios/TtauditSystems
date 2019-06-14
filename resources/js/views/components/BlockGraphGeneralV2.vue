<template>
    <div>
        <div  :id="div" style="height: 400px">
        </div>
    </div>
</template>

<script>
    import * as am4core from "@amcharts/amcharts4/core";
    import * as am4charts from "@amcharts/amcharts4/charts";
    import am4themes_animated from "@amcharts/amcharts4/themes/animated";
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import EventBus from '../../bus';

    am4core.useTheme(am4themes_animated);
    export default {
        name: "BlockGraphGeneralV2",
        props:['type','div','dataGraph','divLegend','series','config'],
        data() {
            return {
                datos:[]
            }
        },
        components: {
            Loading
        },
        mounted() {

            if (this.type ==4)
            {
                // EventBus.$on('sendDataGraphGeneralStacked', (item) => {
                //     this.datosGraph = item[0];
                //     this.divLegend = item[6];
                //     console.log('datosStacked: ',this.datosGraph,'item: ',item,this.divLegend);
                //     this.datos = this.datosGraph.datos;
                //     this.createdStackedColumn();
                // });
            }
        },
        watch: {
            // cuando 'question' cambie, se ejecutará esta función
            dataGraph: function (newQuestion,oldQuestion) {
                console.log('div: ',this.div,'newQuestion: ',newQuestion,'oldQuestion: ',oldQuestion);
                if (this.type ==4)
                {
                    this.createdStackedColumn();
                    //this.config[0].stacked
                }
                if (this.type ==2)
                {
                    console.log('this.config: ',this.config);
                    this.createdBar();
                    //this.config[0].TitleY
                    //this.config[0].AngleX
                    //this.config[0].Orientation
                    //this.config[0].FontSizeValY
                }
                if (this.type ==1)
                {
                    this.createdDonout();
                }
            }
        },
        methods:{
            createdStackedColumn(){
                am4core.useTheme(am4themes_animated);
                //am4core.useTheme(am4themes_kelly);
                // Create chart instance
                var chart = am4core.create(this.div, am4charts.XYChart3D);
                //console.log('dataGraph: ',this.dataGraph);
                // Add data
                chart.data =this.dataGraph;
                // chart.data = [ {
                //     "bloque": "Pruebas",
                //     "europe": 2.8,
                //     "namerica": 2.9,
                //     "asia": 2.4,
                //     "lamerica": 0.3,
                //     "meast": 0.3,
                //     "africa": 0.1
                // }];

                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "bloque";
                //categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.labels.template.align = "middle";
                categoryAxis.renderer.labels.template.fontSize=10;
                categoryAxis.renderer.labels.template.hideOversized = true;
                //categoryAxis.renderer.minGridDistance = 20;
                categoryAxis.renderer.labels.template.horizontalCenter = "right";
                categoryAxis.renderer.labels.template.verticalCenter = "middle";
                categoryAxis.tooltip.label.rotation = 270;
                categoryAxis.tooltip.label.horizontalCenter = "right";
                categoryAxis.tooltip.label.verticalCenter = "middle";

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                // valueAxis.renderer.inside = true;
                // valueAxis.renderer.labels.template.disabled = true;
                // valueAxis.min = 0;

                // Create series
                function createSeries(field, name,stackedC) {
                    // Set up series
                    var series = chart.series.push(new am4charts.ColumnSeries3D());
                    series.name = name;
                    series.dataFields.valueY = field;
                    series.dataFields.categoryX = "bloque";
                    series.tooltipText = "{name}: [bold]{valueY}[/]";
                    series.columns.template.fillOpacity = .8;
                    // series.sequencedInterpolation = true;

                    // Make it stacked
                    //console.log(this.config[0].stacked,'datos: ',this.dataGraph);
                    //series.stacked = true;
                    series.stacked = stackedC;
                
                    

                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.lineX.strokeOpacity = 0;
                    chart.cursor.lineY.strokeOpacity = 0;

                    if (stackedC)
                    {
                        // Configure columns
                        series.columns.template.width = am4core.percent(60);
                        series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: " + field + " ({valueY} %)";

                        // Add label
                        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                        labelBullet.label.text = field+" ({valueY} %)";
                        labelBullet.locationY = 0.5;
                    }else{
                        // var columnTemplate = series.columns.template;
                        // columnTemplate.strokeWidth = 2;
                        // columnTemplate.strokeOpacity = 1;
                        // columnTemplate.stroke = am4core.color("#FFFFFF");

                        // columnTemplate.adapter.add("fill", (fill, target) => {
                        // return chart.colors.getIndex(target.dataItem.index);
                        // })

                        // columnTemplate.adapter.add("stroke", (stroke, target) => {
                        // return chart.colors.getIndex(target.dataItem.index);
                        // })
                    }
                    

                    return series;
                }

                for (var indice in this.series) {
                    createSeries(this.series[indice][0], this.series[indice][1],this.config[0].stacked);
                }

                // createSeries("europe", "Europe");
                // createSeries("namerica", "North America");
                // createSeries("asia", "Asia-Pacific");
                // createSeries("lamerica", "Latin America");
                // createSeries("meast", "Middle-East");
                // createSeries("africa", "Africa");
                // Legend
                if (this.divLegend !== '')
                {
                    chart.legend = new am4charts.Legend;
                    chart.legend.itemContainers.template.tooltipText = "[bold]{name}:[/]";

                    var legendContainer = am4core.create(this.divLegend, am4core.Container);
                    legendContainer.width = am4core.percent(100);
                    legendContainer.height = am4core.percent(100);
                    chart.legend.parent = legendContainer;
                }
                this.datos=[];
            },
            createdBar(){
                var TitleY = this.config[0].TitleY;
                var AngleX = this.config[0].AngleX;
                var Orientation = this.config[0].Orientation;
                var FontSizeValY = this.config[0].FontSizeValY;
                // Orientation->0 Horizontal, Orientation->1 Vertical
                // Use themes
                am4core.useTheme(am4themes_animated);

                // Create chart instance
                var chart = am4core.create(this.div, am4charts.XYChart3D);

                chart.data =this.dataGraph;
                chart.exporting.menu = new am4core.ExportMenu();

                if (Orientation == 0 )
                {
                    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
                }else{
                    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                }

                categoryAxis.dataFields.category = "opcion";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;
                categoryAxis.renderer.labels.template.rotation = AngleX;//fontSize 
                categoryAxis.fontSize = 10;

                if (Orientation == 0 )
                {
                    var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
                }else{
                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                }
                valueAxis.title.text = TitleY;


                /* Create series */
                var series1 = chart.series.push(new am4charts.ColumnSeries3D());
                series1.columns.template.width = am4core.percent(80);
                if (Orientation == 0 )
                {
                    series1.columns.template.tooltipText = "{categoryY}: [bold]{valueX} - {porcent.formatNumber('#.')} % [/]";
                }else{
                    series1.columns.template.tooltipText = "{categoryX}: [bold]{valueY} - {porcent.formatNumber('#.')} % [/]";
                }

                if (Orientation == 0 )
                {
                    series1.dataFields.categoryY = "opcion";
                    series1.dataFields.valueX = "valor";
                }else{
                    series1.dataFields.categoryX = "opcion";
                    series1.dataFields.valueY = "valor";
                }

                //series1.calculateTotals = true;
                series1.stacked = true;

                var labelBullet = series1.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "[bold]{labelY}[/]";
                labelBullet.label.dy = -5;
                labelBullet.label.hideOversized = false;
                labelBullet.label.fontSize = FontSizeValY;

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

                //
                this.datos=[];
            },
            createdDonout(){
                //var TitleY = this.config[0].TitleY;
                let chart = am4core.create(this.div, am4charts.PieChart3D);

                chart.data = this.dataGraph;
                chart.exporting.menu = new am4core.ExportMenu();
                chart.numberFormatter.outputFormat = "#.";

                let pieSeries = chart.series.push(new am4charts.PieSeries3D());
                chart.innerRadius = am4core.percent(40);
                pieSeries.dataFields.value = "valor";
                pieSeries.dataFields.category = "opcion";
                pieSeries.labels.template.text = "[bold]{value.percent.formatNumber('#.')} %[/]";
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
                this.datos=[];
            },
        }
    }
</script>

<style scoped>

</style>
