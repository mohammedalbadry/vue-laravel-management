import Vue from 'vue'
import Vuex from 'vuex'
import router from '../router/route'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		setting: null,
        auther: null,
		token: null,
		authError: null,
	},
	mutations: {
		setAuther(state, payload){
			state.auther = payload
			localStorage.setItem("auther", payload)
		},
		setToken(state, payload){
			state.token = payload
			localStorage.setItem("token", payload)

		},
		setError(state, payload){
			state.authError = payload
		},
		setSetting(state, payload){
			state.setting = payload
		}
	},
	actions: {
		loginAction (context, payload) {
            var fd = new FormData()
            fd.append('email', payload.email)
            fd.append('password', payload.password)

            axios({
                    url: '/auth/login/',
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,

            })
            .then(response=> {
                if(response.data.status == "error"){
					context.commit('setError', response.data.error)
                }
                if(response.data.status == "success"){
					context.dispatch("attempAction",response.data.access_token)
                }
            })
            .catch( error => {
               //console.log(error)
            });
		},
		attempAction(context, token){

			axios({
				url: 'http://127.0.0.1:8000/api/auth/me/',
				method: 'post',
				headers: {'authorization': "Bearer " + token}
			})
			.then(res=> {
				context.commit('setError', null)
				context.commit('setToken', token)
				context.commit('setAuther', res.data)

				if(router.history.current.path == "/admin/login"){
					axios.defaults.headers.common['authorization'] = "Bearer " + token
					router.push('home').catch(() => { /* ignore */ })
				} else {
					router.push(router.history.current.path).catch(() => { /* ignore */ })
				}
				
			})
			.catch( error => {
				context.commit('setError', "Unauthorized")
				context.commit('setToken', null)
				context.commit('setAuther', null)
				localStorage.removeItem("token")
			});

				
		
		},
		logoutAction(context) {

			axios({
				url: '/auth/logout/',
				method: 'post',
				baseURL: 'http://127.0.0.1:8000/api/',
				})
				.then(response=> {

					context.commit('setError', null)
					context.commit('setToken', null)
					context.commit('setAuther', null)
					localStorage.removeItem("token")
					localStorage.removeItem("auther")
					router.push('/admin/login').catch(() => { /* ignore */ })


				})
				.catch( error => {
					//console.log(error)
				});
			

		},
		settingAction(context, settings){
			context.commit('setSetting', settings)
		}
	}

})

export default store
