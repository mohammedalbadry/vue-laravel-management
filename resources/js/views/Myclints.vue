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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modal">
                    add
                    </button>
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
                    <th>name</th>
                    <th>info</th>
                    <th>actions</th>
                </tr>
                </thead>
                <tbody>
                     <tr v-for="(item, index) in allItem.data" :key="item.id">
                        <td>{{index + 1}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.address}} <br /> {{item.phone}}</td>
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
            <!-- add Modal -->
            <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">add new clint</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">name</label>
                                <input
                                 :class='[ newDataErrors.name ? "is-invalid" : ""]'
                                 v-model="newData.name"
                                 type="text" class="form-control" placeholder="name ...">
                                <div class="invalid-feedback" v-if="newDataErrors.name">{{ newDataErrors.name[0] }}</div>

                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">address</label>
                                <input
                                 :class='[ newDataErrors.address ? "is-invalid" : ""]'
                                 v-model="newData.address"
                                 type="text" class="form-control" placeholder="address ...">
                                <div class="invalid-feedback" v-if="newDataErrors.address">{{ newDataErrors.address[0] }}</div>

                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">phone</label>
                                <input
                                 :class='[ newDataErrors.phone ? "is-invalid" : ""]'
                                 v-model="newData.phone"
                                 type="text" class="form-control" placeholder="phone ...">
                                <div class="invalid-feedback" v-if="newDataErrors.phone">{{ newDataErrors.phone[0] }}</div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="create()">save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- show Modal -->
            <div class="modal fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">show clint</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>name: <strong>{{actionItem.name}}</strong></h4>
                        <h4 v-if="actionItem.address !== null">
                            address: <strong>{{actionItem.address}}</strong>
                        </h4>
                        <h4 v-if="actionItem.phone !== null">
                            phone: <strong>{{actionItem.phone}}</strong>
                        </h4>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">edit clint</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">name</label>
                            <input
                                :class='[ editDataErrors.name ? "is-invalid" : ""]'
                                v-model="actionItem.name"
                                type="text" class="form-control" placeholder="name ...">
                            <div class="invalid-feedback" v-if="editDataErrors.name">{{ editDataErrors.name[0] }}</div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">address</label>
                            <input
                                :class='[ editDataErrors.address ? "is-invalid" : ""]'
                                v-model="actionItem.address"
                                type="text" class="form-control" placeholder="address ...">
                            <div class="invalid-feedback" v-if="editDataErrors.address">{{ editDataErrors.address[0] }}</div>

                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">phone</label>
                            <input
                                :class='[ editDataErrors.phone ? "is-invalid" : ""]'
                                v-model="actionItem.phone"
                                type="text" class="form-control" placeholder="phone ...">
                            <div class="invalid-feedback" v-if="editDataErrors.phone">{{ editDataErrors.phone[0] }}</div>

                        </div>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">delete clint</h5>
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
                name: null,
                address: null,
                phone: null,
            },
            actionItem:{
                id: null,
                name: null,
                address: null,
                phone: null,
            },
            newDataErrors: {},
            editDataErrors: {},
            searchItems: null

        }
    },
    methods:{
        passActionItem:function(item){
            this.actionItem.id = item.id
            this.actionItem.name = item.name
            this.actionItem.address = item.address
            this.actionItem.phone = item.phone
        },
        read:function(page = 1){
            const search = this.searchItems != null ?  this.searchItems : null

            axios({
                    url: '/clint?page=' + page,
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
        create:function(){
            
            var fd = new FormData()
            if(this.newData.name != null){
                fd.append('name', this.newData.name)
            }
            if(this.newData.address != null){
                fd.append('address', this.newData.address)
            }
            if(this.newData.phone != null){
                fd.append('phone', this.newData.phone)
            }

            axios({
                    url: '/clint',
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,
            })
            .then(response=> {
                if(response.data.status == "error"){
                    this.newDataErrors = response.data.errors
                } else if(response.data.status == "success") {
                    this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.newDataErrors = {}
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
                }
               
            })
            .catch( error => {
               //console.log(error)
            });


        },
        update:function(){

            var fd = new FormData()
            if(this.actionItem.name != null){
                fd.append('name', this.actionItem.name)
            }
            if(this.actionItem.address != null){
                fd.append('address', this.actionItem.address)
            }
            if(this.actionItem.phone != null){
                fd.append('phone', this.actionItem.phone)
            }
            fd.append("_method", "put");

            axios({
                    url: '/clint/' + this.actionItem.id,
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
        destroy:function(){

            var fd = new FormData()
            fd.append("_method", "delete");

            axios({
                    url: '/clint/' + this.actionItem.id,
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

<style>

</style>