//load application bootstrap file
require('./bootstrap');

import createStore from './store/createStore';
import { sync } from 'vuex-router-sync';

Vue.component('main-nav', require('./components/MainNav.vue'));

import createRoutes from './routes/index';

const store = createStore({
    // initial state here
});



const routes = [];
routes.push(createRoutes(store))

const router = new VueRouter({routes})
sync(store, router);


const app = new Vue({
    store,
    router
}).$mount('#app');

