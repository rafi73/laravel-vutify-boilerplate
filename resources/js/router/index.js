import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

//
import example_component from '../components/ExampleComponent.vue'
import admin_component from '../components/AdminComponent.vue'
import login_component from '../components/LoginComponent.vue'
import r_link from '../components/RouterLink.vue'

//
Vue.component('example-component', example_component)
Vue.component('admin-component', admin_component)
Vue.component('r-link', r_link)
Vue.component('login-component', login_component)


//
import home from '../components/HomeComponent.vue'
import admin_user from '../components/Admin/UserComponent.vue'

export default new Router({
    mode: 'history',
    routes: 
    [
        {
            path: '/admin/login',
            name: 'login',
            meta: 
            { 
                requiresAuth : false
            }
        },
        { 
            path: '/admin/home', 
            name: 'home', 
            component: home, 
            meta: 
            { 
                name: 'ホーム', 
                icon: 'home' ,
                requiresAuth : true
            } 
        },
        { 
            path: '/admin/user', 
            name: 'admin_user', 
            component: admin_user, 
            meta: 
            { 
                name: '社員管理', 
                icon: 'supervisor_account',
                requiresAuth : true
            } 
        },
    ],
})