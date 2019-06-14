<template>
    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="row">
            <div class="col-sm-12 col-md-6">

            </div>
            <div class="col-sm-12 col-md-6 text-right">
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
        <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
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
                                colspan="1">
                                Id
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1">
                                Fecha
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1">Nombres
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" >Mercado
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" >Modulo
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" >Oficina
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" >Foto
                            </th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" >Comentario
                            </th>
                            <th>Audio</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr role="row" class="odd" v-for="(storeM,index) in storesMedias" v-if="viewStores">
                            <td class="sorting_1">{{index + 1}}</td>
                            <td>{{storeM.id}}</td>
                            <td>{{storeM.created_at}}</td>
                            <td>{{storeM.fullname}}</td>
                            <td>{{storeM.region}}</td>
                            <td>{{storeM.district}}</td>
                            <td>{{storeM.ubigeo}}</td>
                            <td v-if="storeM.archivo !=''">
                                <img :src="'http://ttaudit.com/media/fotos/'+storeM.archivo"  class="img-fluid img-thumbnail" data-toggle="modal" data-target="#modalImage" @click="loadImageModal(storeM.archivo)" style="width: 80px">
                            </td>
                            <td v-else></td>
                            <td>{{storeM.comentario}}</td>
                            <td v-if="storeM.audio !=''">
                                <audio controls="controls" >
                                    <source :src="'http://ttaudit.com/media/audios/'+storeM.audio"  type="audio/mpeg" />
                                    Your browser does not support the audio element.
                                </audio>
                            </td>
                            <td v-else></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-sm-12 col-md-5"><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 10 of 32 entries</div>
            </div>
            <div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                <ul class="pagination"><li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="3" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="4" tabindex="0" class="page-link">4</a></li><li class="paginate_button page-item next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="5" tabindex="0" class="page-link">Next</a></li>
                </ul>
            </div>
            </div>
        </div>-->
    </div>
</template>

<script>
    import EventBus from '../../bus';
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import JsonExcel from 'vue-json-excel';

    export default {
        name: "BlockDetailsOption",
        props:['company_id'],
        components:{
            Loading,
            JsonExcel
        },
        data(){
            return{
                opcion:0,
                poll_id:0,
                storesMedias:[],
                viewStores:false,
                isLoading2: false,
                json_data:[],
                json_fields:new Object(),
                json_meta:[],
                nombExcel:''
            }
        },
        mounted(){
            EventBus.$on('goStores', (item) => {
                console.log(item)
                this.opcion=item;
                this.poll_id = this.opcion.poll_id;
                this.storesMedias = [];
                this.viewStores = false;
                this.getStores();
            });
        },
        methods:{
            loadImageModal(Image) {
                let ImageLink= 'http://ttaudit.com/media/fotos/' + Image;

                EventBus.$emit('loadImageModal', ImageLink);
            },
            getStores() {
                this.isLoading2 = true;
                var type = this.opcion.type;
                var stores = this.opcion.stores;
                var poll_id_n = this.poll_id;
                var company_id_n = this.company_id;
                var storeArray = '';var valorN='';var valorOt=[];
                stores.forEach( function(valor, indice) {
                    if (type=="sino"){
                        valorN = valor;
                    }else{
                        valorN = valor.store_id;valorOt[valor.store_id] = valor.otro;
                    }
                    if (indice+1<stores.length)
                    {
                        storeArray = storeArray + valorN + "|";
                    }else{
                        storeArray = storeArray + valorN;
                    }
                    //console.log("En el índice " + indice + " hay este valor: " + valorN + " poll_id: " + poll_id_n + " company_id: " + company_id_n + " acumula: " + storeArray );
                });
                let urlCombo = '/api/getStoreMedia/'+ storeArray + '/' + company_id_n + '/' + poll_id_n;
                //console.log(urlCombo);
                axios.get(urlCombo)
                    .then((response) => {
                        var valorOtros='';
                        this.isLoading2 = false;
                        var archivo_foto ='';
                        var archivo_audio ='';
                        this.json_data = [];
                        this.json_fields = new Object();
                        console.log('getStoreMedia: ',response.data);

                        for (var indice in response.data) {
                            if (type=="sino"){
                                valorOtros = response.data[indice]['comentario'];
                            }else{
                                valorOtros = valorOt[response.data[indice]['id']];
                            }

                            archivo_foto = "<a href='http://ttaudit.com/media/fotos/'>" + response.data[indice]['archivo'] + "</a>";
                            archivo_audio = "<a href='http://ttaudit.com/media/audios/'>" + response.data[indice]['audio_web']+"</a>";

                            this.storesMedias.push({id: response.data[indice]['id'],fullname: response.data[indice]['fullname'], address: response.data[indice]['address'], archivo: response.data[indice]['archivo'], region: response.data[indice]['region'],district: response.data[indice]['district'], ubigeo: response.data[indice]['ubigeo'], comentario: valorOtros, created_at: response.data[indice]['created_at'], audio: response.data[indice]['audio_web']});
                            this.json_data.push({id: response.data[indice]['id'],fullname: response.data[indice]['fullname'], address: response.data[indice]['address'], archivo: archivo_foto, region: response.data[indice]['region'],district: response.data[indice]['district'], ubigeo: response.data[indice]['ubigeo'], comentario: valorOtros, created_at: response.data[indice]['created_at'], audio: archivo_audio});
                        }
                        //this.json_data = this.storesMedias;
                        //this.json_fields = new Object();
                        this.nombExcel = this.company_id + '_' + this.poll_id + '_' + this.opcion.type + '.xls';

                        this.json_fields.Id = "id";
                        this.json_fields.Nombres = "fullname";
                        this.json_fields.Direccion = "address";
                        this.json_fields.Mercado = "region";
                        this.json_fields.Modulo = "district";
                        this.json_fields.Oficina = "ubigeo";
                        this.json_fields.Foto = "archivo";
                        this.json_fields.Comentario = "comentario";
                        this.json_fields.Audio = "audio";
                        //this.json_fields.push('Id':'id','Nombres':'fullname','Mercado':'region','Modulo':'district','Oficina':'ubigeo','foto':'archivo','Comentario':'comentario','Audio':'audio');
                        this.json_meta = [
                            {
                                'key': 'charset',
                                'value': 'utf-8'
                            }
                        ];
                        //this.storesMedias = response.data;
                        this.viewStores = true;
                        //console.log('fields:',this.json_fields,' data: ',this.json_data,' meta: ',this.json_meta);
                    })
                    .catch((response) => {
                        this.isLoading2 = false;
                        console.log('Error', error);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
