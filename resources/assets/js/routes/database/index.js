import ListViewLayout from '../../layouts/ListViewLayout.vue'

const DbTables = resolve => {
    require.ensure(['./components/DbTables.vue'], () => {
        resolve(require('./components/DbTables.vue'))
    })
}

const TableForm = resolve => {
    require.ensure(['./components/TableForm.vue'], () => {
        resolve(require('./components/TableForm.vue'))
    })
}

const EmptyFormComponent = { template: '<h1>Empty Form</h1>'}
const CreateFormComponent = { template: '<h1>Create Form</h1>'}

export default (store) => {

    var tables = [
        {
            id : 1,
            name: 'X-ABC',
            details: 'ABC details'
        },

        {
            id : 2,
            name: 'X-DEF',
            details: 'DEF details'
        },

        {
            id : 3,
            name: 'X-GHI',
            details: 'GHI details'
        },

        {
            id : 4,
            name: 'X-JKL',
            details: 'JKL details'
        }
    ];

    store.registerModule('database', {
        namespaced: true,

        state: {
            list: tables,
            selectedIndex: null,
            selectedItem:null
        },

        getters: {
            selectedItem(state){
                return state.selectedItem;
            }
        },

        mutations: {
            selectedItem(state, key){

                console.log('mutation')
                console.log({state, key})
                var item = _.find(state.list, function(o)
                {
                    return o.id == key
                });

                var selectedItem = (item) ? item : null
                Vue.set(state, 'selectedItem', selectedItem);

                if(item)
                {
                    Vue.set(state, 'selectedIndex', key);
                }
                else
                {
                    Vue.set(state, 'selectedIndex', null);
                }
            }
        },

        actions: {
            selectedItem({commit}, item){
                console.log('action SelectedItem ', item);
                commit('selectedItem', item.key)
            }
        }

    });

    return {
        path: 'database',
        components: {
            default: ListViewLayout,
        },
        children: [
            {
                path: '/',
                components: {
                    list: DbTables,
                    form: EmptyFormComponent
                }
            },

            {
                path: 'create',
                components: {
                    list: DbTables,
                    form: CreateFormComponent
                }
            },

            {
                path: ':key',
                components: {
                    list: DbTables,
                    form: TableForm
                }
            }

        ]
    };
}
