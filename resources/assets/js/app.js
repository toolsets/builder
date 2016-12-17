//load application bootstrap file
require('./bootstrap')



Vue.component('main-nav', require('./components/MainNav.vue'));
Vue.component('list-view-layout', require('./layouts/ListViewLayout.vue'));


import DatabaseComponent from './components/Database/Database.vue';

const ModelsComponent = { template: '<div>Models</div>' }


const routes = [
    {
        path: '/database/:tablename?', component: DatabaseComponent
    },
    {
        path: '/models/:modelname?', component: ModelsComponent
    }
]


const router = new VueRouter({
    routes
})


const app = new Vue({
    router,
    el: '#app',
    data: {
        message: 'Hello Vue!'
    }
});

console.log('app loaded');