<template>
  <div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div class="mb-3">
            <label class="form-label">name</label>
            <input
              :class='[ editDataErrors.name ? "is-invalid" : ""]'
              v-model="theItem.name"
              type="text" class="form-control" placeholder="name ...">
            <div class="invalid-feedback" v-if="editDataErrors.name">{{ editDataErrors.name[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">email</label>
            <input
              :class='[ editDataErrors.email ? "is-invalid" : ""]'
              v-model="theItem.email"
              type="text" class="form-control" placeholder="email ...">
            <div class="invalid-feedback" v-if="editDataErrors.email">{{ editDataErrors.email[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">password</label>
            <input
              :class='[ editDataErrors.password ? "is-invalid" : ""]'
              v-model="theItem.password"
              type="password" class="form-control" placeholder="password ...">
            <div class="invalid-feedback" v-if="editDataErrors.password">{{ editDataErrors.password[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">password confirm</label>
            <input
              :class='[ editDataErrors.password_confirmation ? "is-invalid" : ""]'
              v-model="theItem.password_confirmation"
              type="password" class="form-control" placeholder="password ...">
            <div class="invalid-feedback" v-if="editDataErrors.password_confirmation">{{ editDataErrors.password_confirmation[0] }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">job title</label>
            <select 
              v-model="theItem.job_title"
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
              :src="theItem.img_path"
                class="img_preview" alt="">
            </div>
          </div>
        </div>  
        <button type="button" class="btn btn-primary" @click="update()">save change</button>
    </div>
  </div>
</template>

<script>
export default {
    data(){
        return {
            theItem: {},
            editDataErrors: {},
        }
    },
    methods:{
        processFile(event) {
          var file = event.target.files[0] 
          this.theItem.img = file
          this.theItem.img_path = URL.createObjectURL(file);
        },
        read:function(){
          
          axios({
            url: 'http://127.0.0.1:8000/api/auth/me/',
            method: 'post',
          })
          .then(res=> {
            this.theItem =  res.data            
          })
          .catch( error => {
            localStorage.removeItem("token")
          });
      
            
        },
        update:function(){

            var fd = new FormData()
            fd.append("_method", "put");

            fd.append('name', this.theItem.name)
            fd.append('email', this.theItem.email)

            if(this.theItem.password && this.theItem.password_confirmation){
                fd.append('password', this.theItem.password)
                fd.append('password_confirmation', this.theItem.password_confirmation)
            }

            if(typeof(this.theItem.img) == "object"){
              fd.append('img', this.theItem.img)
            }
            fd.append('job_title', this.theItem.job_title)

            axios({
                    url: '/Employee/' + this.theItem.id,
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

                    this.$store.commit('setAuther', this.theItem)
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())

                }
               
            })
            .catch( error => {
               //console.log(error)
            });

        },
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