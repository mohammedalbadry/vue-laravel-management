<template>
  <div>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{clints_count}}</h3>

                <p>clints</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{products_count}}</h3>

                <p>products</p>
              </div>
              <div class="icon">
                <i class="fas fa-boxes"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{orders_count}}</h3>

                <p>orders</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{categories_count}}</h3>

                <p>categories</p>
              </div>
              <div class="icon">
                <i class="fas fa-th"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-running"></i>
                  quick access
                </h3>
              </div><!-- /.card-header -->
              <div class="card-body quick_access">
                <div class="row">
                  <div class="col-12 col-sm-6 col-md-4">
                    <router-link to="/admin/new_order" class="item">
                      new order
                    </router-link>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4">
                    <router-link to="/admin/orders" class="item">
                      access orders
                    </router-link>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4">
                    <router-link to="/admin/clints" class="item">
                      access clints
                    </router-link>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4">
                    <router-link to="/admin/employees" class="item">
                      access employees
                    </router-link>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4">
                    <router-link to="/admin/category" class="item">
                      access categoris
                    </router-link>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4">
                    <router-link to="/admin/products" class="item">
                      access products
                    </router-link>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          </section>
          <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
export default {
  data(){
    return{
      clints_count:1,
      products_count:1,
      orders_count:1,
      categories_count:1,
    }
  },
  methods:{
    read(){
            axios({
                    url: '/home',
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
            })
            .then(response=> {
                if(response.data.status == "success") {
                   this.clints_count = response.data.clints_count
                   this.products_count = response.data.clints_count
                   this.orders_count = response.data.orders_count
                   this.categories_count = response.data.categories_count
                }else{
                  alert("something is wrong");
                }
               
            })
            .catch( error => {
               //console.log(error)
            });
    }
  },
  created(){
    this.read()
  }
}

</script>

<style>
.quick_access .item{
  padding: 15px;
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #343a40;
  font-size: 30px;
  border-radius: 8px;
  background-color: #ccc;
  margin-bottom: 30px;
  transition: all .6s ease;
  box-shadow: 1px 1px 5px 2px #0000007a
  
}
.quick_access .item:hover{
  opacity: .8;
}


</style>