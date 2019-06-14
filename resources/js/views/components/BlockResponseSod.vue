<template>
    <div>
        <div class="row">
            <div class="col-lg-6">
                <strong>¿Foto mal tomada?</strong>
            </div>
            <div class="col-lg-6">
                <label class="switch switch-pill switch-label switch-danger">
                    <input class="switch-input" type="checkbox" v-if="buttonPhoto" checked @click="insertFotoMalTomada()">
                    <input class="switch-input" type="checkbox" v-else @click="insertFotoMalTomada()">
                    <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                </label>
                <loading :active.sync="isLoading3" :can-cancel="false" :is-full-page="false" :height="20"></loading>
            </div>
        </div>
        <div class="row" v-for="(poll,indexPoll) in dataPolls">
            <div class="col-lg-4">
                <strong>{{poll.poll.question}}</strong>
            </div>
            <div class="col-lg-2">
                <div v-for="(response,index) in poll.responses[0].poll_details">
                    <label class="switch switch-label switch-pill switch-success" v-if="poll.poll.sino==1" >
                        <input class="switch-input" type="checkbox" checked v-if="response.result==1" @click="changeResponseSod(index,response,0)">
                        <input class="switch-input" type="checkbox" v-else @click="changeResponseSod(index,response,1)">
                        <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                    </label>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="row" v-if="poll.poll.options==1" v-for="(response,index) in poll.responses[0].optionsResults" >
                    <div class="col-lg-6 col-sm-12">
                        {{response.obj_option.options}}
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <label class="switch switch-label switch-pill switch-success" v-if="response.obj_responses.length>0" >
                            <input class="switch-input" type="checkbox" checked  @click="changeResponseSodOptions(poll.responses[0].poll_details[0],response.obj_option,response.obj_responses[0],0,indexPoll)">
                            <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                        </label>
                        <label class="switch switch-label switch-pill switch-success" v-if="response.obj_responses.length==0" >
                            <input class="switch-input" type="checkbox" @click="changeResponseSodOptions(poll.responses[0].poll_details[0],response.obj_option,[],1,indexPoll)">
                            <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <loading :active.sync="isLoading2" :can-cancel="false" :is-full-page="false" :height="20"></loading>
    </div>
</template>

