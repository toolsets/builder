import DatabaseRoute from './database'


const RootViewComponent = {
    template: '<router-view class="wrapper"></router-view>'
};

const store = {};


const createRoutes = () => {

    return {
        path:'/',
        component: RootViewComponent,
        children: [
            DatabaseRoute(store)
        ]
    };
}

export default createRoutes