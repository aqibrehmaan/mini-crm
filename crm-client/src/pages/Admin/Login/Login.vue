<template>
    <div>
    <Header />
    <div class="container container-small mt-5">
      <form @submit.prevent="loginUser">
      <h3>Login</h3>
      <div class="form-group">
        <label for="exampleInputEmail1" class="text-left">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="form.email">
        <span class="text-small text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1" class="text-left">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" v-model="form.password">
      </div>
      <button type="submit" class="btn btn-primary" :disabled="isLoading ? true : false">
        {{ isLoading ? 'Logging In' : 'Login' }}
        </button>
    </form>
  </div>
  </div>
</template>


<script>
import Header from '../../../components/layout/Header.vue';

export default {
  components: {
    Header
  },
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: {},
      isLoading: false,
    }
  },
  methods: {
    loginUser() {
        this.isLoading = true;
       this.$store.dispatch('login', this.form).then(() => {
          this.isLoading = false;
          this.$router.replace({ name: 'company.index'});

       }).catch((e) =>{
         this.isLoading = false;
         this.errors = e.response.data.errors;
       });
    },
  }
}
</script>

<style scoped>

</style>