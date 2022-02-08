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

const set_setting = new Promise((resolve) => {
  axios.get(`http://127.0.0.1:8000/api/setting`).then((res)=>{
          store.dispatch('settingAction', res.data.data)
          document.title = res.data.data.name

          var link = document.createElement('link');
          link.rel = 'icon';
          link.id = 'site_icon';
          document.getElementsByTagName('head')[0].appendChild(link);
          link.href = res.data.data.icon_path
          resolve('go to next route')
      })   
});
var token = localStorage.getItem("token")

const valid_authentication = new Promise((resolve) => {
   
  if(token == null){resolve('go to login')}
  else{

    axios({
      url: 'http://127.0.0.1:8000/api/auth/me/',
      method: 'post',
      headers: {'authorization': "Bearer " + token}
    })
    .then(res=> {

      let current_router = router.history

      store.commit('setError', null)
			store.commit('setToken', token)
      store.commit('setAuther', res.data)

      if(current_router._startLocation == "/admin/login"){
        axios.defaults.headers.common['authorization'] = "Bearer " + token
        router.push('admin/home').catch(() => { /* ignore */ })
      } else {
        axios.defaults.headers.common['authorization'] = "Bearer " + token
        router.push(current_router._startLocation).catch(() => { /* ignore */ })
      }
      resolve('go to next route')

    }).catch( error => {
        store.commit('setError', "Unauthorized")
				store.commit('setToken', null)
				store.commit('setAuther', null)
        localStorage.removeItem("token")         
        resolve('go to next route')

    });

      
      
  }    
});
set_setting.then(()=>{
  valid_authentication.then(()=>{
    const app = new Vue({
        el: '#app',
        router,
        store,
        components:{}
      });
      
  })
})
