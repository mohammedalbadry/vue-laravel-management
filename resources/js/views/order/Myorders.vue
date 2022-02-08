<template>
    <div class="card card-outline card-primary">

        <div class="card-header">
            <div class="row">
                <div class="col-md-5">
                     <form action="" method="get">
                        <div class="input-group mb-2">
                            <input @input.prevent="searchfun()" v-model="searchItems" name="search" value="" type="text" class="form-control" placeholder="search ...">
                            <span class="input-group-append">
                              <button @click.prevent="searchfun()" type="submit" class="btn btn-primary btn-flat">search</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-7">
                    <router-link to="/admin/new_order" target="_blank" class="btn btn-primary">
                        add
                    </router-link>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>                  
                <tr>
                    <th style="width: 10px">#</th>
                    <th>total</th>
                    <th>status</th>
                    <th>clint name</th>
                    <th>actions</th>
                </tr>
                </thead>
                <tbody>
                     <tr v-for="(item, index) in allItem.data" :key="item.id">
                        <td>{{index}}</td>
                        <td>{{item.total}}</td>
                        <td>
                            <button class='btn btn-sm btn-status' :class="item.status">{{item.status}}</button>
                        </td>
                        <td>{{item.clint.name}}</td>
                        <td>
                            <button @click="passActionItem(item)" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#show_modal">
                                    show <i class="fas fa-eye"></i>
                            </button>
                            
                            <button @click="passActionItem(item)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_modal">
                                edit <i class="fa fa-edit"></i>
                            </button>
                            <button @click="passActionItem(item)" type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete_modal">
                                delete <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                     </tr>
                </tbody>
            </table>
            <pagination :data="allItem" @pagination-change-page="read"></pagination>
        </div>
        <!-- /.card-body -->

        <div class="all_modals">
            <!-- show Modal -->
            <div class="modal fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">order details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="clint" v-if="actionItem.items">
                            <h6 v-if="actionItem.clint.name !== null" class="mr-3">
                                name: <strong>{{actionItem.clint.name }}</strong>
                            </h6>
                            <h6 v-if="actionItem.clint.address !== null" class="mr-3">
                                address: <strong>{{actionItem.clint.address}}</strong>
                            </h6>
                            <h6 v-if="actionItem.clint.phone !== null">
                                phone: <strong>{{actionItem.clint.phone}}</strong>
                            </h6>
                        </div>

                        <table v-if="actionItem.items && actionItem.items.length !== 0" class="table mb-0">
                            <thead class="table-primary">
                                <tr>
                                <th scope="col">product name</th>
                                <th scope="col">quantity</th>
                                <th scope="col">price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in actionItem.items" :key="index">
                                    <td>{{item.name}}</td>
                                    <td>{{item.quantity}}</td>
                                    <td>$ {{item.price}}</td>
                                </tr>
                                <tr v-if="actionItem.discount > 0" class="table-danger">
                                    <td>discount</td>
                                    <td>---</td>
                                    <td>$ {{actionItem.discount}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div  v-if="actionItem.length !== 0" class="payment">
                            <div class="row">    
                                <div class="col-6">total</div>
                                <div class="col-6">$  {{actionItem.total}}</div>
                            </div>
                        </div>
                        <div v-if="actionItem.note !== null" class="note">
                            note: <strong>{{actionItem.note}}</strong>
                        </div>
                        <div class="note">
                            code: <strong>{{actionItem.code}}</strong>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
            <!-- edit Modal -->
            <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">edit item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">status</label>
                            <select 
                                :class='[ editDataErrors.status ? "is-invalid" : ""]'
                                v-model="actionItem.status"
                                class="custom-select form-select form-select-lg" aria-label=".form-select-lg example">
                                <option value="pending">pending</option>     
                                <option value="complete">complete</option>
                                <option value="processing">processing</option>
                                <option value="shipping">shipping</option>
                                <option value="refound">refound</option>
                                <option value="canceled">canceled</option>
                            </select>   
                            <div class="invalid-feedback" v-if="editDataErrors.status">{{ editDataErrors.status[0] }}</div>
                        </div>
                                
                        <router-link :to="'/admin/edit_order/' + actionItem.id" target="_blank" class="btn btn-primary btn-block">
                            Edit details <i class="fa fa-edit"></i>
                        </router-link>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="update()" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
            <!-- delete Modal -->
            <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">delete item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Confirm deletion of <strong>{{actionItem.name}}</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button @click="destroy()" type="button" class="btn btn-danger">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data(){
        return {
            allItem: {},
            newData:{
                name: ""
            },
            actionItem:{},
            newDataErrors: {},
            editDataErrors: {},
            searchItems: null

        }
    },
    methods:{
        passActionItem:function(item){
            this.actionItem = item
        },
        read:function(page = 1){
            const search = this.searchItems != null ?  this.searchItems : null

            axios({
                    url: '/order?page=' + page,
                    method: 'get',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    params: { 
                        search
                    }
            })
            .then(response=> {
              this.allItem = response.data.data               
            })
            .catch( error => {
               //console.log(error)
            });


        },
        update:function(){

            var fd = new FormData()
            fd.append("_method", "put");
            fd.append("status", this.actionItem.status);
            fd.append('total', this.actionItem.total)
            fd.append('discount', this.actionItem.discount)
            fd.append('note', this.actionItem.note)
            fd.append('order_items[]', JSON.stringify(this.actionItem.items))

            axios({
                    url: '/order/' + this.actionItem.id,
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,

            })
            .then(response=> {
                                    console.log(response)

                if(response.data.status == "error"){
                    this.editDataErrors = response.data.errors
                } else if(response.data.status == "success") {
                    this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.editDataErrors = {}
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())

                }
               
            })
            .catch( error => {
               //console.log(error)
            });

        },
        destroy:function(){

            var fd = new FormData()
            fd.append("_method", "delete");

            axios({
                    url: '/order/' + this.actionItem.id,
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,

            })
            .then(response=> {
                if(response.data.status == "error"){
                    this.editDataErrors = response.data.errors
                } else if(response.data.status == "success") {
                    this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.editDataErrors = {}
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())

                }
               
            })
            .catch( error => {
               //console.log(error)
            });


        },
        searchfun:function(){
            this.read()
        }
    },
    mounted() {
        this.read();
    }
}
</script>

<style scoped>
.btn-status{color: #fff}
.btn-status.shipping{background-color: #0275d8}
.btn-status.processing{background-color: #5bc0de}
.btn-status.complete{background-color: #5cb85c}
.btn-status.pending{background-color: #f0ad4e}
.btn-status.refound{background-color: #d9534f}
.btn-status.canceled{background-color: #292b2c}
</style>