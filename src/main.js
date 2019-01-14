import Vue from 'vue'
import Vuex from 'vuex';
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'
import App from './App.vue'
import AddOperation from './components/AddOperation.vue'

window.axios = require('axios');
Vue.use(Vuex);
Vue.use(Vuetify);
Vue.component('AddOperation', AddOperation);



new Vue({
  el: '#app',
  render: h => h(App)
})
