import Vue from 'vue'
import VueRouter from 'vue-router'

import App from '../layouts/App.vue'
import Base from '../layouts/Base.vue'
import MyLogin from '../views/MyLogin.vue'
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
            //redirect: '/category',
            children: [
                {
                    path: '/login',
                    name: 'login',
                    component: MyLogin,
                    beforeEnter: (to, from, next) => {
                        if(localStorage.getItem("token") == null){
                            next()
                        }else{
                            next("/home")
                        }
                    },
                },
            ],
        },
        {
            path: '/app',
            name: 'app',
            component: App,
            redirect: '/home',
            beforeEnter: (to, from, next) => {
                if(localStorage.getItem("token") !== null){
                    next()
                }else{
                    next("/login")
                }
            },
            children: [
                {
                    path: '/home',
                    name: 'home',
                    component: Myhome,
                },
                {
                    path: '/profile',
                    name: 'profile',
                    component: Myprofile,
                },
                {
                    path: '/employees',
                    name: 'employees',
                    component: Myemployees,
                },
                {
                    path: '/category',
                    name: 'category',
                    component: Mycategory,
                },
                {
                    path: '/products',
                    name: 'products',
                    component: Myproduct,
                },
                {
                    path: '/clints',
                    name: 'clints',
                    component: Myclints,
                },
                {
                    path: '/orders',
                    name: 'orders',
                    component: Myorders,
                },
                {
                    path: '/new_order',
                    name: 'new_order',
                    component: Myneworder,
                },    
                {
                    path: '/edit_order/:id',
                    name: 'edit_order',
                    component: Myeditorder,
                },
                {
                    path: '/settings',
                    name: 'settings',
                    component: Mysettings,
                },
            ],
        },
    ]
})


export default router