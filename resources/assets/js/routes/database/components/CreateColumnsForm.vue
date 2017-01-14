<template>
    <div id="columns-form">
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
                <th>Nullable</th>
                <th>PK</th>
                <th>Default</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(col, index) in columns">
                <td v-bind:class="{'has-error': col.has_error == true}"><input type='text' class="form-control" v-model="col.name"  /></td>
                <td >
                    <select v-model="col.type" class="form-control">
                        <option v-for="option in columnTypes" v-bind:value="option">
                            {{ option }}
                        </option>
                    </select>
                </td>
                <td>
                    <input type='text' class="form-control" v-model="col.length" placeholder="auto" />
                </td>
                <td><input type="checkbox" value="1" v-model="col.nullable" /></td>
                <td><input type="radio" v-bind:value='col' v-model="primaryKey" /></td>
                <td><input type='text' class="form-control" v-model="col.default" placeholder="none" /></td>
                <td>
                    <button type="button" class="btn btn-default btn-sm" @click='removeColumn(col)'><i class="fa fa-trash"></i> </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

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

    props: {
        columns : {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            columnTypes: ColumnTypes,
            primaryKey: null,
        }
    },

    computed: {

        primaryColumnName() {

            if(this.primaryKey !== null) {
                return this.primaryKey.name;
            }

            return undefined;
        },

        listOfColumns() {

            var list = {};

            if(this.columns.length > 0) {
                var primaryKeyName = this.primaryColumnName;

                this.columns.map(function(item) {

                    if(item.name) {
                        //trim name
                        item.name = item.name.trim();
                        item.name = item.name.replace(" ", "_");

                        if(_.has(list, item.name)) {
                            list[item.name] = list[item.name] + 1;
                            item.has_error = true;
                        } else {
                            list[item.name] = 1;
                            item.has_error = false;
                        }
                    }

                    //check primaryKey
                    if(primaryKeyName !== undefined)
                    {
                        if(item.name === primaryKeyName)
                        {
                            item.pk = true;
                        } else {
                            item.pk = false;
                        }
                    }

                    return item;
                });

                this.$emit('columns-updated', list);
            }

            return list;
        }

    },

    methods: {

        addColumn() {
            var col = {
                name: null,
                type: 'string',
                length: null,
                nullable: false,
                pk: false,
                default: null,
                has_error: false,
            };

            if(this.columns.length == 0)
            {
                col.name = 'id';
                col.type = 'increments';
                col.pk = true;

                this.primaryKey = col;
            }


            this.columns.push(col);
        },

        hasColumnError(col) {

            if(this.col !== undefined && this.col.name !== undefined) {
                if(this.listOfColumns[col.name] > 1) {
                    return true;
                }
                return false;
            }
            return false;
        },

        removeColumn(item) {
            var index = this.columns.indexOf(item);
            this.columns.splice(index, 1)
        }
    }
}
</script>
