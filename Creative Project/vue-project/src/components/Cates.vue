<template>
<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
      <a class="navbar-brand">Category</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#" @click="setCate">All</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="setCate">Work</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="setCate">Study</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="setCate">Play</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="setCate">Sport</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Others
            </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <div class="catesAdded" v-for="(cate, index) in cates" :key="index">
                <li><a class="dropdown-item" href="#" @click="setCate">{{cate}}</a></li>
              </div>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#" @click="addCate">Add a category</a></li>
              <li><a class="dropdown-item" href="#" @click="editCate">Edit a category</a></li>
          </ul>
          </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" v-model="keyword" placeholder="Keyword" aria-label="Search">
      </form>
      </div>
  </div>
  </nav>

  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped table-hover">
        <caption class="caption-top">
          <strong>Category: {{curCate}}</strong>
        </caption>
        <caption>Today is: {{curDate}}</caption>
        <thead>
          <tr>
            <th scope="col">Content</th>
            <th scope="col">Date</th>
            <th scope="col">Category</th>
            <th scope="col">Done?</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(event, index) in events" :key="index" 
          v-show="(!event.done || showDone) 
          && (curCate=='All' || curCate==event.cate) 
          && ((Date.parse(event.date) > Date.parse(curDate)-30000000 || Date.parse(event.date) == Date.parse(curDate)-30000000) || showHistory==true)
          && (event.content.toLowerCase().indexOf(keyword.toLowerCase()) != -1)">
            <td>{{ event.content }}</td>
            <td>{{ event.date }}</td>
            <td>{{ event.cate }}</td>
            <td>
              <span v-if="event.done">Yes</span>
              <span v-else>No</span>
            </td>
            <td>
              <button type="button" class="btn btn-warning btn-sm" @click="editEvent(event)">Update</button>
              <button type="button" class="btn btn-danger btn-sm" @click="delEvent(event)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<input type="checkbox" class="btn-check" id="btn-check" autocomplete="off">
<label class="btn btn-outline-dark showDoneBtn text-nowrap" for="btn-check" @click="toggleShowDone">Hide Done Events</label>

<input type="checkbox" class="btn-check" id="btn-check1" autocomplete="off">
<label class="btn btn-outline-dark showHistoryBtn text-nowrap" for="btn-check1" @click="toggleHistory">Show History Events</label>

<div class="backdrop" v-if="showEdit">
  <form class="row g-3 editEvent" @submit="submitEditEvent">
    <button type="button" class="btn-close" aria-label="Close" @click="closeEditEvent"></button>

    <div class="mb-3">
      <label for="validationDefault01" class="form-label">Content</label>
      <input type="text" class="form-control" v-model="editEventForm.content" id="validationDefault01" required>
    </div>

    <div class="col-md-6">
      <label for="validationDefault02" class="form-label">Date</label>
      <input type="date" class="form-control" v-model="editEventForm.date" id="validationDefault02" required>
    </div>

    <div class="col-md-6">
      <label for="validationDefault03" class="form-label">Category</label>
      <select class="form-select" v-model="editEventForm.cate" id="validationDefault03">
        <option></option>
        <option v-for="(category, index) in allcates" v-bind:value="category" :key="index">{{category}}</option>
      </select>
    </div>

    <div class="formcheck">
      <input type="checkbox" v-model="editEventForm.isDone">
      <label class="form-check-label" for="flexCheckChecked">
      Done
    </label>
    </div>

    <div class="col-12">
      <button class="btn btn-success" type="submit">Update Event</button>
    </div>
  </form>
</div>
</template>

<script>
import axios from 'axios';
import { axios_inst } from '@/main';

export default {
  emits: ["addCate", "editCate"],
  props: ["curUser"],

  setup() {
    
  },

  data() {
    return {
      msg: '',
      curCate: 'All',
      events: '',
      curDate: new Date().toLocaleDateString(),
      showDone: true,
      showHistory: false,
      keyword: '',

      showEdit: false,
      editEventForm: {
        content: '',
        date: '',
        cate: '',
        uuid: '',
        isDone: ''
      },

      cates: '',
      allcates: '',
    };
  },

  mounted() {
    this.getCates();
    this.getEvents();
    this.getAllCates();
  },

  methods: {
    getCates() {
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

    getAllCates() {
      const path = '/api/getAllCates';
      axios.get(
        path,
        { withCredentials: true }
      )
        .then((res) => {
            this.allcates = res.data.catelist;
        })
        .catch((error) => {
          console.log(error);
        })
    },

    getEvents() {
      const path = '/api/getEvents';
      axios.get(
        path,
        { withCredentials: true }
      )
        .then((res) => {
            this.events = res.data.eventlist;
        })
        .catch((error) => {
          console.log(error);
        })
    },

    laterThanToday(dbdate) {
      const reg =/(\d{4})\-(\d{2})\-(\d{2})/;
      let newdate = dbdate.replace(reg,"$1/$2/$3");
    },

    addCate() {
      this.$emit('addCate');
    },

    editCate() {
      this.$emit('editCate');
    },

    setCate(e) {
      this.curCate = e.target.innerHTML;
    },

    toggleShowDone() {
      this.showDone = !this.showDone;
    },

    toggleHistory() {
      this.showHistory = !this.showHistory;
    },

    clearKeyword() {
      this.keyword = '';
    },

    editEvent(event) {
      console.log(event.done);
      this.showEdit = true;
      this.editEventForm.content = event.content;
      this.editEventForm.date = event.date;
      this.editEventForm.cate = event.cate;
      this.editEventForm.uuid = event.uuid;
      this.editEventForm.isDone = event.done;
    },

    closeEditEvent() {
      this.showEdit = false;
    },

    submitEditEvent() {
      const path = '/editEvent';
      const payload = this.editEventForm;
      axios_inst.post(path, payload)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          console.log(error);
        })
    },
    
    delEvent(event) {
      const path = '/delEvent';
      const payload = {
        'uuid': event.uuid};
      axios_inst.post(path, payload)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          console.log(error);
        }) 
    }
  },
};
</script>

<style scoped>
th {
  width: 120px;
}

.showHistoryBtn {
  position: fixed;
  width: 170px;
  left: 20px;
  bottom: 80px;
}

.showDoneBtn {
  position: fixed;
  width: 170px;
  left: 20px;
  bottom: 30px;
}

.editEvent {
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
</style>