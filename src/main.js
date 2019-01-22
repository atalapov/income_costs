import Vue from 'vue'
import Vuex from 'vuex';
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'
import VueRouter from 'vue-router'
import App from './App.vue'
import AddOperation from './components/AddOperation.vue'
import AddSafe from './components/AddSafe.vue'
import Routes from './routes'

window.axios = require('axios');
Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.component('AddOperation', AddOperation);
Vue.component('AddSafe', AddSafe);

const router = new VueRouter({
  routes: Routes,
  mode: 'history'
}); 

new Vue({
  el: '#app',
  render: h => h(App),
  router: router
})
