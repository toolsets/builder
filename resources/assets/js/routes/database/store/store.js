import api from '../api';
import types from './types';

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
        [ types.GET_SELECTED_ITEM ] (state){

            return state.selectedItem;
        },

        [ types.GET_TABLES_LIST ] (state) {
            //returns sorted list of list
            return state.tables_list.sort();
        },

        [ types.GET_TABLES_DATA ] (state) {

            var list = state.list;
            var dataset = {};

            list.map(function(table)
            {
                dataset[table.table_name] = {
                    columns: Object.keys(table.columns)
                };
            });
            return dataset;
        }
    },

    mutations: {
        [ types.UPDATE_SELECTED_ITEM ] (state, item){

            var selectedItem = (item) ? _.cloneDeep(item) : null;

            var HasEnumKey = false;
            Object.keys(selectedItem.columns).map(function(key, indx) {
                if(selectedItem.columns[key].attributes.type === 'enum') {
                    HasEnumKey = true;
                }
            });

            selectedItem.hasEnumColumns = HasEnumKey;

            Vue.set(state, 'selectedItem', selectedItem);

            if(item) {
                Vue.set(state, 'selectedIndex', item.table_name);
            } else {
                Vue.set(state, 'selectedIndex', null);
            }
        },

        [ types.UPDATE_SELECTED_ITEM_INDEX ] (state, itemIndex){
            Vue.set(state, 'selectedIndex', itemIndex);
        },

        [ types.RECEIVE_TABLES ] (state, dbState){
            Vue.set(state, 'tables_list', dbState.tables_list);
            Vue.set(state, 'list', dbState.tables);
            Vue.set(state, 'database', dbState.database);
            Vue.set(state, 'connection', dbState.connection);

            if(state.selectedIndex)
            {
                var selectedItem = _.find(dbState.tables, function (tbl) {
                    return tbl.table_name === state.selectedIndex;
                });

                var HasEnumKey = false;

                if(selectedItem) {
                    Object.keys(selectedItem.columns).map(function(key, indx) {
                        if(selectedItem.columns[key].attributes.type === 'enum') {
                            HasEnumKey = true;
                        }
                    });
                }

                selectedItem.hasEnumColumns = HasEnumKey;

                Vue.set(state, 'selectedItem', selectedItem);
            }

            console.log('receiveTables');

        }
    },

    actions: {
        [ types.SELECTED_ITEM ] ({commit}, item){

            commit('updateSelectedItem', item)
        },

        [ types.SET_SELECTED_ITEM_INDEX ] ({commit}, itemIndex)
        {
            commit('updateSelectedItemIndex', itemIndex)
        },

        [ types.GET_TABLES ] ({commit}) {

            console.log('calling getAllTables from store');
            api.getAllTables().then(function(response)
            {
                console.log('response', response);
                commit('receiveTables', response.body)
            });
        },

        [ types.SUBMIT_NEW_TABLE ] ({dispatch}, payload) {

            return api.submitNewTable(payload.table).then(function(response) {

                dispatch(types.GET_TABLES);

                return response;
            });

        }
    }

}