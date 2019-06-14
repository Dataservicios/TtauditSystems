<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card-group mb-0" v-if="sinLogin">
                        <div class="card p-4">
                            <div class="card-body">
                                <form @submit="checkForm"  action="https://vuejs.org/"  method="post">
                                    <h1>Inicio de sesión</h1>
                                    <p class="text-muted">Control de acceso al sistema</p>
                                    <div v-if="errors.length" class="alert alert-danger" role="alert">
                                        <b>Error</b>
                                        <ul>
                                            <li v-for="error in errors">{{ error }}</li>
                                        </ul>
                                    </div>

                                    <div class="input-group mb-3">
                                        <span class="input-group-addon"><i class="icon-user"></i></span>
                                        <input type="text" class="form-control" placeholder="Username" id="username" v-model="username">
                                    </div>
                                    <div class="input-group mb-4">
                                        <span class="input-group-addon"><i class="icon-lock"></i></span>
                                        <input type="password" class="form-control" placeholder="Password" id="password" v-model="password">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-primary px-4">Ingresar</button>
                                        </div>
                                        <!-- <div class="col-6 text-right">
                                            <button type="button" class="btn btn-link px-0">Forgot password?</button>
                                        </div> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                            <div class="card-body text-center">
                                <!-- <div>
                                    <h2>Sign up</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <button type="button" class="btn btn-primary active mt-3">Register Now!</button>
                                    <p>Versión: 2.1.4</p>
                                </div> -->
                                <div>
                                    <img src="images/logo.png" width="200px">
                                    <p>Sistema de Investigación y Control de Puntos de Venta.</p>
                                    <a href="http://ttaudit.com" target="_blank" class="btn btn-primary active mt-3">Visítanos</a>
                                    <p>Versión: {{version}}</p>
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
        name: 'LoginInit',
        mounted(){
            if (sessionStorage.getItem('api_token'))
            {
                this.sinLogin=false;
            }
        },
        data:function () {
            return {
            errors: [],
            username: null,
            password: null,
            sinLogin: true,
            objUser: [],
            version:'2.2.4',
        }
        },
        methods:{
            checkForm: function (e) {
                //
                var router = this.$router;
                e.preventDefault();
                if (this.username && this.password) {
                    //console.log("probando");
                    //router.push('/dashboard')
                    this.login();
                }

                this.errors = [];

                if (!this.username) {
                    this.errors.push('Ingrese el usuario.');
                }

                if (!this.password) {
                    this.errors.push('Ingrese contraseña');
                }



            },
            login(){
                let urlBase = '/api/loginUser';
                axios.post(urlBase, {
                    email: this.username,
                    password: this.password
                })
                    .then((response) => {
                        if (response.data.api_token == null)
                        {
                            this.errors.push('Usuario No esta actualizado.');
                        }else{
                            sessionStorage.setItem('api_token',response.data.api_token);
                            this.objUser = response.data.obj_user;
                            let _Modules = response.data.obj_modules;
                            sessionStorage.setItem('user',JSON.stringify(response.data.obj_user));
                            sessionStorage.setItem('user_modules',JSON.stringify(response.data.obj_modules));
                            sessionStorage.setItem('user_roles',JSON.stringify(response.data.obj_roles));
                            let startUrl=0;let urlRouter;
                            for (var indice1 in _Modules) {
                                if (_Modules[indice1]['title'] == 1){
                                    urlRouter = _Modules[indice1]['url'];
                                    startUrl=1;break;
                                }
                            }
                            if (startUrl==0)
                            {
                                for (var indice1 in _Modules) {
                                    urlRouter = _Modules[indice1]['url'];
                                    startUrl=1;break;
                                }
                            }
                            this.$router.push(urlRouter);

                            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.api_token;
                            this.sinLogin=false;
                        }
                        
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            logout()
            {
                sessionStorage.removeItem('api_token');
            }
        }
    }
</script>
