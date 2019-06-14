import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '../containers/Full'
import FullContent from '../containers/FullContent'

// Views
import Dashboard from '../views/Dashboard'
import DashboardContent from '../views/DashboardContent'
import MedicionSod from '../views/MedicionSod'
import PanelContent from '../views/PanelContent'
import PanelLucky from '../views/PanelLucky'
import DisplayCampaigne from '../views/DisplayCampaigne'
import LoginInit from '../views/LoginInit'
import Charts from '../views/Charts'
import OsaAlicorp from '../views/OsaAlicorp'
import PruebasVue from '../views/PruebasVue'
import LuckyInconsistency from '../views/LuckyInconsistency'
import PanelValidacionCanjes from '../views/PanelValidacionCanjes'
import PanelAdmin from '../views/PanelAdmin'
//import Widgets from '../views/Widgets'

// Views - Components
/*import Buttons from '../views/components/Buttons'
import SocialButtons from '../views/components/SocialButtons'
import Cards from '../views/components/Cards'
import Forms from '../views/components/Forms'
import Modals from '../views/components/Modals'
import Switches from '../views/components/Switches'
import Tables from '../views/components/Tables'*/

// Views - Icons
import FontAwesome from '../views/icons/FontAwesome'
import SimpleLineIcons from '../views/icons/SimpleLineIcons'

// Views - Pages
// import Page404 from '@/views/pages/Page404'
// import Page500 from '@/views/pages/Page500'
// import Login from '@/views/pages/Login'
// import Register from '@/views/pages/Register'

Vue.use(Router)

export default new Router({
    mode: 'history', // Demo is living in GitHub.io, so required!
    linkActiveClass: 'open active',
    scrollBehavior: () => ({ y: 0 }),
    routes: [
        {
            path: '/',
            name: 'LoginInit',
            component: LoginInit
        },
        // {
        //     path: '/dashboard',
        //     name: 'dashboard',
        //     component: Dashboard,
        // }
        {
            path: '/dashboard',
            name: 'InicioPanel',
            component: Full,
            children: [
                {
                    path: '/dashboard',
                    name: 'Dashboard',
                    component: DashboardContent
                },
                {
                    path: '/charts',
                    name: 'Charts',
                    component: Charts
                }
            ]
        },
        {
            path: '/panel',
            name: 'HomePanel',
            component: Full,
            children: [
                {
                    path: '/panel',
                    name: 'PanelCompanies',
                    component: PanelContent
                }
            ]
        },
        {
            path: '/medirSod',
            name: 'Sod',
            component: Full,
            children: [
                {
                    path: '/medirSod',
                    name: 'MedicionSod',
                    component: MedicionSod
                }
            ]
        },
        {
            path: '/panelLucky',
            name: 'Lucky',
            component: Full,
            children: [
                {
                    path: '/panelLucky',
                    name: 'PanelLucky',
                    component: PanelLucky
                }
            ]
        },
        {
            path: '/panelValidacionCanjes',
            name: 'ValidacionCanjes',
            component: Full,
            children: [
                {
                    path: '/panelValidacionCanjes',
                    name: 'PanelValidacionCanjes',
                    component: PanelValidacionCanjes
                }
            ]
        },
        {
            path: '/panelAdmin',
            name: 'PanelAdmin',
            component: Full,
            children: [
                {
                    path: '/panelAdmin',
                    name: 'PanelAdmin',
                    component: PanelAdmin
                }
            ]
        },
        {
            path: '/pruebas_admin',
            name: 'PruebasVue',
            component: Full,
            children: [
                {
                    path: '/pruebas_admin',
                    name: 'PruebasVue',
                    component: PruebasVue
                }
            ]
        },
        {
            path: '/home/:identify',
            name: 'Administrator',
            component: Full,
            children: [
                {
                    path: '/home/:identify',
                    name: 'DisplayCampaigne',
                    component: DisplayCampaigne,
                    props: true
                }
            ]
        },
        {
            path: '/osaDashboard',
            name: 'Osa',
            component: Full,
            children: [
                {
                    path: '/osaDashboard',
                    name: 'OsaAlicorp',
                    component: OsaAlicorp
                }
            ]
        },
        {
            path: '/inconsistencyLucky',
            name: 'inconsistency',
            component: Full,
            children: [
                {
                    path: '/inconsistencyLucky',
                    name: 'LuckyInconsistency',
                    component: LuckyInconsistency
                }
            ]
        }
    ]
})
