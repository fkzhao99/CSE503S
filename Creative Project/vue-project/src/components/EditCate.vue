<template>
<div class="backdrop">
<div v-if="showErr" class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>{{errMsg}}</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="toggleShowErr"></button>
</div>
<div class="editCate">
  <button type="button" class="btn-close" aria-label="Close" @click="closeEditCate"></button>

  <div class="mb-3">
    <label for="validationDefault01" class="form-label">Choose a category</label>
    <select class="form-select" v-model="editCateName" id="validationDefault03">
      <option v-for="(cate, index) in cates" v-bind:value="cate" :key="index">{{cate}}</option>
    </select>
  </div>

  <div class="mb-3">
  <label for="validationDefault01" class="form-label">Rename the category</label>
  <input type="text" class="form-control" v-model="newCateName" id="validationDefault03">
  </div>

  <div class="common" v-show="showCommon">
    <div class="btn-group">
      <button type="button" class="btn btn-success" @click="submitEditCate">Edit</button>
      <button type="button" class="btn btn-danger" @click="confirmDelCate">Delete</button>
    </div>
  </div>

  <div class="confirm" v-show="showConfirm">
    <strong>This action will delete all the events in this category. Are you sure you want to delete them?</strong><br>
    <div class="btn-group">
    <button type="button" class="btn btn-success" @click="cancelDelCate">Cancel</button>
    <button type="button" class="btn btn-danger" @click="submitDelCate">Confirm</button>
    </div>
  </div>
  
</div>
</div>

</template>

<script>
import {axios_inst} from '../main.js'
import axios from 'axios';

export default {
  emits: ["closeEditCate", "editCateSuccess"],
  data() {
    return {
      editCateName: '',
      newCateName: '',
      errMsg: '',
      showErr: false,
      cates: '',
      showCommon: true,
      showConfirm: false,
    }
  },

  mounted() {
    this.getcates();
  },

  methods: {
    getcates() {
      const path = '/api/getCates';
      axios.get(
        path,
        { withCredentials: true }
      )
        .then((res) => {
            this.cates = res.data.catelist;
        })
        .catch((error) => {
          console.log(error);
        })
    },

    closeEditCate() {
      this.$emit('closeEditCate')
    },

    submitEditCate() {
      if(!this.editCateName) {
        this.errMsg = "Please choose a category!";
        this.showErr = true;
        return;
      }
      if(!this.newCateName) {
        this.errMsg = "Please enter a new name!";
        this.showErr = true;
        return;
      }
      const path = '/editCate';
      const payload = {
        precate: this.editCateName,
        newcate: this.newCateName,
      };
      axios_inst.post(path, payload)
        .then((res) => {
          if(res.data.res == 1){
            this.$emit('editCateSuccess');
          }
          else{
            this.showErr = true;
            this.errMsg = res.data.msg;
          }
        })
        .catch((error) => {
          console.log(error);
        })
    },

    submitDelCate() {
      if(!this.editCateName) {
        this.errMsg = "Please choose a category!";
        this.showErr = true;
        return;
      }
      const path = '/delCate';
      const payload = {
        catename: this.editCateName,
      };
      axios_inst.post(path, payload)
        .then((res) => {
          if(res.data.res == 1){
            this.$emit('editCateSuccess');
          }
        })
        .catch((error) => {
          console.log(error);
        })
    },

    toggleShowErr() {
      this.showErr = false;
    },

    confirmDelCate() {
      this.showConfirm = true;
      this.showCommon = false;
    },

    cancelDelCate() {
      this.showCommon = true;
      this.showConfirm = false;
    }
  }
}
</script>

<style scoped>
.editCate {
  width: 350px;
  padding: 20px;
  margin: 200px auto;
  background: white;
  border-radius: 10px;
}

.addCate input {
  margin: 0 auto;
  width: 250px;
}

.backdrop {
  top: 0;
  position: fixed;
  background: rgba(0, 0, 0, 0.5);
  width: 100%;
  height: 100%;
}

.btn-group {
  margin: 0 auto;
  width: 150px;
}

.btn-group button{
  width: 70px;
}
</style>