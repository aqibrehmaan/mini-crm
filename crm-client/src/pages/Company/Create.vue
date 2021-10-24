<template>
    <div>
        <Header />

        <div class="alert alert-success container container-small" v-if="message">
            {{ message }}
        </div>

        
        <div v-if="isLoading" class="container container-small">
                <div class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
                </div>
        </div>

        <form class="mt-3 container container-small" @submit.prevent="companyDetail" enctype="multipart/form-data">
            <h3>Create Company</h3>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" v-model="form.name">
                <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="form.email">
                <small class="text-danger" v-if="errors.email">{{ errors.email[0]}}</small>
            </div>

                <div v-if="form.photo">
                     <img :src="form.photo" alt="Company Logo" height="100" width="100">
                </div>
              
           <div class="form-group">
             
                <label for="exampleFormControlFile1">Company Logo</label>
                <input type="file" class="form-control-file" id="exampleFormControlFile1"  @change="onFileSelected">
                <small class="text-danger" v-if="errors.logo">{{ errors.logo[0] }}</small>
            </div>

             <div class="form-group">
                <label for="website">Website</label>
                <input type="text" class="form-control" id="website" v-model="form.website">
                <small class="text-danger" v-if="errors.website">{{ errors.website[0]}}</small>
            </div>
    
            <button type="submit" class="btn btn-primary" :disabled="isLoading ? true : false">Create Company</button>

        </form>
    </div>
</template>

<script>

import Header from '../../components/layout/Header';

import axios from 'axios';

export default {
    components: {
        Header,
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                photo: null,
                website: null
            }, 
            errors: {},
            message: '',
            isLoading: false,
        }
    },
    methods: {
        onFileSelected(event) {
            let file = event.target.files[0];
            console.log(file);
            if(file.size > 1048770) {
               alert('Image file size is large');
            } else {
                let reader = new FileReader();

                reader.onload = event => {
                    this.form.photo = event.target.result;
                   // console.log(event.target.result);
                }
                reader.readAsDataURL(file);
            }
          
        },
        companyDetail() {
            this.isLoading = true;
            this.message = '';
            this.errors = {};
            axios.post('/api/company', this.form).then((response) => {
                this.isLoading = false;
                    if(response.data.message) {
                      this.message = response.data.message;
                    }
            }).catch((error) => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>

<style scoped>

</style>