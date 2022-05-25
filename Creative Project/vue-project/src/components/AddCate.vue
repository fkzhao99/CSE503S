<template>
<div class="backdrop">
<div v-if="showErr" class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>{{errMsg}}</strong>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="toggleShowErr"></button>
</div>

<div class="addCate">
  <button type="button" class="btn-close" aria-label="Close" @click="closeAddCate"></button>

  <div class="mb-3">
    <label for="validationDefault01" class="form-label">Category Name</label>
    <input type="text" class="form-control" v-model="addCateName" rows="3" id="validationDefault01" required>
  </div>

  <div class="col-12">
    <button class="btn btn-success" type="submit" @click="submitAddCate">Create Category</button>
  </div>
  
</div>
</div>
</template>

<script>
import {axios_inst} from '../main.js'
import axios from 'axios';

export default {
  emits: ["closeAddCate", "addCateSuccess"],
  data() {
    return {
      addCateName: '',
      errMsg: '',
      showErr: false,
    }
  },
  methods: {
    closeAddCate() {
      this.$emit('closeAddCate')
    },

    submitAddCate() {
      if(!this.addCateName) {
        this.errMsg = "Category name must not be empty!";
        this.showErr = true;
        return;
      }
      const path = '/addCate';
      const payload = {
        catename: this.addCateName,
      };
      axios_inst.post(path, payload)
        .then((res) => {
          if(res.data.res == 1){
            this.$emit('addCateSuccess');
          }
          else{
            this.errMsg = res.data.msg;
            this.showErr = true;
          }
        })
        .catch((error) => {
          console.log(error);
        })
    },

    toggleShowErr() {
      this.showErr = false;
    }
  }
}
</script>

<style scoped>
.addCate {
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
</style>