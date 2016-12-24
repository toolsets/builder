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


// const bus = new Vue();

// const globalMixinMethods = {
//     fireOnBus: function(key, args = null)
//     {
//         console.log('fire on bus fired', key, args);
//         bus.$emit(key, args);
//     },
//
//     listenOnBus: function(key, callback)
//     {
//         console.log('listen on bus for '+ key);
//         bus.$on(key, callback);
//     }
// }

// Vue.mixin({
//     methods: globalMixinMethods
// });

const app = new Vue({
    store,
    router
}).$mount('#app');

