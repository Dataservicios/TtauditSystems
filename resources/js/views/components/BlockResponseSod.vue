<template>
    <div>
        <div class="row" v-for="poll in dataPolls">
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
                            <input class="switch-input" type="checkbox" checked  @click="changeResponseSodOptions(poll.responses[0].poll_details[0],response.obj_option,response.obj_responses[0],0)">
                            <span class="switch-slider" data-checked="Sí" data-unchecked="No"></span>
                        </label>
                        <label class="switch switch-label switch-pill switch-success" v-if="response.obj_responses.length==0" >
                            <input class="switch-input" type="checkbox" @click="changeResponseSodOptions(poll.responses[0].poll_details[0],response.obj_option,[],1)">
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
        data(){
            return{
                isLoading2: false,
                dataLoad:[],
                dataPolls:[],
                polls:[],
                pollResponses:[],
                idEdit:0
            }
        },
        mounted(){
            EventBus.$on('loadResponseSod', (item) => {
                this.dataPolls=[];
                this.dataLoad=[];
                this.dataLoad=item;
                //console.log(this.dataLoad,this.dataLoad[0].store_id);
                this.getResponsesPoll();
            });
        },
        methods:{
            changeResponseSod(index,objResponse,change){
                this.idEdit = objResponse;console.log('Select: ',this.idEdit,change,index);
                let urlBase = '/api/changeResponseSiNo';
                axios.post(urlBase, {
                    poll_details_id: this.idEdit.id,
                    result: change
                })
                    .then((response) => {
                        this.getResponsesPoll();

                    })
                    .catch(function (error) {
                        //this.isLoading2 = false;
                        console.log(error);
                    });
            },
            changeResponseSodOptions(objPollDetails,objOption,objPollOptionDetails,Action)
            {
                this.isLoading2 = true;
                this.idEdit = objPollOptionDetails;console.log('Select: ',this.idEdit,objPollDetails,objOption,Action);
                // Action : 1 ->insert 0->delete
                if (Action==1)
                {
                    let storeId = objPollDetails.store_id;
                    let companyId = objPollDetails.company_id;
                    let pollOptionId = objOption.id;
                    let auditor = objPollDetails.auditor;
                    let publicity_id = objPollDetails.publicity_id;
                    let product_id = objPollDetails.product_id;//insertPollOptionDetail
                    let urlBase = '/api/insertPollOptionDetail';//console.log(storeId,"-",companyId,"-",pollOptionId,"-",auditor,"-",publicity_id,"-",product_id);
                    axios.post(urlBase, {
                        company_id: companyId,
                        store_id: storeId,
                        publicity_id: publicity_id,
                        poll_option_id: pollOptionId,
                        auditor: auditor,
                        product_id: product_id
                    })
                        .then((response) => {
                            this.isLoading2 = false;
                            this.getResponsesPoll();
                        })
                        .catch(function (error) {
                            //this.isLoading2 = false;
                            console.log(error);
                        });
                }
                if (Action==0)
                {
                    this.isLoading2 = true;
                    let urlBase = '/api/deletePollOptionDetail';//console.log(this.idEdit.id);
                    axios.post(urlBase, {
                        poll_option_details_id: this.idEdit.id
                    })
                        .then((response) => {
                            this.isLoading2 = false;
                            this.getResponsesPoll();

                        })
                        .catch(function (error) {
                            this.isLoading2 = false;
                            console.log(error);
                        });
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
                    //console.log('objetoTotal',this.dataPolls);

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