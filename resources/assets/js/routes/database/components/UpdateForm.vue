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

            <input-columns :selected="selectedItem"></input-columns>



            {{ selectedItem.updates }}
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex'
import types from '../store/types';
import UpdateColumnsForm from './UpdateColumnsForm.vue'


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
        'input-columns': UpdateColumnsForm
     },

     data() {

        return {

        }
    },

    computed : {

        ...mapState('database', ['selectedItem']),

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
.table {
    font-size: 1em;
    background-color: #FFF;

.tbl-status {
    width: 10px;
    padding: 0;
}

td.migrated {
    background-color: #2b542c;
}

td.not-migrated {
    background-color: #985f0d;
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