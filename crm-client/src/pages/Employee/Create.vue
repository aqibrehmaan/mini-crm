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

        <form class="mt-3 container container-small" @submit.prevent="employeeInsert">
            <h3>Create Employee</h3>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" v-model="form.first_name">
                <small class="text-danger" v-if="errors.first_name">{{ errors.first_name[0] }}</small>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" v-model="form.last_name">
                <small class="text-danger" v-if="errors.last_name">{{ errors.last_name[0] }}</small>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="form.email">
                <small class="text-danger" v-if="errors.email">{{ errors.email[0]}}</small>
            </div>

             <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" v-model="form.phone">
                <small class="text-danger" v-if="errors.phone">{{ errors.phone[0]}}</small>
            </div>

            <div class="form-group">
                <label for="company">Company</label>
                <select class="form-control" id="company" v-model="form.company_id">
                   <option disabled selected value>Select Company</option>
                    <option :value="company.id" v-for="company in companies" :key="company.id"
                    >
                        {{ company.name }}
                    </option>
                </select>
            </div>

    
            <button type="submit" class="btn btn-primary" :disabled="isLoading ? true : false">Create Employee</button>

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
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                company_id: ''
            },
            companies: [],
            errors: {},
            message: '',
            isLoading: false,
        }
    },
    methods: {
        employeeInsert() {
            this.isLoading = true;
            this.message = '';
            this.errors = {};
            axios.post(`/api/employee/`, this.form).then((response) => {
               // console.log(response);
                this.isLoading = false;
                if(response.data.message) {
                    this.message = response.data.message;
                }
            }).catch((error) => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        },
        async getCompanies() {
            await axios.get(`/api/companies/`).then((response) => {
                // console.log(response);
                this.companies = response.data.data;
            }).catch((err) =>{
                console.log(err);
            });
        }
    },
    created() {
        this.getCompanies();
    }
}
</script>

<style scoped>

</style>