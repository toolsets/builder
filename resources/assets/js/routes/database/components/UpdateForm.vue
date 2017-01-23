<template>
<div class="table-form">
    <div class="list-panel">
        <div class="table-section">
            <div class="title-header">
                Update Table {{ tableName }}
            </div>

            <div class="builder-form">
                <div class="builder-form">
                    <div class="form-group input-title">
                        <label for="tableName">Table Name</label>
                        <input v-if='selectedItem' type="text" class="form-control" v-model="selectedItem.name" />
                    </div>
                </div>
            </div>

            <update-form v-if="selectedItem !== null" :selected="selectedItem" :tables="getTablesList" v-on:update="updateSelected"></update-form>

            <div class="action-footer">
                <button class="btn btn-primary" @click='submitForm' v-bind:disabled='submittable === false'>Submit</button>
                <button class="btn btn-default" @click='cancelForm'>Cancel</button>
                <button class="btn btn-danger" @click='dropTable'>Drop Table</button>
            </div>

        </div>
    </div>
</div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import types from '../store/types';
import UpdateFormComponent from './UpdateFormComponent.vue'

function makeUpdatableTableObject(selectedItem) {

    var table = {};
    var PrimaryKeyColumn = null;
    var columnRefs = {};

    if(selectedItem) {
        table.name = selectedItem.table_name;
        table.columns = Object.keys(selectedItem.columns).map(function(key) {

            var item = selectedItem.columns[key];
            var isDropped = false;
            if(item.drop !== undefined && item.drop === true) {
                isDropped = true;
            }

            var columnObj = {
                key: key,
                name: item.attributes.name,
                type: item.attributes.type,
                length: item.attributes.length,
                nullable: item.attributes.nullable,
                default: item.attributes.default,
                migrated: item.migrated,
                drop: isDropped,
                onFile: item.onFile,
                updates: {
                    change_name: null,
                    change_type: null,
                    change_length: null,
                    change_nullable: null,
                    change_default: null,
                    change_drop: null
                }
            };

            if(columnObj.primaryKey) {
                PrimaryKeyColumn = key;
            }

            columnRefs[key] = columnObj;

            return columnObj;
        });

        table.relations = selectedItem.relations;
        table.relations_added = [];

        table.updates = selectedItem.updates;
        table.hasEnumColumns = selectedItem.hasEnumColumns;
        table.updates = selectedItem.updates;

        table.indexes = selectedItem.indexes;
        table.indexes_added = [];
        table.columnRefs = columnRefs;
    }

    return table;
}

function setComputedValue(newValue, fromKey, toKey, data) {
    if(newValue !== data[fromKey]) {
        data.updates[toKey] = newValue;
    } else {
        data.updates[toKey] = null;
    }
}

function getComputedValue(fromKey, toKey, data) {
    if(data.updates[toKey]) {
        return data.updates[toKey];
    }

    if(data[fromKey]) {
        return data[fromKey];
    }
    return null;
}

export default {

     components: {
        'update-form': UpdateFormComponent
     },

     data() {

        return {
            selectedItemOriginal : null,
            selectedItem: null

        }
    },

    computed : {

        ...mapGetters('database',['getTablesList']),


        tableName : function() {
            if(this.selectedItem) {
                return getComputedValue('table_name', 'tableName', this.selectedItem);
            }
        },


    },
    
    methods: {
        submitForm: function () {

            this.storeSubmitUpdate({
                table: this.selectedItem
            }).then(function(response) {
                if(response.ok) {
                    this.$router.push({ name: 'db-table-view', params: { key: this.selectedItemOriginal.table_name} });
                }
            }.bind(this));
        },

        ...mapActions('database', {
            storeSubmitUpdate : 'UPDATE_TABLE'
        }),

        cancelForm: function () {
            this.$router.push({ name: 'db-table-view', params: { key: this.selectedItemOriginal.table_name} });
        },
        
        dropTable: function () {
            
        },

        updateSelected: function (data) {
          this.selectedItem = data;
        },

        ...mapGetters('database', ['getSelectedItem']),
    },

    mounted() {
         this.selectedItem = makeUpdatableTableObject(Object.assign({}, this.getSelectedItem()));
         this.selectedItemOriginal = _.cloneDeep(this.getSelectedItem());
    }
}
</script>
<style lang="sass">
.toolbar {
    padding: 8px;
    background-color: #ebeaee;
    margin-bottom: 8px;

    .btn {
        font-size: .8em;
    }

}

.action-footer {
    margin:8px;
}

.table {
    font-size: 1em;
    background-color: #FFF;
    margin-bottom: 0px;

    .tbl-status {
        width: 10px;
        padding: 0;
    }

    td.migrated {
        background-color: #3f964e;
    }

    td.not-migrated {
        background-color: #c9a900;
    }

    tr.drop-column {

        td.migrated {
            background-color: #cc1214;
        }

        td.not-migrated {
            background-color: #cc1214;
        }

        td {
            color: #cc1214;
            text-decoration: line-through;
        }

    }
}

</style>