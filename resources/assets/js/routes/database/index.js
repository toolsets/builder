import ListViewLayout from '../../layouts/ListViewLayout.vue'
import UiSplitViewController from '../../layouts/UiSplitViewController.vue'

import api from './api';

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

    var tables = [];

    store.registerModule('database', {
        namespaced: true,

        state: {
            list: tables,
            database: null,
            connection: null,
            selectedIndex: null,
            selectedItem:null
        },

        getters: {
            getSelectedItem(state){
                return state.selectedItem;
            }
        },

        mutations: {
            selectedItem(state, key){

                console.log('mutation')
                console.log(state, key)
                var item = _.find(state.list, function(o)
                {
                    return o.table_name == key
                });

                var selectedItem = (item) ? _.cloneDeep(item) : null;

                Vue.set(state, 'selectedItem', selectedItem);

                if(item)
                {
                    Vue.set(state, 'selectedIndex', key);
                }
                else
                {
                    Vue.set(state, 'selectedIndex', null);
                }
            },

            receiveTables(state, dbState){
                Vue.set(state, 'list', dbState.tables);
                Vue.set(state, 'database', dbState.database);
                Vue.set(state, 'connection', dbState.connection);
            }
        },

        actions: {
            selectedItem({commit}, itemIndex){
                console.log('action SelectedItem ', itemIndex);
                commit('selectedItem', itemIndex)
            },

            getTables({commit}) {

                api.getAllTables().then(function(response)
                {
                    console.log('response', response);
                    commit('receiveTables', response.body)
                });

            }
        }

    });

    return {
        path: 'database',
        components: {
            default: UiSplitViewController,
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
