/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Swal from 'sweetalert2'
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })

window.Toast = Toast



Vue.component('pagination', require('laravel-vue-pagination'));

import store from "./store/index"
import router from './router/route'

// auth on reload
var token = localStorage.getItem("token")
if(token !== null){
  axios.defaults.headers.common['authorization'] = "Bearer " + token
  store.dispatch("attempAction", localStorage.getItem("token"))
}
const app = new Vue({
    el: '#app',
    router,
    store,
    components:{

    }
});
