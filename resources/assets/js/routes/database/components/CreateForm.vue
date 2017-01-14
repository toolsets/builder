<template>
    <div class="table-form">
        <div class="list-panel">
            <div class="table-section">
                <div class="title-header">
                    New Table Migration : {{ form.name }}
                </div>

                <div class="builder-form">
                    <div class="builder-form">
                        <div class="form-group input-title">
                            <label for="table_name">Table Name</label>
                            <input type="text" class="form-control" v-model="form.name" ></input>
                        </div>
                    </div>
                </div>

                <input-columns :columns="form.columns" v-on:columns-updated="updateColumnsList"></input-columns>
                <input-relations :columns="columnKeys" :tables="getTablesList" :relations="form.relations"></input-relations>




                <div class="builder-form">
                    <div class="input-title">
                        <label>Table Indexes</label>
                    </div>
                </div>

                <div class="toolbar">
                    <a class="btn btn-primary" title="Add Index">Add Index</a>
                </div>

                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Columns</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import CreateColumnsForm from './CreateColumnsForm.vue'
import CreateRelationsForm from './CreateRelationsForm.vue'

export default {

    components: {
        'input-columns': CreateColumnsForm,
        'input-relations': CreateRelationsForm
    },

    data() {

        return {
            form : {
                name: null,
                columns: [],
                columnsList: {},
                relations: [],
                index: []
            },

        }
    },

    computed: {

        showBackButton(){
            if(this.$parent.showLeft == false)
            {
                return true;
            }

            return false;
        },

        columnKeys() {
            return Object.keys(this.form.columnsList);
        },

        ...mapGetters('database',['getTablesList'])
    },

    methods: {

        goBack() {
            this.$router.push({ path: '/database' })
        },

        updateColumnsList(columnsList) {
            this.form.columnsList = columnsList;
        }

    },

    mounted() {

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

    .col-type {
        width:200px;
    }

    .col-name {
        width:230px;
    }
}
</style>