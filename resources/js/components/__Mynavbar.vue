<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">


           <button 
           @click="logout()"
           class="btn btn-light btn-sm mx-2">
               <i class="nav-icon fas fa-sign-out-alt"></i>
            </button>

        </ul>
    </nav>
</template>

<script>
export default {
    methods:{
        logout(){
             this.$store.dispatch('logoutAction')
        }
    }
}
</script>

<style>

</style>