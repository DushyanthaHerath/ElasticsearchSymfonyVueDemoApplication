import Vue from 'vue';
import Vuetify from './plugins/vuetify'
import Routes from './routes.js';
import App from './layouts/App';
import '../css/style.scss';
import axios from 'axios'
import VueAxios from 'vue-axios'
import Vuex from 'vuex'
import store from './store'

Vue.use(Vuex);

Vue.use(VueAxios, axios);

Vue.use(Vuetify);

Vue.use(require('vue-moment'));
window.moment = require('moment');

window.Vue = Vue;
window.axios = axios;

const app = new Vue({
    el: '#app',
    store,
    router: Routes,
    vuetify : Vuetify,
    render: h => h(App),
});

export default app;
