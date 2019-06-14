<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 pt-4">
                    <div class="card border-warning">
                        <div class="card-header">Campañas Vigentes</div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-lg-4 col-sm-12" v-for="(links, index) of LinksModules"  :value='links' v-if="links.icon !='icon-speedometer'">
                                    <div class="card">
                                        <div class="card-body p-0 d-flex align-items-center">
                                            <i :class=icons[index]></i>
                                            <div>
                                                <div class="text-value-sm text-primary">{{ links.fullname }}</div>
                                                <div class="text-muted text-uppercase font-weight-bold small">
                                                    <router-link :to="links.url" class="nav-link">
                                                        Ingresar
                                                    </router-link>
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
    export default {
        name: "DisplayCampaigne",
        props: ["identify"],
        data() {
            return {
                LinksModules: [],
                RolesUser:[],
                icons:[],
            }
        },
        mounted()
        {
            //this.identify=this.$route.params.identify;
            this.RolesUser = JSON.parse(sessionStorage.getItem('user_roles'));
            this.sidebarToggle();
            this.icons = ['fa fa-laptop bg-info p-4 font-2xl mr-3','fa fa-line-chart bg-info p-4 font-2xl mr-3','fa fa-gears bg-info p-4 font-2xl mr-3','fa fa-line-chart bg-info p-4 font-2xl mr-3','fa fa-cube bg-info p-4 font-2xl mr-3','fa fa-barcode bg-info p-4 font-2xl mr-3','fa fa-laptop bg-info p-4 font-2xl mr-3','fa fa-line-chart bg-info p-4 font-2xl mr-3','fa fa-gears bg-info p-4 font-2xl mr-3','fa fa-line-chart bg-info p-4 font-2xl mr-3','fa fa-cube bg-info p-4 font-2xl mr-3','fa fa-barcode bg-info p-4 font-2xl mr-3'];
            this.getModules();
        },
        watch: {
            identify: function (newQuestion) {
                this.LinksModules = [];
                this.getModules();
            }
        },
        methods:{
            sidebarToggle (e) {
                document.body.classList.toggle('sidebar-minimized');
            },
            getModules(){
                
                let _Modules = JSON.parse(sessionStorage.getItem('user_modules'));
                for (var indice1 in _Modules) {
                    if ((_Modules[indice1]['identify'] == this.identify) && (_Modules[indice1]['url'].indexOf('/home/')==-1)){
                        this.LinksModules.push({id: _Modules[indice1]['id'],fullname:_Modules[indice1]['fullname'],url: _Modules[indice1]['url']});
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
