<template>
<div class="backdrop">
<form class="row g-3 addEvent" @submit="submitAddEvent">
  <div class="closebtn">
  <button type="button" class="btn-close" aria-label="Close" @click="closeAddEvent"></button>
  </div>

  <div class="mb-3">
    <label for="validationDefault01" class="form-label">Content</label>
    <input type="text" class="form-control" v-model="addEventForm.content" id="validationDefault01" required>
  </div>

  <div class="col-md-6">
    <label for="validationDefault02" class="form-label">Date</label>
    <input type="date" class="form-control" v-model="addEventForm.date" id="validationDefault02" required>
  </div>

  <div class="col-md-6">
    <label for="validationDefault03" class="form-label">Category</label>
    <select class="form-select" v-model="addEventForm.cate" id="validationDefault03">
      <option></option>
      <option v-for="(category, index) in cates" v-bind:value="category" :key="index">{{category}}</option>
    </select>
  </div>

  <div class="col-12">
    <button class="btn btn-success" type="submit">Create Event</button>
  </div>
</form>
</div>


</template>

<script>
import {axios_inst} from '../main.js'
import axios from 'axios';

export default {
  emits: ["closeAddEvent", "addEventSuccess"],

  data() {
    return {
      addEventForm: {
        content: '',
        date: '',
        cate: '',
      },
      cates: '',
    }
  },

  mounted() {
    this.getAllCates();
  },

  methods: {
    getAllCates() {
      const path = '/api/getAllCates';
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

    closeAddEvent() {
      this.$emit('closeAddEvent')
    },

    initForm() {
      this.addEventForm.content = '';
      this.addEventForm.date = '';
      this.addEventForm.cate = ''
    },

    submitAddEvent() {
      const path = '/addEvent';
      const payload = this.addEventForm;
      axios_inst.post(path, payload)
        .then((res) => {
          if(res.data.res == 1){
            this.$emit('addEventSuccess');
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
  }
}
</script>

<style scoped>
.addEvent {
  width: 450px;
  padding: 20px;
  margin: 200px auto;
  background: white;
  border-radius: 10px;
}

.backdrop {
  top: 0;
  position: fixed;
  background: rgba(0, 0, 0, 0.5);
  width: 100%;
  height: 100%;
}

.closebtn{
  padding-left: 370px;
  width: 50px;
}
</style>