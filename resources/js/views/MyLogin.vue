<template>
<div class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Admin</b>LTE</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="../../index3.html" method="post">
                <p class="text-danger" 
                v-if="this.$store.state.authError !== null"
                >{{this.$store.state.authError}}</p>
                <div class="input-group mb-3">
                    <input v-model="email" type="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input v-model="password" type="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button @click.prevent="login()" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    data(){
        return {
            email: "",
            password: "",
        }
    },
    methods:{
        login:function(){
            this.$store.dispatch('loginAction', {
                email: this.email,
                password: this.password
            })
        }
    },
    created(){
      /*  if(this.$store.getters.isLogged !== false){
            this.$router.push('category') 
        }*/
    }
}
</script>

<style>

</style>