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

                <div class="builder-form">
                    <div class="input-title">
                        <label>Table Columns | {{ Object.keys(listOfColumns).length }}</label>
                    </div>
                </div>


                <div class="toolbar">
                    <a class="btn btn-primary" title="Add Column" @click="addColumn" >Add Column</a>
                </div>

                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th class="col-name">Column</th>
                        <th class="col-type">Type</th>
                        <th>Length</th>
                        <th>Unsigned</th>
                        <th>Nullable</th>
                        <th>AI</th>
                        <th>PK</th>
                        <th>Default</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="col in form.columns">
                            <td v-bind:class="{'has-error': col.has_error == true}"><input type='text' class="form-control" v-model="col.name"  /></td>
                            <td >
                                <select v-model="col.type" class="form-control">
                                    <option v-for="option in columnTypes" v-bind:value="option">
                                        {{ option }}
                                    </option>
                                </select>
                            </td>
                            <td></td>
                            <td ><input type="checkbox" value="1" v-model="col.unsigned" /></td>
                            <td><input type="checkbox" value="1" v-model="col.nullable" /></td>
                            <td><input type="checkbox" value="1" v-model="col.ai" /></td>
                            <td><input type="checkbox" value="1" v-model="col.pk" /></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <div class="builder-form">
                    <div class="input-title">
                        <label>Table Relations</label>
                    </div>
                </div>

                <div class="toolbar">
                    <a class="btn btn-primary" title="Add Relation">Add Relation</a>
                </div>

                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Columns</th>
                        <th>FK Table</th>
                        <th>FK Columns</th>
                        <th>On Update</th>
                        <th>On Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


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

const ColumnTypes = [
    "bigIncrements",
    "bigInteger",
    "binary",
    "boolean",
    "char",
    "date",
    "dateTime",
    "dateTimeTz",
    "decimal",
    "double",
    "enum",
    "float",
    "increments",
    "integer",
    "ipAddress",
    "json",
    "jsonb",
    "longText",
    "macAddress",
    "mediumIncrements",
    "mediumInteger",
    "mediumText",
    "morphs",
    "nullableMorphs",
    "nullableTimestamps",
    "rememberToken",
    "smallIncrements",
    "smallInteger",
    "softDeletes",
    "string",
    "text",
    "time",
    "timeTz",
    "tinyInteger",
    "timestamp",
    "timestampTz",
    "timestamps",
    "timestampsTz",
    "unsignedBigInteger",
    "unsignedInteger",
    "unsignedMediumInteger",
    "unsignedSmallInteger",
    "unsignedTinyInteger",
    "uuid"
];


export default {

    data() {

        return {
            form : {
                name: null,
                columns: [],
                relations: [],
                index: []
            },
            columnTypes : ColumnTypes
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

        listOfColumns() {

            var list = {};

            if(this.form.columns.length > 0) {
                this.form.columns.map(function(item) {
                    if(item.name)
                    {
                        if(_.has(list, item.name)) {
                            list[item.name] = list[item.name] + 1;
                            item.has_error = true;
                        } else {
                            list[item.name] = 1;
                            item.has_error = false;
                        }
                    }

                    return item;
                });
            }

            return list;
        },

        ...mapGetters('database',['getTablesList']),
    },

    methods: {

        goBack() {
            this.$router.push({ path: '/database' })
        },

        addColumn() {
            var col = {
                name: null,
                type: 'string',
                length: null,
                unsigned: null,
                nullable: false,
                ai: false,
                pk: false,
                default: null,
                has_error: false,
            };

            if(this.form.columns.length == 0)
            {
                col.type = 'increments';
                col.pk = true;
                col.ai = true;
            }

            this.form.columns.push(col);
        },

        hasColumnError(col) {

            if(this.col !== undefined && this.col.name !== undefined) {
                if(this.listOfColumns[col.name] > 1) {
                    return true;
                }
                return false;
            }
            return false;
        }

    },

    mounted() {

    }
}
</script>
<style lang="sass" scoped>
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