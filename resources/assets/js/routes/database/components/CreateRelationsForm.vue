<template>
    <div id="relations-form">
        <div class="builder-form">
            <div class="input-title">
                <label>Table Relations</label>
            </div>
            {{ columnKeys }}
        </div>

        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th>Column</th>
                <th>FK Table</th>
                <th>FK Columns</th>
                <th>On Update</th>
                <th>On Delete</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="row in relations">
                    <td>
                        <select v-model="row.column" class="form-control">
                            <option v-for="option in columns" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <select v-model="row.fk_table" class="form-control">
                            <option v-for="option in tables" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <select v-if="!row.fk_table" v-model="row.fk_column" disabled="disabled" class="form-control">
                            <option value="null"></option>
                        </select>
                        <select v-model="row.fk_column" class="form-control" v-if="row.fk_table">
                            <option   v-for="option in table_data[row.fk_table].columns" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <select v-model="row.on_update" class="form-control" >
                            <option value="none">None</option>
                            <option value="cascade">CASCADE</option>
                            <option value="set_null">SET NULL</option>
                            <option value="restrict">RESTRICT</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="row.on_delete" class="form-control" >
                            <option value="none">None</option>
                            <option value="cascade">CASCADE</option>
                            <option value="set_null">SET NULL</option>
                            <option value="restrict">RESTRICT</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-default btn-sm" @click='removeItem(row)'><i class="fa fa-trash"></i> </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="toolbar">
            <a class="btn btn-primary" title="Add Relation" @click='addRelation'>Add Relation</a>
        </div>

    </div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {

    props: {
        columns : {
            type: Array,
            required: true
        },
        relations: {
            type: Array,
            required: true
        },
        tables: {
            type: Array,
            required: true
        }
    },

    computed: {

        ...mapGetters('database', {
            'table_data' : 'GET_TABLES_DATA'
        })
    },

    methods: {

        addRelation () {
            var newRelation = {
                column: null,
                fk_table: null,
                fk_column: null,
                on_update: 'none',
                on_delete: 'none'
            };

            this.relations.push(newRelation);
        },

        removeItem (item) {
            var index = this.relations.indexOf(item);
            this.relations.splice(index, 1)
        }
    }
}
</script>