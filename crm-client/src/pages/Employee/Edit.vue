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
        <form class="mt-3 container container-small" @submit.prevent="employeeUpdate">
            <h3>Update Employee</h3>
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
                    <option :value="company.id" v-for="company in companies" :key="company.id" :selected="form.company_id === company.id ? true : false"
                    >
                        {{ company.name }}
                    </option>
                </select>
            </div>

    
            <button type="submit" class="btn btn-primary" :disabled="isLoading ? true : false">Update Employee</button>

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
                first_name: '',
                last_name: '',
                email: '',
                phone: ''
            },
            companies: [],
            errors: {},
            message: '',
            isLoading: false
        }
    },
    methods: {
        employeeUpdate() {
            this.isLoading = true;
            this.message = '';
            this.errors = {};
            axios.patch(`/api/employee/${this.updatedId}`, this.form).then((response) => {
                this.isLoading = false;
               // console.log(response);
                if(response.data.message) {
                    this.message = response.data.message;
                }
            }).catch((error) => {
                this.isLoading = false;
                this.errors = error.response.data.errors;
            });
        },
        getUpdatedDetail() {
            axios.get(`/api/employee/${this.updatedId}/edit`).then((response) => {
               // console.log(response);
                this.form = response.data.data[0];
            }).catch((err) =>{
                console.log(err);
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
        this.getUpdatedDetail();
        this.getCompanies();
    }
}
</script>

<style scoped>

</style>