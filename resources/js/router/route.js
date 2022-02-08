import Vue from 'vue'
import VueRouter from 'vue-router'
import store from "../store/index"

import App from '../layouts/App.vue'
import Base from '../layouts/Base.vue'

import MyLogin from '../views/MyLogin.vue'
import Enduser from '../views/Enduser.vue'
import NotFound from '../views/404.vue'
import Forbidden from '../views/403.vue'


import Myprofile from '../views/Myprofile.vue'
import Myemployees from '../views/Myemployees.vue'
import Mycategory from '../views/Mycategory.vue'
import Myhome from '../views/Myhome.vue'
import Myproduct from '../views/Myproduct.vue'
import Myclints from '../views/Myclints.vue'
import Mysettings from '../views/Mysettings.vue'

import Myorders from '../views/order/Myorders.vue'
import Myneworder from '../views/order/Myneworder.vue'
import Myeditorder from '../views/order/Myeditorder.vue'


Vue.use(VueRouter)


const router = new VueRouter({
    mode: 'history',
    routes:[
        {
            path: '/',
            name: 'base',
            component: Base,
            children: [
                {
                    path: '/',
                    name: 'enduser',
                    component: Enduser,
                },
                {
                    path: "admin/login",
                    name: "Login",
                    component: MyLogin,
                },
                {
                    path: "404",
                    name: "404",
                    component: NotFound,
                },
                {
                    path: "403",
                    name: "403",
                    component: Forbidden,
                }
            ],
        },
        {
            path: '/admin',
            name: 'app',
            component: App,
            redirect: '/admin/home',
            beforeEnter: (to, from, next) => {
                if(store.state.auther == null || store.state.token == null){
                  next('/admin/login')
                }else{
                  next()
                }
                
              },
            children: [
                {
                    path: 'home',
                    name: 'home',
                    component: Myhome,
                },
                {
                    path: 'profile',
                    name: 'profile',
                    component: Myprofile,
                },
                {
                    path: 'employees',
                    name: 'employees',
                    component: Myemployees,
                },
                {
                    path: 'category',
                    name: 'category',
                    component: Mycategory,
                },
                {
                    path: 'products',
                    name: 'products',
                    component: Myproduct,
                },
                {
                    path: 'clints',
                    name: 'clints',
                    component: Myclints,
                },
                {
                    path: 'orders',
                    name: 'orders',
                    component: Myorders,
                },
                {
                    path: 'new_order',
                    name: 'new_order',
                    component: Myneworder,
                },    
                {
                    path: 'edit_order/:id',
                    name: 'edit_order',
                    component: Myeditorder,
                },
                {
                    path: 'settings',
                    name: 'settings',
                    component: Mysettings,
                },
            ],
        },
        {
            path: "*",
            name: "404",
            component: NotFound,
        },
    ]
})


export default router