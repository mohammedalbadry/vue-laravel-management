<template>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link to="home" class="brand-link">
      <img :src="setting.logo_path" :alt="setting.name" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{setting.name}}</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="auth.img_path" :alt="auth.name">
        </div>
        <div class="info">
          <router-link  to="/admin/profile" class="d-block">{{auth.name}}</router-link>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <router-link to="/admin/home" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                dashboard
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/employees" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                employees
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/category" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                caregories
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/products" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                products
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/clints" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                clints
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/settings" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                settings
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/new_order" class="nav-link">
              <i class="nav-icon fas fa-cart-plus"></i>
              <p>
                new order
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/admin/orders" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                orders
              </p>
            </router-link>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
</template>

<script>
export default {
    computed:{
      setting(state){
        return this.$store.state.setting
      },
      auth(state){
        return this.$store.state.auther
      },
    
    }
}
</script>

<style>

</style>