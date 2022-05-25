<template>
  <div class="logoutBtn">
      <button type="button" class="btn btn-success" @click="logOut">Log Out</button>
  </div>

  <div class="profileBtn">
      <button type="button" class="btn btn-success" @click="profile">Edit Profile</button>
  </div>

  <div class="home">

    <img alt="Vue logo" src="../assets/logo.png">
    <h1>Your Deadline Reminder</h1>
  </div>
  <Cates @addCate="toggleAddCate" @editCate="toggleEditCate" :curUser="curUser"/>

  <div v-if="showAddCate">
    <AddCate @closeAddCate="toggleAddCate" @addCateSuccess="addCateSuccess"/>
  </div>

  <div v-if="showEditCate">
    <EditCate @closeEditCate="toggleEditCate" @editCateSuccess="editCateSuccess"/>
  </div>

  <div v-if="showAddEvent">
    <AddEvent @closeAddEvent="toggleAddEvent" @addEventSuccess="addEventSuccess"/>
  </div>

  <div class="addIcon">
    <button type="button" class="btn btn-outline-dark text-nowrap" @click="toggleAddEvent">Create Event</button>
  </div>
</template>

<script>
import Cates from '../components/Cates.vue'
import AddEvent from '../components/AddEvent.vue'
import AddCate from '@/components/AddCate.vue'
import EditCate from '@/components/EditCate.vue'
import axios from 'axios';
import { axios_inst } from '@/main';
// import AddCate from '../components/AddCate.vue'
// @ is an alias to /src

export default {
  name: 'HomeView',
  
  components: {
    Cates,
    AddEvent,
    AddCate,
    EditCate
},

  mounted() {
    this.checkLogin();
  },

  data() {
    return {
      showAddEvent: false,
      showAddCate: false,
      showEditCate: false,
      curUser: '',
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

    toggleAddEvent() {
      this.showAddEvent = !this.showAddEvent;
    },

    toggleAddCate() {
      this.showAddCate = !this.showAddCate;
    },

    toggleEditCate() {
      this.showEditCate = !this.showEditCate;
    },

    logOut() {
      const path = '/api/logOut';
      axios.get(path)
        .then(() => {
          this.$router.push('/auth');
        })
        .catch((error) => {
          console.log(error);
        })
    },

    addCateSuccess() {
      this.showAddCate = false;
      location.reload();
    },

    editCateSuccess() {
      this.showEditCate = false;
      location.reload();
    },

    addEventSuccess() {
      this.showAddEvent = false;
      location.reload();
    },

    profile() {
      this.$router.push('/profile');
    }
  }
}
</script>

<style scoped>
img{
  width: 100px;
}

.addIcon{
  width: 105px;
  position: fixed;
  right: 100px;
  bottom: 30px;
}

.logoutBtn{
  position: fixed;
  top:1%;
  left: 1%;
}

.profileBtn {
  position: fixed;
  top:1%;
  right: 1%;
}
</style>
