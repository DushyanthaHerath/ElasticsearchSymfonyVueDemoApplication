import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './views/Home';
import Login from "./layouts/Login";
import TopSelling from "./views/TopSelling";
import BoughtTogether from "./views/BoughtTogether";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes:[
        {path:'/home', name:'home', component:Home},
        {path:'/top_selling', name:'top_selling', component:TopSelling},
        {path:'/bought_together', name:'bought', component:BoughtTogether},
        {path:'/login', name:'login', component:Login, meta: { cleanLayout: true }},
    ]
});

export default router;