<script>
    import EventBus from '../../bus';
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "BlockResponseSod",
        components: {Loading},
        props:['publicity_details_id','buttonPhoto'],
        data(){
            return{
                isLoading2: false,
                isLoading3: false,
                dataLoad:[],
                dataPolls:[],
                dataPollsOrigin:[],
                polls:[],
                pollResponses:[],
                idEdit:0,
                user:[],
                check:false
            }
        },
        mounted(){
            EventBus.$on('loadResponseSod', (item) => {
                this.user = JSON.parse(sessionStorage.getItem('user'));
                this.dataPolls=[];
                this.dataLoad=[];
                this.dataLoad=item;
                //console.log(this.dataLoad,this.dataLoad[0].store_id);
                this.getResponsesPoll();
            });
        },
        methods:{
            insertFotoMalTomada(){
                this.isLoading3 = true;
                let urlBase = '/api/insertPhotoError';
                console.log(this.dataLoad[0].company_id,this.publicity_details_id,this.user.id);
                
                axios.post(urlBase, {
                    company_id: this.dataLoad[0].company_id,
                    publicityDetail_id: this.publicity_details_id,
                    user_id : this.user.id
                })
                    .then((response) => {
                        this.isLoading3 = false;console.log('grabado photo',response);
                        this.check = true;
                        //this.mensaje="dato guardado ...";
                        //this.showAlert();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            changeResponseSod(index,objResponse,change){
                this.idEdit = objResponse;
                let urlBase = '/api/changeResponseSiNo';
                axios.post(urlBase, {
                    poll_details_id: this.idEdit.id,
                    result: change,
                    user_id : this.user.id
                })
                    .then((response) => {
                        this.getResponsesPoll();

                    })
                    .catch(function (error) {
                        //this.isLoading2 = false;
                        console.log(error);
                    });
            },
            changeResponseSodOptions(objPollDetails,objOption,objPollOptionDetails,Action,indexPoll)
            {
                this.isLoading2 = true;
                this.idEdit = objPollOptionDetails;
                //console.log('Select: ',this.idEdit,objPollDetails,objOption,Action);
                //console.log('Poll: ',this.dataPolls[indexPoll].poll.option_type);
                //console.log('Poll_options: ',this.dataPolls[indexPoll].responses[0].optionsResults);
                // Action : 1 ->insert 0->delete
                if (Action==1)
                {
                    let optionType = this.dataPolls[indexPoll].poll.option_type;
                    /*let storeId = objPollDetails.store_id;
                    let companyId = objPollDetails.company_id;
                    let pollOptionId = objOption.id;
                    let auditor = objPollDetails.auditor;
                    let publicity_id = objPollDetails.publicity_id;
                    let product_id = objPollDetails.product_id;*/
                    let urlBase = '/api/updatedPollOptionDetail';
                    let optionsResponseOrigin = this.dataPolls[indexPoll].responses[0].optionsResults;
                    let optionsResponses =[];
                    for (var indice1 in optionsResponseOrigin) {
                        if (optionsResponseOrigin[indice1].obj_responses.length>0)
                        {
                            optionsResponses = optionsResponses + optionsResponseOrigin[indice1].obj_responses[0].poll_option_id + "|" + optionsResponseOrigin[indice1].obj_responses[0].id + ",";
                            //optionsResponses = [optionsResponseOrigin[indice1].obj_responses[0].poll_option_id,optionsResponseOrigin[indice1].obj_responses[0].id];
                            //optionsResponses.push({poll_option_id:optionsResponseOrigin[indice1].obj_responses[0].poll_option_id,poll_option_detail_id:optionsResponseOrigin[indice1].obj_responses[0].id});
                        }else{
                            optionsResponses = optionsResponses + optionsResponseOrigin[indice1].obj_option.id + "|" + 0 + ",";
                            //optionsResponses = [optionsResponseOrigin[indice1].obj_option.id,0];
                            //optionsResponses.push({poll_option_id:optionsResponseOrigin[indice1].obj_option.id,poll_option_detail_id:0});
                        }

                    }
                    //console.log('poll_option_type: ',this.dataPolls[indexPoll].poll.option_type,'responseOptions: ',optionsResponses,'optionIdSelected: ',objOption.id,'pollId: ',objOption.poll_id);
                    axios.post(urlBase, {
                        pollOptionType: this.dataPolls[indexPoll].poll.option_type,
                        responseOptions: optionsResponses,
                        optionIdSelected: objOption.id,
                        pollId: objOption.poll_id
                    })
                        .then((response) => {
                            this.isLoading2 = false;
                            //this.inicio=false;
                            this.getResponsesPoll();
                        })
                        .catch(function (error) {
                            //this.isLoading2 = false;
                            console.log(error);
                        });
                }
                if (Action==0)
                {
                    /*this.isLoading2 = true;
                    let urlBase = '/api/deletePollOptionDetail';
                    axios.post(urlBase, {
                        poll_option_details_id: this.idEdit.id
                    })
                        .then((response) => {
                            this.isLoading2 = false;
                            this.inicio=false;
                            this.getResponsesPoll();

                        })
                        .catch(function (error) {
                            this.isLoading2 = false;
                            console.log(error);
                        });*/
                }
            },
            getResponsesPoll(){
                this.isLoading2 = true;
                let urlBase = '/api/getQuestionForSod';
                axios.post(urlBase, {
                    company_id: this.dataLoad[0].company_id,
                    store_id: this.dataLoad[0].store_id,
                    publicity_id: this.dataLoad[0].publicity_id
                })
                .then((response) => {
                    this.isLoading2 = false;
                    this.dataPolls = [];
                    this.dataPolls = response.data;

                    //console.log('objetoActual',this.dataPolls);

                })
                .catch(function (error) {
                    this.isLoading2 = false;
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>

</style>