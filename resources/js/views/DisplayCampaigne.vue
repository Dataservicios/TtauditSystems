<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 pt-4">
                    <div class="card border-warning">
                        <div class="card-header">Campañas Vigentes</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-body p-0 d-flex align-items-center">
                                            <i class="fa fa-laptop bg-warning p-4 font-2xl mr-3"></i>
                                            <div>
                                                <div class="text-value-sm text-primary">Mistery</div>
                                                <div class="text-muted text-uppercase font-weight-bold small">
                                                    <a href="/panel">Ingresar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-body p-0 d-flex align-items-center">
                                            <i class="fa fa-cogs bg-primary p-4 font-2xl mr-3"></i>
                                            <div>
                                                <div class="text-value-sm text-primary">Lucky</div>
                                                <div class="text-muted text-uppercase font-weight-bold small">
                                                    <a href="/panelLucky">Ingresar</a>
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
        mounted()
        {
            this.sidebarToggle();
        },
        methods:{
            sidebarToggle (e) {
                document.body.classList.toggle('sidebar-minimized');
            },
        }
    }
</script>

<style scoped>

</style>