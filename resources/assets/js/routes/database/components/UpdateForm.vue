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

            <input-columns :selected="selectedItem" :tables="getTablesList"></input-columns>

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
        'input-columns': UpdateFormComponent
     },

     data() {

        return {

        }
    },

    computed : {

        ...mapState('database', ['selectedItem']),

        ...mapGetters('database',['getTablesList']),

        tableName : {

            set : function(newValue) {
                setComputedValue(newValue, 'table_name', 'tableName', this.selectedItem);
            },

            get: function() {
                return getComputedValue('table_name', 'tableName', this.selectedItem);
            }

        },


    }
}
</script>
<style lang="sass">
.toolbar {
    padding: 8px;
    background-color: #ebeaee;

.btn {
    font-size: .8em;
}

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

.col-type {
    width:200px;
}

.col-name {
    width:230px;
}
}

</style>