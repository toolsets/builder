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
                        <input type="text" class="form-control" v-model="tableName" />
                    </div>
                </div>
            </div>

            <update-form v-if="selectedItem !== null" :selected="selectedItem" :tables="getTablesList"></update-form>

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
import { mapState, mapGetters } from 'vuex'
import types from '../store/types';
import UpdateFormComponent from './UpdateFormComponent.vue'


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


        tableName : {

            set : function(newValue) {
                if(this.selectedItem !== null) {
                    setComputedValue(newValue, 'table_name', 'tableName', this.selectedItem);
                }

            },

            get: function() {
                if(this.selectedItem) {
                    return getComputedValue('table_name', 'tableName', this.selectedItem);
                }
            }

        },


    },
    
    methods: {
        submitForm: function () {
            
        },

        cancelForm: function () {
            this.$router.push({ name: 'db-table-view', params: { key: this.selectedItemOriginal.table_name} });
        },
        
        dropTable: function () {
            
        },

        ...mapGetters('database', ['getSelectedItem']),
    },

    mounted() {
         this.selectedItem = _.cloneDeep(this.getSelectedItem());
         this.selectedItemOriginal = _.cloneDeep(this.getSelectedItem);
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