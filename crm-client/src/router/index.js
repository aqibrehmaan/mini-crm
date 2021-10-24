import { createRouter, createWebHistory } from 'vue-router';

import store from '../store';

import Company from '../pages/Company/index.vue';
import CompanyCreate from '../pages/Company/Create.vue';
import CompanyEdit from '../pages/Company/Edit.vue';

import Employee from '../pages/Employee/index.vue';
import EmployeeCreate from '../pages/Employee/Create.vue';
import EmployeeEdit from '../pages/Employee/Edit.vue';

import Login from '../pages/Admin/Login/Login.vue';

const routes = [
    {
        path: '/',
        name: 'company.index',
        component: Company
    },
    {
        path: '/company/create',
        name: 'company.create',
        component: CompanyCreate,
        beforeEnter: (to, from, next) => {

            if(!store.getters.authenticated) {
                return next({ name: 'admin.login' });
            }

            return next();

        }
    },
    {
        path: '/company/edit/:id',
        props: true,
        name: 'company.edit',
        component: CompanyEdit,
        beforeEnter: (to, from, next) => {

            if(!store.getters.authenticated) {
                return next({ name: 'admin.login' });
            }

            return next();

        }
    },
    {
        path: '/employee',
        name: 'employee.index',
        component: Employee
    },
    {
        path: '/employee/create',
        name: 'employee.create',
        component: EmployeeCreate,
        beforeEnter: (to, from, next) => {

            if(!store.getters.authenticated) {
                return next({ name: 'admin.login' });
            }

            return next();

        }
    },
    {
        path: '/employee/edit/:id',
        props: true,
        name: 'employee.edit',
        component: EmployeeEdit,
        beforeEnter: (to, from, next) => {

            if(!store.getters.authenticated) {
                return next({ name: 'admin.login' });
            }

            return next();

        }
    },
    {
        path: '/admin/login',
        name: 'admin.login',
        component: Login,
        beforeEnter: (to, from, next) => {

            if(store.getters.authenticated) {
                return next({ name: 'company.index' });
            }
            return next();
        }
    }

];

export default createRouter({

    history: createWebHistory(),
    routes
});