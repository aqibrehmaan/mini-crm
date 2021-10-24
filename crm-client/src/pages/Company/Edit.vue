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

        <form class="mt-3 container container-small" @submit.prevent="companyUpdate" enctype="multipart/form-data">
            <h3>Update Company</h3>
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

            <div v-html="imageRender(form.logo)"></div>
           
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
    
            <button type="submit" class="btn btn-primary" :disabled="isLoading ? true : false">Update Company</button>

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
            updatedId: this.$route.params.id,
            form: {
                name: '',
                email: '',
                logo: '',
                newLogo: '',
                website: ''
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
                    this.form.newLogo = event.target.result;
                   // console.log(event.target.result);
                }
                reader.readAsDataURL(file);
            }
          
        },
        companyUpdate() {
            this.isLoading = true;
            this.message = '';
            this.errors = {};
            axios.patch(`/api/company/${this.updatedId}`, this.form).then((response) => {
                this.isLoading = false;
                if(response.data.message) {
                    this.message = response.data.message;
                    this.form.logo = response.data.logo;
                }
            }).catch((error) => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        },
        getUpdatedDetail() {
            axios.get(`/api/company/${this.updatedId}/edit`).then((response) => {
                this.form = response.data.data[0];
            }).catch((err) =>{
                console.log(err);
            });
        },
        imageRender(companyLogo) {
            let imgSrc = '';
            if(companyLogo !== null ) {
                if(companyLogo.includes('placeholder')) {
                    imgSrc = `<img src="${companyLogo}" alt="Company Logo">`;
                } else {
                    imgSrc =  `<img src="${axios.defaults.baseURL}/storage/company/${companyLogo}" alt="Company Logo">`
                }
            } else {
                imgSrc = 'No previous image found';
            }

            return `<div>${imgSrc}</div>`;
        }
    },
    created() {
        this.getUpdatedDetail();
    }
}
</script>

<style scoped>

</style>