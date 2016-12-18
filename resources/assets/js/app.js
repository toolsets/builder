//load application bootstrap file

require('./bootstrap')

Vue.component('main-nav', require('./components/MainNav.vue'));

import createRoutes from './routes/index';

const routes = [];
routes.push(createRoutes())

const router = new VueRouter({routes})

const bus = new Vue();

const globalMixinMethods = {
    fireOnBus: function(key, args = null)
    {
        console.log('fire on bus fired', key, args);
        bus.$emit(key, args);
    },

    listenOnBus: function(key, callback)
    {
        console.log('listen on bus for '+ key);
        bus.$on(key, callback);
    }
}

Vue.mixin({
    methods: globalMixinMethods
});

const app = new Vue({
    router
}).$mount('#app');

