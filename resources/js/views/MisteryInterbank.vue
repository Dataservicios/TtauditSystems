<template>
<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center mt-3">
                                <div class="col-lg-5 col-sm-5 col-md-3 mb-3 mb-xl-3">
                                    
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-2 mb-2 mb-xl-2 text-right">
                                    <span class="badge badge-pill badge-success" v-if="selectedCompany != ''">{{selectedCompany.fullname}}</span>
                                    <span class="badge badge-success" v-if="selectedUbigeo != '0'">{{selectedUbigeo}}</span>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-1 mb-1 mb-xl-1 text-right">
                                    
                                </div>
                            </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"><strong>Ciudad</strong></label>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm" v-model="selectedUbigeo" @change="gatDataValidation">
                                    <option value="" >TODOS</option>
                                    <option v-for="ubigeo in ubigeos"  :value="ubigeo.office">{{ ubigeo.office }} </option>
                                </select>
                                <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
                            </div>
                            <label class="col-md-2 col-form-label"><strong>Campaña</strong></label>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm" v-model="selectedCompany" @change="loadCombos">
                                    <option value="" disabled>Seleccionar</option>
                                    <option v-for="company in companies"  :value='company'>{{ company.fullname }} </option>
                                </select>
                                <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="false" :height="20"></loading>
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

</script>