<template>
    <div>
        <div v-if="type==1" ref="chartDonout" style="height: 300px">
        </div>
        <div v-if="type==2" ref="chartBar" style="height: 300px">
        </div>
        <div v-if="type==3" ref="chartdiv" style="height: 350px">
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
        name: "BlockGraphGeneral",
        props:['type'],
        data() {
            return {
                datos:[]
            }
        },
        components: {
            Loading
        },
        mounted() {
            EventBus.$on('sendDataGraphGeneral', (item) => {
                this.datosGraph = item;
                if (this.datosGraph.type == this.type)
                {
                    this.datos = this.datosGraph.datos;
                    if (this.type ==1)
                    {
                        this.createdDonout();
                    }
                    if (this.type ==2)
                    {
                        this.createdBar();
                    }
                    if (this.type ==3)
                    {
                        this.createdLineal();
                    }
                }
            });

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
                //valueAxis.title.text = "Fecha";
                valueAxis.renderer.minLabelPosition = 0.01;

                // Create series
                let series1 = chart.series.push(new am4charts.LineSeries());
                series1.dataFields.valueY = "porcentOsa";
                series1.dataFields.categoryX = "date";
                series1.name = "OSA";
                series1.strokeWidth = 3;
                series1.bullets.push(new am4charts.CircleBullet());
                series1.tooltipText = "{name} in {categoryX}: {OSA}";
                series1.legendSettings.valueText = "{valueY}";
                series1.visible  = false;
                var valueLabel = series1.bullets.push(new am4charts.LabelBullet());
                valueLabel.label.text = "[bold]{porcentOsa} %[/]";
                valueLabel.label.dy = -10;

                let series2 = chart.series.push(new am4charts.LineSeries());
                series2.dataFields.valueY = "porcentOos";
                series2.dataFields.categoryX = "date";
                series2.name = 'OOS';
                series2.strokeWidth = 3;
                series2.bullets.push(new am4charts.CircleBullet());
                series2.tooltipText = "{name} in {categoryX}: {OOS}";
                series2.legendSettings.valueText = "{valueY}";
                var valueLabel1 = series2.bullets.push(new am4charts.LabelBullet());
                valueLabel1.label.text = "[bold]{porcentOos} %[/]";
                valueLabel1.label.dy = -10;

                // let series3 = chart.series.push(new am4charts.LineSeries());
                // series3.dataFields.valueY = "porcentVoid";
                // series3.dataFields.categoryX = "date";
                // series3.name = 'VOID';
                // series3.strokeWidth = 3;
                // series3.bullets.push(new am4charts.CircleBullet());
                // series3.tooltipText = "{name} in {categoryX}: {valueY}";
                // series3.legendSettings.valueText = "{valueY}";
                // var valueLabel2 = series3.bullets.push(new am4charts.LabelBullet());
                // valueLabel2.label.text = "[bold]{porcentVoid} %[/]";
                // valueLabel2.label.dy = -10;

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
            createdBar(){

                // Use themes
                am4core.useTheme(am4themes_animated);

                // Create chart instance
                var chart = am4core.create(this.$refs.chartBar, am4charts.XYChart3D);

                chart.data =this.datos;
                chart.exporting.menu = new am4core.ExportMenu();
                var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                categoryAxis.dataFields.category = "opcion";
                categoryAxis.renderer.grid.template.location = 0;
                categoryAxis.renderer.minGridDistance = 30;

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.title.text = "Auditorias";


                /* Create series */
                var series1 = chart.series.push(new am4charts.ColumnSeries3D());
                series1.columns.template.width = am4core.percent(80);
                series1.columns.template.tooltipText = "{categoryX}: [bold]{valueY} - {porcent.formatNumber('#.')} % [/]";
                //series1.name = this.datos[indice]['opcion'];
                series1.dataFields.categoryX = "opcion";
                series1.dataFields.valueY = "valor";
                //series1.calculateTotals = true;
                series1.stacked = true;
                var labelBullet = series1.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "[bold]{porcent} %[/]";
                labelBullet.label.dy = -5;
                labelBullet.label.hideOversized = false;
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
            }
        }
    }
</script>

<style scoped>

</style>
