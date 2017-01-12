import api from './api';

var tables = [];

export default {

    namespaced: true,

    state: {
        list: tables,
        tables_list: [],
        database: null,
        connection: null,
        selectedIndex: null,
        selectedItem:null
    },

    getters: {
        getSelectedItem(state){
            return state.selectedItem;
        },

        getTablesList(state) {
            //returns sorted list of list
            return state.tables_list.sort();
        }
    },

    mutations: {
        updateSelectedItem(state, item){

            var selectedItem = (item) ? _.cloneDeep(item) : null;

            Vue.set(state, 'selectedItem', selectedItem);

            if(item) {
                Vue.set(state, 'selectedIndex', item.table_name);
            } else {
                Vue.set(state, 'selectedIndex', null);
            }
        },

        updateSelectedItemIndex(state, itemIndex)
        {
            Vue.set(state, 'selectedIndex', itemIndex);
        },

        receiveTables(state, dbState){
            Vue.set(state, 'tables_list', dbState.tables_list);
            Vue.set(state, 'list', dbState.tables);
            Vue.set(state, 'database', dbState.database);
            Vue.set(state, 'connection', dbState.connection);

            if(state.selectedIndex)
            {
                var selectedItem = _.find(dbState.tables, function (tbl) {
                    return tbl.table_name === state.selectedIndex;
                });

                Vue.set(state, 'selectedItem', selectedItem);
            }

            console.log('receiveTables');

        }
    },

    actions: {
        selectedItem({commit}, item){

            commit('updateSelectedItem', item)
        },

        setSelectedItemIndex({commit}, itemIndex)
        {
            commit('updateSelectedItemIndex', itemIndex)
        },

        getTables({commit}) {

            console.log('calling getAllTables from store');
            api.getAllTables().then(function(response)
            {
                console.log('response', response);
                commit('receiveTables', response.body)
            });

        }
    }

}