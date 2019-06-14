<template>
    <div>
        <div  ref="chart" style="height: 400px">
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
        name: "BlockGraphGeneralV1",
        props:['type','div'],
        data() {
            return {
                datos:[],
                divLegend:''
            }
        },
        components: {
            Loading
        },
        mounted() {
            EventBus.$on('sendDataGraphGeneral', (item) => {
                this.datosGraph = item[0];
                if (item[1] == this.div)
                {
                    this.datos=[];
                    this.datos = this.datosGraph.datos;
                    if (this.type ==1)
                    {
                        this.createdDonout();
                    }
                    if (this.type ==2)
                    {
                        this.createdBar(item[2],item[3],item[4],item[5]);
                    }
                    if (this.type ==3)
                    {
                        this.createdLineal();
                    }

                }
            });

            if (this.type ==4)
            {
                EventBus.$on('sendDataGraphGeneralStacked', (item) => {
                    this.datosGraph = item[0];
                    this.divLegend = item[6];
                    //console.log('datosStacked: ',this.datosGraph,'item: ',item,this.divLegend);
                    this.datos = this.datosGraph.datos;
                    this.createdStackedColumn();
                });
                //console.log(this.datosGraph.series[0][0]);
            }
        },
        methods:{
            createdLineal(){
                // Create chart instance
                let chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);
                chart.paddingRight = 20;

                // Add data
                chart.data = this.datos;
                /*chart.data = [{
                    "date": new Date(2018, 3, 20),
                    "value": 90,
                    "value2": 45
                }, {
                    "date": new Date(2018, 3, 21),
                    "value": 102,
                    "value2": 90
                }, {
                    "date": new Date(2018, 3, 22)
                }, {
                    "date": new Date(2018, 3, 23),
                    "value": 125
                }, {
                    "date": new Date(2018, 3, 24),
                    "value": 55,
                    "value2": 90
                }, {
                    "date": new Date(2018, 3, 25),
                    "value": 81,
                    "value2": 60
                }, {
                    "date": new Date(2018, 3, 26)
                }, {
                    "date": new Date(2018, 3, 27),
                    "value": 63,
                    "value2": 87
                }, {
                    "date": new Date(2018, 3, 28),
                    "value": 113,
                    "value2": 62
                }];*/

                // Create category axis
                let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "date";
                //categoryAxis.renderer.opposite = true;

                // Create value axis
                let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                //valueAxis.renderer.inversed = true;
                valueAxis.title.text = "Fecha";
                valueAxis.renderer.minLabelPosition = 0.01;

                // Create series
                let series1 = chart.series.push(new am4charts.LineSeries());
                series1.dataFields.valueY = "OSA";
                series1.dataFields.categoryX = "date";
                series1.name = "OSA";
                series1.strokeWidth = 3;
                series1.bullets.push(new am4charts.CircleBullet());
                series1.tooltipText = "{name} in {categoryX}: {valueY}";
                series1.legendSettings.valueText = "{valueY}";
                series1.visible  = false;
                var valueLabel = series1.bullets.push(new am4charts.LabelBullet());
                valueLabel.label.text = "[bold]{porcentOsa} %[/]";
                valueLabel.label.dy = -10;

                let series2 = chart.series.push(new am4charts.LineSeries());
                series2.dataFields.valueY = "OOS";
                series2.dataFields.categoryX = "date";
                series2.name = 'OOS';
                series2.strokeWidth = 3;
                series2.bullets.push(new am4charts.CircleBullet());
                series2.tooltipText = "{name} in {categoryX}: {valueY}";
                series2.legendSettings.valueText = "{valueY}";
                var valueLabel1 = series2.bullets.push(new am4charts.LabelBullet());
                valueLabel1.label.text = "[bold]{porcentOos} %[/]";
                valueLabel1.label.dy = -10;

                let series3 = chart.series.push(new am4charts.LineSeries());
                series3.dataFields.valueY = "VOID";
                series3.dataFields.categoryX = "date";
                series3.name = 'VOID';
                series3.strokeWidth = 3;
                series3.bullets.push(new am4charts.CircleBullet());
                series3.tooltipText = "{name} in {categoryX}: {valueY}";
                series3.legendSettings.valueText = "{valueY}";
                var valueLabel2 = series3.bullets.push(new am4charts.LabelBullet());
                valueLabel2.label.text = "[bold]{porcentVoid} %[/]";
                valueLabel2.label.dy = -10;

                // Add chart cursor
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.behavior = "zoomY";

                // Add legend
                chart.legend = new am4charts.Legend();
            },
            createdDonout(){

                let chart = am4core.create(this.$refs.chartDonout, am4charts.PieChart3D);

                chart.data = this.datos;
                chart.exporting.menu = new am4core.ExportMenu();
                chart.numberFormatter.outputFormat = "#.";

                let pieSeries = chart.series.push(new am4charts.PieSeries3D());
                chart.innerRadius = am4core.percent(40);
                pieSeries.dataFields.value = "valor";
                pieSeries.dataFields.category = "opcion";
                pieSeries.labels.template.text = "[bold]{value.percent.formatNumber('#.')} %[/]";
                this.datos=[];
            },
            createdBar(TitleY,AngleX,Orientation,FontSizeValY){

                // Orientation->0 Horizontal, Orientation->1 Vertical
                // Use themes
                am4core.useTheme(am4themes_animated);

                // Create chart instance
                var chart = am4core.create(this.$refs.chart, am4charts.XYChart3D);

                chart.data =this.datos;
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

            createdStackedColumn()
            {
                // Create chart instance
                var chart = am4core.create(this.$refs.chart, am4charts.XYChart3D);

                // Add data
                chart.data =this.datos;
                /*chart.data = [ {
                    "bloque": "Pruebas",
                    "europe": 2.8,
                    "namerica": 2.9,
                    "asia": 2.4,
                    "lamerica": 0.3,
                    "meast": 0.3,
                    "africa": 0.1
                }];*/

                // Create axes
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "bloque";
                categoryAxis.renderer.grid.template.location = 0;
                //categoryAxis.width=100;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.renderer.inside = true;
                valueAxis.renderer.labels.template.disabled = true;
                valueAxis.min = 0;

                // Create series
                function createSeries(field, name) {
                    // Set up series
                    var series = chart.series.push(new am4charts.ColumnSeries3D());
                    series.name = name;
                    series.dataFields.valueY = field;
                    series.dataFields.categoryX = "bloque";
                    series.sequencedInterpolation = true;

                    // Make it stacked
                    series.stacked = true;

                    // Configure columns
                    series.columns.template.width = am4core.percent(60);
                    series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: " + field + " ({valueY} %)";

                    // Add label
                    var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                    labelBullet.label.text = field+" ({valueY} %)";
                    labelBullet.locationY = 0.5;

                    return series;
                }

                for (var indice in this.datosGraph.series) {
                    //console.log(indice,' - ',this.datosGraph.series[indice][0],this.datosGraph.series[indice][1]);
                    createSeries(this.datosGraph.series[indice][0], this.datosGraph.series[indice][1],indice,this.datosGraph.series);
                }

                /*createSeries("europe", "Europe");
                createSeries("namerica", "North America");
                createSeries("asia", "Asia-Pacific");
                createSeries("lamerica", "Latin America");
                createSeries("meast", "Middle-East");
                createSeries("africa", "Africa");*/
                // Legend
                chart.legend = new am4charts.Legend;
                chart.legend.itemContainers.template.tooltipText = "[bold]{name}:[/]";

                var legendContainer = am4core.create(this.divLegend, am4core.Container);
                legendContainer.width = am4core.percent(100);
                legendContainer.height = am4core.percent(100);
                chart.legend.parent = legendContainer;
            }
        }
    }
</script>

<style scoped>

</style>
