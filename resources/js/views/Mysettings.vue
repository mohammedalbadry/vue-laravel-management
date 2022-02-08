<template>
    <div class="card card-outline card-primary">
        <!-- form start -->
        <div class="card-body">
            
            <div class="mb-3">
                <label class="form-label">name</label>
                <input
                    :class='[ errors.name ? "is-invalid" : ""]'
                    v-model="settings.name"
                    type="text" class="form-control" placeholder="name ...">
                <div class="invalid-feedback" v-if="errors.name">{{ errors.name[0] }}</div>
            </div>
            <div class="mb-3">
                <label class="form-label">description</label>
                <textarea
                    :class='[ errors.description ? "is-invalid" : ""]'
                    v-model="settings.description"
                    class="form-control" placeholder="description ..."></textarea>
                <div class="invalid-feedback" v-if="errors.description">{{ errors.description[0] }}</div>
            </div>
            <div class="mb-3">
                <div class="row">
                <div class="col-9 col-sm-10">
                    <div class="form-group">
                        <label for="exampleInputFile">logo</label>
                        <div :class='[ errors.logo ? "is-invalid" : ""]'
                            class="input-group">
                            <div class="custom-file">
                            <input 
                                @change="processFile($event, 'logo')"
                                :class='[ errors.logo ? "is-invalid" : ""]'
                                type="file" class="custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.logo">{{ errors.logo[0] }}</div>
                    </div>
                </div>
                <div class="col-3 col-sm-2">
                    <img class="img_preview" :src="settings.logo_path" alt="">
                </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                <div class="col-9 col-sm-10">
                    <div class="form-group">
                        <label for="exampleInputFile">icon</label>
                        <div :class='[ errors.icon ? "is-invalid" : ""]'
                            class="input-group">
                            <div class="custom-file">
                            <input 
                                @change="processFile($event ,'icon')"
                                :class='[ errors.icon ? "is-invalid" : ""]'
                                type="file" class="custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.icon">{{ errors.icon[0] }}</div>
                    </div>
                </div>
                <div class="col-3 col-sm-2">
                    <img class="img_preview" :src="settings.icon_path" alt="">
                </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">status</label>
                <select 
                    v-model="settings.status"
                    :class='[ errors.status ? "is-invalid" : ""]'
                    class="custom-select form-select form-select-lg" aria-label=".form-select-lg example">
                    <option value="open">open</option>
                    <option value="close">close</option>
                </select>
                <div class="invalid-feedback" v-if="errors.status">{{ errors.status[0] }}</div>
            </div>
            <div class="mb-3">
                <label class="form-label">alt text</label>
                <textarea
                    :class='[ errors.alt_text ? "is-invalid" : ""]'
                    v-model="settings.alt_text"
                    class="form-control" placeholder="alt_text ..."></textarea>
                <div class="invalid-feedback" v-if="errors.alt_text">{{ errors.alt_text[0] }}</div>
            </div>
            <button type="button" class="btn btn-primary" @click="update()">save change</button>

        </div>
        <!-- /.card-body -->


    </div>
</template>

<script>
export default {
    data(){
        return {
            settings: {
                name: "",
                description: "",
                alt_text: "",
                status: "",
                logo: "",
                logo_path: "",
                icon: "",
                icon_path: "",
            },
            errors: {},
        }
    },
    methods:{
        processFile(event, type = "add") {
          var file = event.target.files[0] 
          if(type == "logo"){
           this.settings.logo = file
           this.settings.logo_path = URL.createObjectURL(file);
          }else{
            this.settings.icon = file
            this.settings.icon_path = URL.createObjectURL(file);
          }
        },
        read:function(){
            axios({
                    url: '/setting',
                    method: 'get',
                    baseURL: 'http://127.0.0.1:8000/api/',
            })
            .then(response=> {
              this.settings.name = response.data.data.name
              this.settings.description = response.data.data.description
              this.settings.alt_text = response.data.data.alt_text
              this.settings.status = response.data.data.status
              this.settings.logo = response.data.data.logo
              this.settings.logo_path = response.data.data.logo_path
              this.settings.icon = response.data.data.icon
              this.settings.icon_path = response.data.data.icon_path
            })
            .catch( error => {
               //console.log(error)
            });


        },
        update:function(){

            var fd = new FormData()
            
            fd.append('name', this.settings.name)
            fd.append('description', this.settings.description)
            fd.append('alt_text', this.settings.alt_text)
            fd.append('status', this.settings.status)

            if(typeof(this.settings.logo) == "object"){
              fd.append('logo', this.settings.logo)
            }
            if(typeof(this.settings.icon) == "object"){
              fd.append('icon', this.settings.icon)
            }

            axios({
                    url: '/setting' ,
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,

            })
            .then(response=> {
                if(response.data.status == "error"){
                    this.errors = response.data.errors
                } else if(response.data.status == "success") {
                    this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.errors = {}

                    this.$store.commit('setSetting', this.settings)
                    document.title = this.settings.name
                    var link = document.getElementById('site_icon')
                    link.href = this.settings.icon_path
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