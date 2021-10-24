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
                <router-link :to="{ name: 'employee.create'}" class="btn btn-primary">Add Employee</router-link>
            </div>

            <div v-if="isLoading" class="ml-5">
                <div class="spinner-grow" role="status">
                <span class="sr-only">Loading...</span>
                </div>
            </div>

            <table class="table">
            
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Edit / Delete</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="employee in employeesResult" :key="employee.id">
                    <td>{{ employee.first_name }}</td>
                    <td>{{ employee.last_name }}</td>
                    <td>{{ employee.email}}</td>
                    <td>{{ employee.phone }}</td>
                    <td class="d-flex justify-content-between">
                        <router-link :to="{ name: 'employee.edit', params: {id: employee.id} }" class="btn btn-primary">Edit</router-link> 
                    
                    <span class="btn btn-danger" @click="deleteEmployee(employee.id)">Delete</span></td>
                </tr>
            </tbody>
        </table>

            <div v-if="employeesResult.length > 0">
                 <pagination :meta="meta" @pagination="fetchEmployees"> </pagination>
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
            employeesResult: [],
            page: 1,
            meta: {},
            message: '',
            error: '',
            isLoading: false,
        }
    },
    methods: {
        async fetchEmployees(page) {
            this.isLoading = true;
            let response = await axios.get('/api/employee', {
                params: {
                    page
                }
            });
            this.isLoading = false;
            this.employeesResult = response.data.data;
            this.meta = response.data.meta
        },
        deleteEmployee(employeeId) {
            if(confirm('Are you sure you want to delete this employee?')) {

                this.error = '';
                this.message = '';
                this.isLoading = true;
                axios.delete('/api/employee/' + employeeId).then((response) => {
                     this.isLoading = false;
                    // console.log(response.data.error);
                    if(response.data.error) {
                        this.error = response.data.error;
                    } else {
                        this.message = response.data.message;

                        let filteredResult = this.employeesResult.filter(item => {
                            return item.id !== employeeId;
                        });

                        this.employeesResult = filteredResult;
                    }
                }).catch((e) => {
                    this.isLoading = false;
                    console.log( e.message + 'Sorry Something went wrong');
                });
            }
        },

    },
    mounted() {
        this.fetchEmployees(1);
    }
}
</script>