import DatabaseRoute from './database'


const RootViewComponent = {
    template: '<router-view class="wrapper"></router-view>'
};




const createRoutes = (store) => {

    return {
        path:'/',
        component: RootViewComponent,
        children: [
            DatabaseRoute(store)
        ]
    };
}

export default createRoutes