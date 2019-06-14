<template>
  <div class="app">
    <AppHeader/>
    <div class="app-body">
      <Sidebar :navItems="nav"/>
      <main class="main">
        <!--<breadcrumb :list="list"/>-->
        <div class="container-fluid">
          <router-view></router-view>
        </div>
      </main>
      
    </div>
    <AppFooter/>
  </div>
</template>

<script>
import nav from '../_nav'
import { HeaderTop as AppHeader, Sidebar, AsideApp as AppAside, FooterApp as AppFooter, Breadcrumb } from '../components/'

export default {
  name: 'full',
  components: {
    AppHeader,
    Sidebar,
    AppAside,
    AppFooter,
    Breadcrumb
  },
  data() {
    return {
      nav: [],
      nav1:[],
      study_id:17,
    }
  },
  mounted(){
    this.getMenus()
  },
  methods:{
        getMenus(){
            // let urlCombo = '/api/getMenus/' + this.study_id ;
            // axios.get(urlCombo)
            //     .then((response) => {
            //         this.nav = response.data;
            //         console.log('menus: ',this.nav);
            //     })
            //     .catch((response) => {
            //         console.log('Error', error);
            //     })

            var _Api_token = sessionStorage.getItem('api_token');
            if (_Api_token == null){
              
              this.$router.push('/');
            }else{
              
              let _Modules = JSON.parse(sessionStorage.getItem('user_modules'));
              
              for (var indice1 in _Modules) {
                if (_Modules[indice1].title == 1){
                  this.nav.push({title: true, icon: "icon-speedometer", name:_Modules[indice1].fullname, url:_Modules[indice1].url});
                }else{
                  this.nav.push({title: false, icon: _Modules[indice1].icon, name:_Modules[indice1].fullname, url:_Modules[indice1].url});
                  //this.nav.push({title: false, icon: "icon-docs", name:_Modules[indice1].fullname, url:_Modules[indice1].url});
                }
              }
              //console.log('menus final: ',this.nav);
            }
        },

    },
  computed: {
    name () {
      return  this.$route.name
    },
    list () {
      return this.$route.matched
    }
  }
}
</script>
