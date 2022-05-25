<template>
<div v-if="showErr" class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Invalid Input! {{errMsg}}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="toggleShowErr"></button>
</div>

<div v-if="showSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>{{successMsg}}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="toggleSuccess"></button>
</div>

<form class="input">
  <img alt="Vue logo" src="../assets/logo.png">
  <h1 class="welcome">Welcome!</h1>

  <div class="mb-3 row">
    <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" v-model="inputUsername">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" v-model="inputPassword">
    </div>
  </div>

  <div class="btn-group" role="group">
    <button type="button" class="btn btn-secondary" @click="signIn">Sign in</button>
    <button type="button" class="btn btn-success" @click="signUp">Sign up</button>
  </div>
</form>
</template>

<script>
// import axios from 'axios';
import {axios_inst} from '../main.js'
import axios from 'axios';
export default {
  data() {
    return {
      inputUsername: '',
      inputPassword: '',
      regex: /^[\w_\.\-]+$/,
      showErr: false,
      showSuccess: false,
      errMsg: 'You should check in on some of those fields below.',
      successMsg: '',
    }
  },

  setup() {

  },

  mounted() {
  },

  methods: {
    signIn() {
      if(this.inputUsername && this.inputPassword && !this.invalidUsername){
        const path = '/signIn';
        const payload = {
        username: this.inputUsername,
        password: this.inputPassword,
        };
        axios_inst.post(path, payload)
          .then((res) => {
            if(res.data.res == 1){
              this.$router.push('/');
            }
            else{
              this.errMsg = res.data.msg;
              this.showSuccess = false;
              this.showErr = true;
            }
          })
          .catch((error) => {
            console.log(error);
          })
      }
      else{
        this.errMsg = 'You should check in on some of those fields below.';
        this.showErr = true;
      } 
    },

    signUp() {
      if(this.inputUsername && this.inputPassword && !this.invalidUsername){
        const path = 'http://localhost:5000/signUp';
        const payload = {
        username: this.inputUsername,
        password: this.inputPassword,
        };
        axios.post(path, payload)
          .then((res) => {
            if(res.data.res == 1){
              this.successMsg = res.data.msg;
              this.showErr = false;
              this.showSuccess = true;
              this.initForm();
            }
            else{
              this.errMsg = res.data.msg;
              this.showSuccess = false;
              this.showErr = true;
            }
          })
          .catch((error) => {
            console.log(error);
          })
      }
      else{
        this.errMsg = 'You should check in on some of those fields below.';
        this.showErr = true;
      } 
    },

    checkLogin() {
      const path = '/checkLogin';
      axios_inst.get(path)
        .then((res) => {
          if(res.data.username){
            this.$router.push({ name: "home" });
          }
        })
        .catch((error) => {
          this.$router.push({ name: "auth" });
        })
    },

    toggleShowErr() {
      this.showErr = false;
    },

    toggleSuccess() {
      this.showSuccess = false;
    },

    initForm() {
      this.inputUsername = '';
      this.inputPassword = '';
    }
  },

  computed: {
    invalidUsername() {
      if (!this.regex.test(this.inputUsername) && !this.inputUsername == "") {
        return true;
      } else return false;
    }
  }
}
</script>

<style>
.input{
    max-width: 600px;
    margin: 0 auto;
    margin-top: 100px;
    padding: 40px;
}

img{
  width: 100px;
}

.welcome{
  margin-bottom: 20px;
}
</style>
