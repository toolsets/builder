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
                <input-relations :columns="columnKeys" :tables="getTablesList" :relations="form.relations" :table-name="form.name"></input-relations>
                <input-indexes :columns="columnKeys" :indexes="form.indexes"></input-indexes>


                <div class="action-footer">
                    <button class="btn btn-primary" @click='submitForm' v-bind:disabled='submittable === false'>Submit</button>
                    <button class="btn btn-default" @click='cancelForm'>Reset</button>

                </div>


            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import CreateColumnsForm from './CreateColumnsForm.vue'
import CreateRelationsForm from './CreateRelationsForm.vue'
import CreateIndexesForm from './CreateIndexesForm.vue'

export default {

    components: {
        'input-columns': CreateColumnsForm,
        'input-relations': CreateRelationsForm,
        'input-indexes': CreateIndexesForm
    },

    data() {

        return {
            form : {
                name: null,
                columns: [],
                columnsList: {},
                relations: [],
                indexes: []
            },

        }
    },

    computed: {

        hasDuplicateColumnNames() {

            var Values = _.valuesIn(this.form.columnsList);
            if(Values.length)
            {
                if(_.sum(Values) !== Values.length) {
                    return true;
                }
            }

            return false;
        },

        submittable() {
            if(this.hasDuplicateColumnNames)
            {
                return false;
            }

            return true;
        },

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
        },

        ...mapActions('database', {
            storeSubmit : 'SUBMIT_NEW_TABLE'
        }),

        submitForm() {

            this.storeSubmit({
               table: {
                   name: this.form.name,
                   columns: this.form.columns,
                   relations: this.form.relations,
                   indexes: this.form.indexes
               }
            }).then(function(response) {
                if(response.ok) {
                    this.$router.push({ path: '/database/' + this.form.name });
                }
            }.bind(this));
        },

        cancelForm() {
            this.form = {
                name: null,
                columns: [],
                columnsList: {},
                relations: [],
                indexes: []
            };
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
    margin-bottom: 20px;

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

    .col-type {
        width:200px;
    }

    .col-name {
        width:230px;
    }
}
</style>