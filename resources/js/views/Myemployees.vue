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
                    <th>email</th>
                    <th>job_title</th>
                    <th>actions</th>
                </tr>
                </thead>
                <tbody>
                     <tr v-for="(item, index) in allItem.data" :key="item.id">
                        <td>{{index + 1}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.job_title}}</td>

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
                            <h5 class="modal-title" id="exampleModalLongTitle">add new employee</h5>
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
                                <label class="form-label">email</label>
                                <input
                                 :class='[ newDataErrors.email ? "is-invalid" : ""]'
                                 v-model="newData.email"
                                 type="text" class="form-control" placeholder="email ...">
                                <div class="invalid-feedback" v-if="newDataErrors.email">{{ newDataErrors.email[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">password</label>
                                <input
                                 :class='[ newDataErrors.password ? "is-invalid" : ""]'
                                 v-model="newData.password"
                                 type="password" class="form-control" placeholder="password ...">
                                <div class="invalid-feedback" v-if="newDataErrors.password">{{ newDataErrors.password[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">password confirm</label>
                                <input
                                 :class='[ newDataErrors.password_confirmation ? "is-invalid" : ""]'
                                 v-model="newData.password_confirmation"
                                 type="password" class="form-control" placeholder="password ...">
                                <div class="invalid-feedback" v-if="newDataErrors.password_confirmation">{{ newDataErrors.password_confirmation[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">job title</label>
                                <select 
                                  v-model="newData.job_title"
                                  :class='[ newDataErrors.job_title ? "is-invalid" : ""]'
                                  class="custom-select form-select form-select-lg" aria-label=".form-select-lg example">
                                  <option value="employee">employee</option>
                                  <option value="supervisor">supervisor</option>
                                  <option value="manager">manager</option>
                                </select>
                                <div class="invalid-feedback" v-if="newDataErrors.job_title">{{ newDataErrors.job_title[0] }}</div>
                            </div>
                            <div class="mb-3">
                              <div class="row">
                                <div class="col-9 col-sm-10">
                                  <div class="form-group">
                                      <label for="exampleInputFile">File input</label>
                                      <div :class='[ newDataErrors.img ? "is-invalid" : ""]'
                                           class="input-group">
                                          <div class="custom-file">
                                            <input 
                                              @change="processFile($event)"
                                              :class='[ newDataErrors.img ? "is-invalid" : ""]'
                                              type="file" class="custom-file-input" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                          </div>
                                      </div>
                                      <div class="invalid-feedback" v-if="newDataErrors.img">{{ newDataErrors.img[0] }}</div>
                                  </div>
                                </div>
                                <div class="col-3 col-sm-2">
                                  <img class="img_preview" :src="add_preview_img" alt="">
                                </div>
                              </div>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">show employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                          <div class="img">
                            <img :src="actionItem.img_path" alt="">
                          </div>
                          <h4>{{actionItem.name}}</h4>
                          <h6>{{actionItem.job_title}}</h6>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">edit employee</h5>
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
                            <label class="form-label">email</label>
                            <input
                              :class='[ editDataErrors.email ? "is-invalid" : ""]'
                              v-model="actionItem.email"
                              type="text" class="form-control" placeholder="email ...">
                            <div class="invalid-feedback" v-if="editDataErrors.email">{{ editDataErrors.email[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">password</label>
                            <input
                              :class='[ editDataErrors.password ? "is-invalid" : ""]'
                              v-model="actionItem.password"
                              type="password" class="form-control" placeholder="password ...">
                            <div class="invalid-feedback" v-if="editDataErrors.password">{{ editDataErrors.password[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">password confirm</label>
                            <input
                              :class='[ editDataErrors.password_confirmation ? "is-invalid" : ""]'
                              v-model="actionItem.password_confirmation"
                              type="password" class="form-control" placeholder="password ...">
                            <div class="invalid-feedback" v-if="editDataErrors.password_confirmation">{{ editDataErrors.password_confirmation[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">job title</label>
                            <select 
                              v-model="actionItem.job_title"
                              :class='[ editDataErrors.job_title ? "is-invalid" : ""]'
                              class="custom-select form-select form-select-lg" aria-label=".form-select-lg example">
                              <option value="employee">employee</option>
                              <option value="supervisor">supervisor</option>
                              <option value="manager">manager</option>
                            </select>
                            <div class="invalid-feedback" v-if="editDataErrors.job_title">{{ editDataErrors.job_title[0] }}</div>
                        </div>
                        <div class="mb-3">
                          <div class="row">
                            <div class="col-9 col-sm-10">
                              <div class="form-group">
                                  <label for="exampleInputFile">File input</label>
                                  <div :class='[ editDataErrors.img ? "is-invalid" : ""]'
                                        class="input-group">
                                      <div class="custom-file">
                                        <input 
                                          @change="processFile($event, 'edit')"
                                          :class='[ editDataErrors.img ? "is-invalid" : ""]'
                                          type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                      </div>
                                  </div>
                                  <div class="invalid-feedback" v-if="editDataErrors.img">{{ editDataErrors.img[0] }}</div>
                              </div>
                            </div>
                            <div class="col-3 col-sm-2">
                              <img
                              :src="actionItem.img_path"
                               class="img_preview" alt="">
                            </div>
                          </div>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">delete employee</h5>
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
            add_preview_img: "http://127.0.0.1:8000/uploads/employees/default.png",
            newData:{
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                img: "",
                job_title: "employee"
            },
            actionItem:{
                id: null,
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                img: "",
                img_path: "",
                job_title: ""
            },
            newDataErrors: {},
            editDataErrors: {},
            searchItems: null

        }
    },
    methods:{
        processFile(event, type = "add") {
          var file = event.target.files[0] 
          if(type == "add"){
           this.newData.img = file
           this.add_preview_img = URL.createObjectURL(file);
          }else{
            this.actionItem.img = file
            this.actionItem.img_path = URL.createObjectURL(file);
          }
        },
        passActionItem:function(item){
            this.actionItem.id = item.id
            this.actionItem.name = item.name
            this.actionItem.email = item.email
            this.actionItem.img = item.img
            this.actionItem.img_path = item.img_path
            this.actionItem.job_title = item.job_title
        },
        read:function(page = 1){
            const search = this.searchItems != null ?  this.searchItems : null

            axios({
                    url: '/Employee?page=' + page,
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
            fd.append('name', this.newData.name)
            fd.append('email', this.newData.email)
            fd.append('password', this.newData.password)
            fd.append('password_confirmation', this.newData.password_confirmation)
            fd.append('img', this.newData.img)
            fd.append('job_title', this.newData.job_title)


            axios({
                    url: '/Employee',
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,
            })
            .then(response=> {
                if(response.data.status == "error"){
                    this.newDataErrors = response.data.errors
                } else if(response.data.status == "success") {
                  console.log("s")
                    this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.newDataErrors = {}
                    this.newData = {
                            name: "",
                            email: "",
                            password: "",
                            password_confirmation: "",
                            img: "",
                            job_title: "employee"
                    }
                    this.add_preview_img = "http://127.0.0.1:8000/uploads/employees/default.png"
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
                }
               
            })
            .catch( error => {
               //console.log(error)
            });


        },
        update:function(){

            var fd = new FormData()
            fd.append("_method", "put");

            fd.append('name', this.actionItem.name)
            fd.append('email', this.actionItem.email)
            fd.append('password', this.actionItem.password)
            fd.append('password_confirmation', this.actionItem.password_confirmation)
            if(typeof(this.actionItem.img) == "object"){
              fd.append('img', this.actionItem.img)
            }
            fd.append('job_title', this.actionItem.job_title)

            axios({
                    url: '/Employee/' + this.actionItem.id,
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
                    this.actionItem = {
                        id: null,
                        name: "",
                        email: "",
                        password: "",
                        password_confirmation: "",
                        img: "",
                        img_path: "",
                        job_title: ""
                    },
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
                    url: '/Employee/' + this.actionItem.id,
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
.img{
  width: 80px;
  overflow: hidden;
  border: 1px solid #007bff;
  padding: 2px;
  margin: 0 auto 10px;
}
.img img{
  width: 100%;
}
.img_preview{
  width: 100%;
}
</style>