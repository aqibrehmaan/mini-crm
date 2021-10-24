<template>
    <div>
        <Header />
        <div class="container mt-5">
             <div class="alert alert-danger" v-if="error">
                <span>{{ error }}</span>
            </div>
            <div class="alert alert-success" v-else-if="message">
                <span>{{ message }}</span>
            </div>

            <div class="mb-3">
                <router-link :to="{ name: 'company.create'}" class="btn btn-primary">Add Company</router-link>
            </div>

            <div v-if="isLoading" class="ml-5">
                <div class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
                </div>
            </div>


              <table class="table">
             
           
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Website</th>
                    <th>Edit / Delete</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="company in companiesResult" :key="company.id">
                    <td>{{ company.name }}</td>
                    <td>{{ company.email }}</td>
                    <td v-html="imageRender(company.logo)"></td>
                   
                    <td>{{ company.website }}</td>
                    <td class="d-flex justify-content-between">
                        <router-link :to="{ name: 'company.edit', params: {id: company.id} }" class="btn btn-primary">Edit</router-link> 
                    
                    <span class="btn btn-danger" @click="deleteCompany(company.id)">Delete</span></td>
                </tr>
            </tbody>
        </table>
        
        <div v-if="companiesResult.length > 0">
              <pagination :meta="meta" @pagination="fetchCompanies"> </pagination>
        </div>
        </div>
      
    </div>
</template>

<script>

import Header from '../../components/layout/Header';
import Pagination from '../../components/layout/Pagination.vue';

import axios from 'axios';

export default {
    components: {
        Header,
        Pagination
    }, 
    data() {
        return {
            companiesResult: [],
            page: 1,
            meta: {},
            message: '',
            error: '',
            isLoading: false,
        }
    },
    methods: {
        async fetchCompanies(page) {
             this.isLoading = true;
            let response = await axios.get('/api/company', {
                params: {
                    page
                }
            });
            this.isLoading = false;
            this.companiesResult = response.data.data;
            this.meta = response.data.meta
        },
        deleteCompany(companyId) {

            if(!this.$store.getters.authenticated) {
                this.$router.replace({name: 'admin.login'});
                return;
            }

            if(confirm('Are you sure you want to delete this company?')) {

                this.error = '';
                this.message = '';
                this.isLoading = true;
                axios.delete('/api/company/' + companyId).then((response) => {
                    // console.log(response.data.error);
                     this.isLoading = false;
                    if(response.data.error) {
                        this.error = response.data.error;
                    } else {
                        this.message = response.data.message;

                        let filteredResult = this.companiesResult.filter(item => {
                            return item.id !== companyId;
                        });

                        this.companiesResult = filteredResult;
                    }
                }).catch((e) => {
                    this.isLoading = false;
                    console.log( e.message + 'Sorry Something went wrong');
                });
            }
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
                imgSrc = null;
            }

            return `<td>${imgSrc}</td>`;
        }

    },
    mounted() {
        this.fetchCompanies(1);
    }
}
</script>