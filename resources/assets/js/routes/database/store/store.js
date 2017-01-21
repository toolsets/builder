import api from '../api';
import types from './types';
import { makeTableColumn } from '../blueprint.js'

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

            var tables = dbState.tables;
            if(tables.length > 0) {
                tables = dbState.tables.map(function(table) {
                    var HasEnumKey = false;
                    var PrimaryKeyColumn = null;
                    //iterates over each table to prepare the table item state
                    if(table.columns) {
                        Object.keys(table.columns).map(function(colKey) {
                            var column = table.columns[colKey];
                            column = makeTableColumn(column);

                            if(column.attributes.primaryKey) {
                                PrimaryKeyColumn = {
                                    name: column.attributes.name
                                }
                            }

                            if(HasEnumKey == false && column.attributes.type === 'enum') {
                                HasEnumKey = true;
                            }

                            table.columns[colKey] = column;
                        });
                    }

                    table.hasEnumColumns = HasEnumKey;
                    table.primaryKeyColumn = PrimaryKeyColumn;

                    // create an empty state for updates
                    table.updates = {
                        tableName: null,
                        tableDrop: false,
                    };

                    return table;

                }.bind(this));
            }

            Vue.set(state, 'list', tables);
            Vue.set(state, 'database', dbState.database);
            Vue.set(state, 'connection', dbState.connection);

            if(state.selectedIndex)
            {
                var selectedItem = _.find(dbState.tables, function (tbl) {
                    return tbl.table_name === state.selectedIndex;
                });

                Vue.set(state, 'selectedItem', selectedItem);
            }

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

            api.getAllTables().then(function(response)
            {
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