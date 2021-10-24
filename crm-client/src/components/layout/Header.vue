<template>
   
    <header class="d-flex justify-content-between">
        <div class="container d-flex justify-content-between">
        <div class="logo">
            <h3>Mini Crm</h3>
        </div>
        <nav>
            <ul>
                <li>
                    <router-link :to="{ name: 'company.index' }">
                        Company
                    </router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'employee.index' }">
                        Employee
                    </router-link>
                </li>

                <li v-if="!this.$store.getters.authenticated">
                    <router-link :to="{ name: 'admin.login' }">
                        Login
                    </router-link>
                </li>

                <li v-if="this.$store.getters.authenticated">
                    <a href="#" @click.prevent="logout">
                        Logout
                    </a>
                </li>

            </ul>
        </nav>
        </div>
    </header>
    
</template>

<script>
export default {
    methods: {
        logout() {
            this.$store.dispatch('logout');
            this.$router.replace({ name: 'company.index'});
        }
    }
}
</script>

<style scoped>
    header {
       
        padding: 1rem 1rem;
        background-color: #fff;
        box-shadow: 1px 1px 10px 1px rgb(0 0 0 / 10%);
    }
    nav ul {
        display: flex;
        list-style: none;
    }

    nav ul li {
        margin-right: 1rem;
    }
</style>