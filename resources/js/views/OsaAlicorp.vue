<template>
    <div>
        <div class="animated fadeIn">
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" :height="20"></loading>
            <div class="row">
                <div class="col-sm-12 col-lg-12">

                    <div class="card">
                        <div class="card-header" >
                            <div class="row align-items-center mt-3">
                                <div class="col-lg-3 col-sm-3 col-md-3 mb-2 mb-xl-0 text-center">
                                    <button class="btn btn-link" type="button"  v-on:click="verForm = !verForm">
                                        <strong>Campaigne</strong> Osa Bodegas ver. {{version}}
                                    </button>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-3 mb-2 mb-xl-0 text-center">

                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-6 mb-2 mb-xl-0 text-center">
                                    <span class="badge badge-pill badge-success" v-if="selectedMonth != '0'">{{selectedMonth}}</span>
                                    <span class="badge badge-success" v-if="selectedWeek != '0'">{{selectedWeek}}</span>
                                    <span class="badge badge-success" v-if="selectedOffice != '0'">{{selectedOffice}}</span>
                                    <span class="badge badge-pill badge-success" v-if="selectedDex != '0'">{{selectedDex}}</span>
                                    <span class="badge badge-pill badge-success" v-if="selectedCategory != ''">{{selectedCategory.category}}</span>
                                    <span class="badge badge-pill badge-success" v-if="selectedProduct != ''">{{selectedProduct.product}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" v-if="verForm" id="collapseOne" data-parent="#accordion">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label"><strong>Mes</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedMonth" @change="loadWeeks">
                                        <option value="0" selected="selected">Todos</option>
                                        <option v-for="month in months"  :value='month'>{{ month }} </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label"><strong>Semana</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedWeek" @change="loadOfficeDex">
                                        <option value="0" selected="selected">Seleccionar</option>
                                        <option v-for="week in weeks"  :value="week">{{ week }} </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label"><strong>Oficina</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedOffice" @change="loadDexForOffice">
                                        <option value="0" selected="selected">Seleccionar</option>
                                        <option v-for="office in offices"  :value='office'>{{ office }} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label"><strong>Dex</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedDex" @change="loadOfficeForDex">
                                        <option value="0" selected="selected">Seleccionar</option>
                                        <option v-for="dex in dexs"  :value='dex'>{{ dex }} </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label"><strong>Categoria</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedCategory" @change="loadProductForCategory">
                                        <option value="0" selected="selected">Seleccionar</option>
                                        <option v-for="category in categories"  :value="category">{{ category.category }} </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label"><strong>Producto</strong></label>
                                <div class="col-md-2">
                                    <select class="form-control form-control-sm" v-model="selectedProduct" @change="loadPanels">
                                        <option value="0" selected="selected">Seleccionar</option>
                                        <option v-for="product in products"  :value='product'>{{ product.product }} </option>
                                    </select>
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

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >


                    <div class="accordion" id="accordionPolls">
                        <div class="card" >
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" href="#multiCollapseExample1" aria-expanded="true" aria-controls="multiCollapseExample1">
                                                Auditoría Osa
                                            </button>
                                        </h5>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                        <button class="btn btn-sm btn-outline-success" type="button">PDV Auditados: {{pointEffective}}</button>
                                        <loading :active.sync="isLoading5" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                    </div>
                                </div>
                            </div>

                            <div  class="multi-collapse collapse show" id="multiCollapseExample1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >

                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active show" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true" @click="loadResume">
                                                        <i class="icon-doc"></i> Resumen</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#dex3" role="tab" aria-controls="dex" aria-selected="false" >
                                                        <i class="icon-docs"></i> Dex</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">
                                                        <i class="icon-docs"></i> Detalle Categoria</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#messages3" role="tab" aria-controls="messages" aria-selected="false">
                                                        <i class="icon-pie-chart"></i> Ranking</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#clients" role="tab" aria-controls="client" aria-selected="false">
                                                        <i class="icon-people"></i> Clientes</a>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane active show" id="home3" role="tabpanel">
                                                    <div class="row">
                                                        <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <h5 class="text-center text-info"> Resumen</h5>
                                                            <BlockGraphGeneral :type="1"></BlockGraphGeneral>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <h5 class="text-center text-info"> OOS Detalle</h5>
                                                            <BlockGraphGeneral :type="2"></BlockGraphGeneral>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6" >
                                                            <h5 class="text-center text-info"> Detalle No Hay Stock</h5>
                                                            <BlockGraphGeneralV2 :type="2"  div="graphNoHayStock" :dataGraph=graphNoHayStock divLegend="" :series=seriesNohayStock :config=configGraphNoHayStock></BlockGraphGeneralV2>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6" >
                                                            <h5 class="text-center text-info"> ¿Hace Cuanto No hace pedido?</h5>
                                                            <BlockGraphGeneralV2 :type="1"  div="graphNoHacePedido" :dataGraph=graphNoHacePedido divLegend="" :series=[] :config=[]></BlockGraphGeneralV2>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <h5 class="text-center text-info"> ¿En cuánto tiempo se acabo?</h5>
                                                            <BlockGraphGeneralV2 :type="1"  div="graphTiempoAcabo" :dataGraph=graphTiempoAcabo divLegend="" :series=[] :config=[]></BlockGraphGeneralV2>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <loading :active.sync="isLoading7" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <h5 class="text-center text-info"> Evolutivo OSA</h5>
                                                            <BlockGraphGeneral :type="3"></BlockGraphGeneral>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane" id="dex3" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                                                            <loading :active.sync="isLoading11" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th>DEX</th>
                                                                    <th>OSA</th>
                                                                    <th>OOS</th>
                                                                    <!-- <th>VOID</th> -->
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr v-for="(dex,indexDex) in dexOsa">
                                                                    <td>{{dex.distributor}}</td>
                                                                    <td>{{dex.OSA}}({{dex.porcentOsa}}%)</td>
                                                                    <td>{{dex.OOS}}({{dex.porcentOos}}%)</td>
                                                                    <!-- <td>{{dex.VOID}}({{dex.porcentVoid}}%)</td> -->
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane" id="profile3" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <loading :active.sync="isLoading3" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th>Categoria</th>
                                                                    <th>Sku mandatorios</th>
                                                                    <th>OSA</th>
                                                                    <th>OOS</th>
                                                                    <!-- <th>VOID</th> -->
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr :class="(selectedCategory == 0 ? 'bg-info' : '')">
                                                                    <td @click="getCategoryAll">Todos</td>
                                                                    <td>Todos</td>
                                                                    <td>Todos</td>
                                                                    <td>Todos</td>
                                                                    <!-- <td>Todos</td> -->
                                                                </tr>
                                                                <tr v-for="(category,indexPoll) in categoriesOsa"  :class="(selectedCategory.category_product_id == category.category_product_id ? 'bg-info' : '')">
                                                                    <td @click="getProductsForCategory(category)">{{category.fullname_category}}</td>
                                                                    <td>{{category.total_products}}</td>
                                                                    <td>{{category.porcentOsa}}%</td>
                                                                    <td>{{category.porcentOos}}%</td>
                                                                    <!-- <td>{{category.porcentVoid}}%</td> -->
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <loading :active.sync="isLoading4" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th>{{OsaGeneral}}</th>
                                                                    <th>EAM</th>
                                                                    <th>OSA</th>
                                                                    <th>OOS</th>
                                                                    <!-- <th>VOID</th> -->
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr v-for="(products,indexProduct) in productsOsa">
                                                                    <td>{{products.fullname_product}}</td>
                                                                    <td>{{products.eam}}</td>
                                                                    <td>{{products.OSA}}({{products.porcentOsa}}%)</td>
                                                                    <td>{{products.OOS}}({{products.porcentOos}}%)</td>
                                                                    <!-- <td>{{products.VOID}}({{products.porcentVoid}}%)</td> -->
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane" id="messages3" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <loading :active.sync="isLoading6" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th>FFVV</th>
                                                                    <th>OSA</th>
                                                                    <th>OOS</th>
                                                                    <!-- <th>VOID</th> -->
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr v-for="(fv,indexFV) in ffvv" :class="(fv.group == selectedGroup ? 'bg-info' : '')">
                                                                    <td @click="getVendors(fv.group)">{{fv.group}}</td>
                                                                    <td>{{fv.OSA}}({{fv.porcentOsa}}%)</td>
                                                                    <td>{{fv.OOS}}({{fv.porcentOos}}%)</td>
                                                                    <!-- <td>{{fv.VOID}}({{fv.porcentVoid}}%)</td> -->
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" >
                                                            <loading :active.sync="isLoading8" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th>COD - VENDEDOR</th>
                                                                    <th>OSA</th>
                                                                    <th>OOS</th>
                                                                    <!-- <th>VOID</th> -->
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr v-for="(vendor,indexVendor) in vendorsOsa">
                                                                    <td>{{vendor.cod_vendor}}</td>
                                                                    <td>{{vendor.OSA}}({{vendor.porcentOsa}}%)</td>
                                                                    <td>{{vendor.OOS}}({{vendor.porcentOos}}%)</td>
                                                                    <!-- <td>{{vendor.VOID}}({{vendor.porcentVoid}}%)</td> -->
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="clients" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                                                            <loading :active.sync="isLoading9" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                                                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th>GRUPOS(FFVV)</th>
                                                                    <th>COD CLIENTE</th>
                                                                    <th>OSA</th>
                                                                    <th>OOS</th>
                                                                    <!-- <th>VOID</th> -->
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <tr v-for="(clients,indexClients) in clientsOsa">
                                                                    <td>{{clients.vendors}}</td>
                                                                    <td>{{clients.codclient}}</td>
                                                                    <td>{{clients.OSA}}({{clients.porcentOsa}}%)</td>
                                                                    <td>{{clients.OOS}}({{clients.porcentOos}}%)</td>
                                                                    <!-- <td>{{clients.VOID}}({{clients.porcentVoid}}%)</td> -->
                                                                </tr>

                                                                </tbody>
                                                            </table>
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
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import BlockGraphGeneral from "./components/BlockGraphGeneral";
    import BlockGraphGeneralV2 from "./components/BlockGraphGeneralV2";
    import EventBus from '../bus'

    export default {
        name: "OsaAlicorp",
        components: {
            Loading,
            BlockGraphGeneral,
            BlockGraphGeneralV2
        },
        data() {
            return {
                graphNoHayStock:[],
                configGraphNoHayStock:[],
                seriesNohayStock:[],
                graphNoHacePedido:[],
                graphTiempoAcabo:[],
                study_id:21,
                verForm: true,
                verFormBody:true,
                verFormBodyCategory:false,
                version: '2.0',
                selectedMonth:0,
                selectedWeek:0,
                selectedOffice:0,
                selectedDex:0,
                selectedCategory:0,
                selectedProduct:0,
                selectedGroup:0,
                dismissSecs: 2,
                dismissCountDown: 0,
                mensaje:'',
                months:[],
                weeks:[],
                offices:[],
                dexs:[],
                categories:[],
                products:[],
                dataInicial:[],
                filterMonths:[],
                floating:'',
                filterWeeks:[],
                filtersAll:[],
                filterOffice:[],
                filterDex:[],
                productsAll:[],
                filterCategory:[],
                filterProdut:[],
                pointEffective:0,
                dataGraph1:[],
                dataGraph2:[],
                dataGraph3:[],
                categoriesOsa:[],
                productsOsa:[],
                OsaGeneral:'Osa General',
                ffvv:[],
                vendorsOsa:[],
                clientsOsa:[],
                dexOsa:[],
                indexedDB : window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB,
                dataBase : null,

                isLoading: false,
                isLoading1: false,
                isLoading2: false,
                isLoading3: false,
                isLoading4: false,
                isLoading5: false,
                isLoading6: false,
                isLoading7: false,
                isLoading8: false,
                isLoading9: false,
                isLoading10: false,
                isLoading11: false,
                fullPage: false,
                resumenPanel: true,
                categoriaPanel: false,
                ranking1Panel: false,
                clientsPanel: false,
                dexPanel: false,
                changeEvolutivo:true
            }
        },
        methods:{
            sidebarToggle (e) {
                document.body.classList.toggle('sidebar-minimized');
            },
            countDownChanged (dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            loadWeeks(){

                if (this.selectedMonth == 0)
                {
                    this.selectedWeek = 0;
                    this.weeks = [];
                }else{
                    this.filterWeeks=[];
                    let weekStart='';let floatingMonth='';
                    for (var indice1 in this.filtersAll) {
                        if (this.filtersAll[indice1]['month_year'] == this.selectedMonth){
                            if (weekStart !== this.filtersAll[indice1]['week_mounth_year']){
                                weekStart = this.filtersAll[indice1]['week_mounth_year'];
                                this.floating = weekStart;
                                floatingMonth = this.filterWeeks.find(this.existValue);
                                if ((floatingMonth == '') || (floatingMonth == undefined))
                                {
                                    this.filterWeeks.push(this.filtersAll[indice1]['week_mounth_year']);
                                }

                            }
                        }
                    }
                    this.weeks = this.filterWeeks;this.filterWeeks=[];
                }

                //this.loadPointsEffectives();
                this.changeEvolutivo = false;
                this.loadPanels();
            },
            existValue(inconsis){
                return inconsis === this.floating;
            },
            loadMonth()
            {
                let monthStart='';let floatingMonth='';
                for (var indice1 in this.filtersAll) {
                    if (monthStart !== this.filtersAll[indice1]['month_year']){
                        monthStart = this.filtersAll[indice1]['month_year'];
                        this.floating = monthStart;
                        floatingMonth = this.filterMonths.find(this.existValue);
                        if ((floatingMonth == '') || (floatingMonth == undefined))
                        {
                            this.filterMonths.push(this.filtersAll[indice1]['month_year']);
                        }

                    }
                }

                this.months = this.filterMonths;
               
            },
            loadOffice()
            {
                let officeStart='';let floatingMonth='';
                for (var indice1 in this.filtersAll) {
                    if (officeStart !== this.filtersAll[indice1]['ubigeo']){
                        officeStart = this.filtersAll[indice1]['ubigeo'];
                        this.floating = officeStart;
                        floatingMonth = this.filterOffice.find(this.existValue);
                        if ((floatingMonth == '') || (floatingMonth == undefined))
                        {
                            this.filterOffice.push(this.filtersAll[indice1]['ubigeo']);
                        }

                    }
                }

                this.offices = this.filterOffice;
               
            },
            loadDex()
            {
                let dexStart='';let floatingMonth='';
                for (var indice1 in this.filtersAll) {
                    if (dexStart !== this.filtersAll[indice1]['distributor']){
                        dexStart = this.filtersAll[indice1]['distributor'];
                        this.floating = dexStart;
                        floatingMonth = this.filterDex.find(this.existValue);
                        if ((floatingMonth == '') || (floatingMonth == undefined))
                        {
                            this.filterDex.push(this.filtersAll[indice1]['distributor']);
                        }

                    }
                }

                this.dexs = this.filterDex;
            },
            loadCategoryProducts()
            {
                let categoryStart='';let floatingMonth='';
                for (var indice1 in this.productsAll) {
                    if (categoryStart !== this.productsAll[indice1]['category_product_id']){
                        categoryStart = this.productsAll[indice1]['category_product_id'];
                        this.floating = categoryStart;
                        floatingMonth = this.filterCategory.find(this.existValue);
                        if ((floatingMonth == '') || (floatingMonth == undefined))
                        {
                            this.filterCategory.push({category: this.productsAll[indice1]['category'], category_product_id:this.productsAll[indice1]['category_product_id']});
                        }

                    }
                }

                this.categories = this.filterCategory;this.filterCategory=[];


                let productsStart='';floatingMonth='';
                for (var indice1 in this.productsAll) {
                    if (productsStart !== this.productsAll[indice1]['product_id']){
                        productsStart = this.productsAll[indice1]['product_id'];
                        this.floating = productsStart;
                        floatingMonth = this.filterProdut.find(this.existValue);
                        if ((floatingMonth == '') || (floatingMonth == undefined))
                        {
                            this.filterProdut.push({product: this.productsAll[indice1]['product'], product_id:this.productsAll[indice1]['product_id']});
                        }

                    }
                }

                this.products = this.filterProdut;this.filterProdut=[];
            },
            loadOfficeDex()
            {
                let officeStart='';let floatingMonth='';this.filterOffice=[];
                for (var indice1 in this.filtersAll) {
                    if (this.filtersAll[indice1]['week_mounth_year'] == this.selectedWeek){
                        if (officeStart !== this.filtersAll[indice1]['ubigeo']){
                            officeStart = this.filtersAll[indice1]['ubigeo'];
                            this.floating = officeStart;
                            floatingMonth = this.filterOffice.find(this.existValue);
                            if ((floatingMonth == '') || (floatingMonth == undefined))
                            {
                                this.filterOffice.push(this.filtersAll[indice1]['ubigeo']);
                            }

                        }
                    }

                }

                this.offices = this.filterOffice;this.filterOffice=[];
               

                let dexStart='';floatingMonth='';this.filterDex=[];
                for (var indice1 in this.filtersAll) {
                    if (this.filtersAll[indice1]['week_mounth_year'] == this.selectedWeek){
                        if (dexStart !== this.filtersAll[indice1]['distributor']){
                            dexStart = this.filtersAll[indice1]['distributor'];
                            this.floating = dexStart;
                            floatingMonth = this.filterDex.find(this.existValue);
                            if ((floatingMonth == '') || (floatingMonth == undefined))
                            {
                                this.filterDex.push(this.filtersAll[indice1]['distributor']);
                            }

                        }
                    }

                }

                this.dexs = this.filterDex;this.filterDex=[];
               
                this.changeEvolutivo=false;
                this.loadPanels();
            },

            loadDexForOffice()
            {
                if (this.selectedOffice==0)
                {
                    this.loadOffice();
                    this.loadDex();
                    if ((this.selectedMonth==0) || (this.selectedWeek==0))
                    {
                        this.changeEvolutivo=false;
                    }else{
                        this.changeEvolutivo=true;
                    }

                    this.loadPanels();
                }else{
                    let floatingMonth='';this.filterOffice=[];
                    

                    let dexStart='';floatingMonth='';this.filterDex=[];
                    for (var indice1 in this.filtersAll) {
                        if (this.filtersAll[indice1]['ubigeo'] == this.selectedOffice){
                            if (dexStart !== this.filtersAll[indice1]['distributor']){
                                dexStart = this.filtersAll[indice1]['distributor'];
                                this.floating = dexStart;
                                floatingMonth = this.filterDex.find(this.existValue);
                                if ((floatingMonth == '') || (floatingMonth == undefined))
                                {
                                    this.filterDex.push(this.filtersAll[indice1]['distributor']);
                                }

                            }
                        }

                    }

                    this.dexs = this.filterDex;this.filterDex=[];
                  
                    this.changeEvolutivo=false;
                    this.loadPanels();
                }

            },
            loadOfficeForDex()
            {
                if (this.selectedDex==0)
                {
                    this.loadOffice();
                    this.loadDex();
                    if ((this.selectedMonth==0) || (this.selectedWeek==0))
                    {
                        this.changeEvolutivo=false;
                    }else{
                        this.changeEvolutivo=true;
                    }

                    this.loadPanels();
                }else{
                    this.filterOffice=[];
                   

                    let officeStart='';let floatingMonth='';this.filterOffice=[];
                    for (var indice1 in this.filtersAll) {
                        if (this.filtersAll[indice1]['distributor'] == this.selectedDex){
                            if (officeStart !== this.filtersAll[indice1]['ubigeo']){
                                officeStart = this.filtersAll[indice1]['ubigeo'];
                                this.floating = officeStart;
                                floatingMonth = this.filterOffice.find(this.existValue);
                                if ((floatingMonth == '') || (floatingMonth == undefined))
                                {
                                    this.filterOffice.push(this.filtersAll[indice1]['ubigeo']);
                                }

                            }
                        }

                    }

                    this.offices = this.filterOffice;this.filterOffice=[];
                    
                    this.changeEvolutivo=false;
                    this.loadPanels();
                }


            },

            loadProductForCategory()
            {
                let productsStart='';let floatingMonth='';
                for (var indice1 in this.productsAll) {
                    if (this.productsAll[indice1]['category_product_id'] == this.selectedCategory.category_product_id){
                        if (productsStart !== this.productsAll[indice1]['product_id']){
                            productsStart = this.productsAll[indice1]['product_id'];
                            this.floating = productsStart;
                            floatingMonth = this.filterProdut.find(this.existValue);
                            if ((floatingMonth == '') || (floatingMonth == undefined))
                            {
                                this.filterProdut.push({product: this.productsAll[indice1]['product'], product_id:this.productsAll[indice1]['product_id']});
                            }

                        }
                    }
                }

                this.products = this.filterProdut;this.filterProdut=[];
                this.loadPanels();
            },
            loadPointsEffectives()
            {
                let urlBase = '/api/getEffectivePointsOsa';
                this.isLoading5 = true;
                axios.post(urlBase, {
                    study_id: this.study_id,
                    user_id: 5,
                    month: this.selectedMonth,
                    week: this.selectedWeek,
                    office: this.selectedOffice,
                    dex: this.selectedDex
                })
                    .then((response2) => {
                        this.pointEffective = response2.data[0].stores;
                        this.isLoading5 = false;

                    })
                    .catch(function (error2) {
                        console.log(error2);
                        this.isLoading5 = false;
                    });
            },
            
            loadGraphs()
            {
                let urlBase = '/api/getCalculateGraphOsa';
                let category_id;
                if (this.selectedCategory==0)
                {
                    category_id = 0;
                }else{
                    category_id = this.selectedCategory.category_product_id;
                }
                let product_id;
                if (this.selectedProduct==0)
                {
                    product_id = 0;
                }else{
                    product_id = this.selectedProduct.product_id;
                }
                this.isLoading = true;
                this.isLoading2 = true;
                axios.post(urlBase, {
                    study_id: this.study_id,
                    user_id: 5,
                    month: this.selectedMonth,
                    week: this.selectedWeek,
                    office: this.selectedOffice,
                    dex: this.selectedDex,
                    category_id: category_id,
                    product_id: product_id
                })
                    .then((response2) => {
                        this.pointEffective = response2.data.stores;
                        this.dataGraph1 = response2.data.graph1;
                        this.dataGraph2 = response2.data.graph2;
                        EventBus.$emit('sendDataGraphGeneral', this.dataGraph1);
                        EventBus.$emit('sendDataGraphGeneral', this.dataGraph2);

                        this.configGraphNoHayStock.push({TitleY:'opciones',AngleX:0,Orientation:1,FontSizeValY:8});
                        this.graphNoHayStock = response2.data.graphNoHayStock.datos;
                        this.seriesNohayStock = [];

                        this.graphNoHacePedido = response2.data.graphNoHacePedido.datos;

                        this.graphTiempoAcabo = response2.data.graphTiempoAcabo.datos;

                        this.isLoading = false;
                        this.isLoading2 = false;
                        if (this.changeEvolutivo)
                        {
                            let urlBase2 = '/api/getEvolutivoOsa';
                            this.isLoading = true;
                            this.isLoading7 = true;
                            axios.post(urlBase2, {
                                study_id: this.study_id,
                                user_id: 5,
                                month: '0',
                                week: '0',
                                office: this.selectedOffice,
                                dex: this.selectedDex,
                                category_id: category_id,
                                product_id: product_id
                            })
                                .then((response3) => {
                                    
                                    this.dataGraph3 = response3.data.graph3;
                                    EventBus.$emit('sendDataGraphGeneral', this.dataGraph3);
                                    this.isLoading = false;
                                    this.isLoading7 = false;
                                })
                                .catch(function (error2) {
                                    console.log(error2);
                                });
                        }
                        this.changeEvolutivo = true;

                    })
                    .catch(function (error2) {
                        console.log(error2);
                    });

            },
            getDetailsCategory()
            {
                if (this.categories.length>0)
                {
                    //this.loadPointsEffectives();
                    let urlBase = '/api/getValuesOsaCategoriesProducts';
                    let category_id;
                    if (this.selectedCategory==0)
                    {
                        category_id = 0;
                    }else{
                        category_id = this.selectedCategory.category_product_id;
                    }
                    let product_id;
                    if (this.selectedProduct==0)
                    {
                        product_id = 0;
                    }else{
                        product_id = this.selectedProduct.product_id;
                    }
                    this.isLoading = true;
                    this.isLoading3 = true;
                    axios.post(urlBase, {
                        study_id: this.study_id,
                        user_id: 5,
                        month: this.selectedMonth,
                        week: this.selectedWeek,
                        office: this.selectedOffice,
                        dex: this.selectedDex,
                        category_id: category_id,
                        product_id: product_id
                    })
                        .then((response2) => {
                            this.categoriesOsa = response2.data;

                            this.isLoading3 = false;
                            this.getDetailsProduct();
                        })
                        .catch(function (error2) {
                            console.log(error2);
                        });

                }

            },
            getDetailsProduct()
            {
                this.categoriaPanel = true;
                if (this.categoriaPanel)
                {
                    this.resumenPanel = false;
                    this.categoriaPanel = false;
                    this.ranking1Panel = false;
                    this.clientsPanel = false;
                    if (this.categories.length>0)
                    {

                        let urlBase = '/api/getValuesOsaProductsCategories';
                        let category_id;
                        if (this.selectedCategory==0)
                        {
                            category_id = 0;
                        }else{
                            category_id = this.selectedCategory.category_product_id;
                        }
                        let product_id;
                        if (this.selectedProduct==0)
                        {
                            product_id = 0;
                        }else{
                            product_id = this.selectedProduct.product_id;
                        }
                        this.isLoading = true;
                        this.isLoading4 = true;
                        axios.post(urlBase, {
                            study_id: this.study_id,
                            user_id: 5,
                            month: this.selectedMonth,
                            week: this.selectedWeek,
                            office: this.selectedOffice,
                            dex: this.selectedDex,
                            category_id: category_id,
                            product_id: product_id
                        })
                            .then((response2) => {
                                this.productsOsa = response2.data;
                                this.isLoading = false;
                                this.isLoading4 = false;

                            })
                            .catch(function (error2) {
                                console.log(error2);
                            });

                    }
                }

            },
            getProductsForCategory(CategoryIdList)
            {
                //this.selectedCategory = CategoryIdList;
                this.filterCategory.push({category: CategoryIdList.fullname_category, category_product_id: CategoryIdList.category_product_id});

                //this.selectedCategory = Object.assign({}, this.filterCategory);
                this.selectedCategory = this.filterCategory[0];
                
                this.OsaGeneral = this.selectedCategory.category;
                this.loadProductForCategory();

                this.getDetailsProduct();
            },
            loadPanels()
            {
                this.loadGraphs();
                this.getDex();
                this.getDetailsCategory();
                this.getRanking1();
                this.getClients();
            },
            loadResume()
            {

                EventBus.$emit('sendDataGraphGeneral', this.dataGraph1);
                EventBus.$emit('sendDataGraphGeneral', this.dataGraph2);
                EventBus.$emit('sendDataGraphGeneral', this.dataGraph3);
            },
            getRanking1()
            {
                let urlBase = '/api/getValuesOsaRanking1';
                let category_id;
                if (this.selectedCategory==0)
                {
                    category_id = 0;
                }else{
                    category_id = this.selectedCategory.category_product_id;
                }
                let product_id;
                if (this.selectedProduct==0)
                {
                    product_id = 0;
                }else{
                    product_id = this.selectedProduct.product_id;
                }
                this.isLoading = true;
                this.isLoading6 = true;
                axios.post(urlBase, {
                    study_id: this.study_id,
                    user_id: 5,
                    month: this.selectedMonth,
                    week: this.selectedWeek,
                    office: this.selectedOffice,
                    dex: this.selectedDex,
                    category_id: category_id,
                    product_id: product_id
                })
                    .then((response2) => {
                        this.ffvv = response2.data;
                        this.isLoading = false;
                        this.isLoading6 = false;
                        if (this.selectedGroup != 0)
                        {
                            this.getVendors(this.selectedGroup)
                        }
                        //this.getDetailsProduct();
                    })
                    .catch(function (error2) {
                        console.log(error2);
                    });
            },
            getCategoryAll()
            {
                this.selectedCategory = 0;
                this.OsaGeneral = 'Osa General';
                this.loadProductForCategory();
                this.getDetailsCategory();
            },
            getVendors(codGroup)
            {
                this.selectedGroup = codGroup;
                this.ranking1Panel = true;
                if (this.ranking1Panel)
                {
                    this.resumenPanel = false;
                    this.categoriaPanel = false;
                    this.ranking1Panel = false;
                    this.clientsPanel = false;
                    let urlBase = '/api/getVendorsForGroup';
                    let category_id;
                    if (this.selectedCategory==0)
                    {
                        category_id = 0;
                    }else{
                        category_id = this.selectedCategory.category_product_id;
                    }
                    let product_id;
                    if (this.selectedProduct==0)
                    {
                        product_id = 0;
                    }else{
                        product_id = this.selectedProduct.product_id;
                    }
                    this.isLoading8 = true;
                    axios.post(urlBase, {
                        study_id: this.study_id,
                        user_id: 5,
                        month: this.selectedMonth,
                        week: this.selectedWeek,
                        office: this.selectedOffice,
                        dex: this.selectedDex,
                        category_id: category_id,
                        product_id: product_id,
                        group: codGroup,
                    })
                        .then((response2) => {
                            this.vendorsOsa = response2.data;
                            this.isLoading8 = false;

                        })
                        .catch(function (error2) {
                            console.log(error2);
                        });
                }
            },
            getDex()
            {
                let urlBase = '/api/getDexOsa';
                let category_id;
                if (this.selectedCategory==0)
                {
                    category_id = 0;
                }else{
                    category_id = this.selectedCategory.category_product_id;
                }
                let product_id;
                if (this.selectedProduct==0)
                {
                    product_id = 0;
                }else{
                    product_id = this.selectedProduct.product_id;
                }
                this.isLoading = true;
                this.isLoading11 = true;
                axios.post(urlBase, {
                    study_id: this.study_id,
                    user_id: 5,
                    month: this.selectedMonth,
                    week: this.selectedWeek,
                    office: 0,
                    dex: 0,
                    category_id: category_id,
                    product_id: product_id
                })
                    .then((response2) => {
                        this.dexOsa = response2.data;
                        this.isLoading = false;
                        this.isLoading11 = false;

                    })
                    .catch(function (error2) {
                        console.log(error2);
                    });
            },
            getClients()
            {
                let urlBase = '/api/getClientsOsa';
                let category_id;
                if (this.selectedCategory==0)
                {
                    category_id = 0;
                }else{
                    category_id = this.selectedCategory.category_product_id;
                }
                let product_id;
                if (this.selectedProduct==0)
                {
                    product_id = 0;
                }else{
                    product_id = this.selectedProduct.product_id;
                }
                this.isLoading = true;
                this.isLoading9 = true;
                axios.post(urlBase, {
                    study_id: this.study_id,
                    user_id: 5,
                    month: this.selectedMonth,
                    week: this.selectedWeek,
                    office: this.selectedOffice,
                    dex: this.selectedDex,
                    category_id: category_id,
                    product_id: product_id
                })
                    .then((response2) => {
                        this.clientsOsa = response2.data;
                        this.isLoading = false;
                        this.isLoading9 = false;

                    })
                    .catch(function (error2) {
                        console.log(error2);
                    });
            }
        },
        mounted() {
            this.sidebarToggle();
            this.isLoading = true;
            //let urlBase = '/api/getProcessingOsa';
            let urlBase = '/api/getFiltersOsa';
            axios.post(urlBase, {
                study_id: 21,
                user_id: 5
            })
                .then((response) => {
                    this.filtersAll = response.data;
                    urlBase = '/api/getFiltersProductsOsa';
                    axios.post(urlBase, {
                        study_id: 21
                    })
                        .then((response1) => {
                            this.productsAll = response1.data;
                            this.loadCategoryProducts();
                            this.isLoading = false;
                            this.loadMonth();
                            this.loadOffice();
                            this.loadDex();
                            this.loadPanels();

                        })
                        .catch(function (error1) {
                            console.log(error1);
                        });


                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }
</script>

<style scoped>

</style>
