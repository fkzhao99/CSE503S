<template>
<div v-if="showErr" class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Invalid Input! {{errMsg}}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="toggleShowErr"></button>
</div>

<form class="input">
  <img alt="Vue logo" src="../assets/logo.png">
  <h1 class="welcome">Edit your profile</h1>

  <div class="mb-3 row">
    <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" v-model="curUser">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Old Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" v-model="curPassword">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="inputPassword" class="col-sm-2 col-form-label">New Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" v-model="newPassword">
    </div>
  </div>

  <div class="btn-group" role="group">
    <button type="button" class="btn btn-secondary" @click="cancel">Cancel</button>
    <button type="button" class="btn btn-success" @click="editProfile">Edit</button>
  </div>
</form>
</template>

<script>
import {axios_inst} from '../main.js'
import axios from 'axios';

export default {
  data() {
    return {
      curUser: '',
      curPassword: '',
      newPassword: '',
      regex: /^[\w_\.\-]+$/,
      showErr: false,
    }
  },

  mounted() {
    this.checkLogin();
  },

  computed: {
    invalidUsername() {
      if (!this.regex.test(this.curUser) && !this.curUser == "") {
        return true;
      } else return false;
    }
  },

  methods: {
    checkLogin() {
      const path = '/checkLogin';
      axios_inst.get(path)
        .then((res) => {
          if(res.data.username){
            this.curUser = res.data.username;
          }
        })
        .catch((error) => {
          this.$router.push({ name: "auth" });
        })
    },

    cancel() {
      this.$router.push('/');
    },

    logOut() {
      const path = '/api/logOut';
      axios.get(path)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          console.log(error);
        })
    },

    editProfile() {
      if(this.curUser && this.newPassword && this.curPassword && !this.invalidUsername){
        const path = '/editProfile';
        const payload = {
        username: this.curUser,
        oldPassword: this.curPassword,
        newPassword: this.newPassword,
        };
        console.log(payload);
        axios_inst.post(path, payload)
          .then((res) => {
            if(res.data.res == 1){
              this.logOut();
            }
            else{
              this.errMsg = res.data.msg;
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
    }
  }
}


</script>