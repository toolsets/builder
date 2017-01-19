<template>
    <div id="columns-form">
        <div class="builder-form">
            <div class="input-title">
                <label>Table Columns | {{ Object.keys(listOfColumns).length }}</label>
            </div>
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
            <tr v-if="hasEnumType">
                <td colspan="9" class="text-warning warning">
                    <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning: {{ EnumWarningMsg }}</p>
                </td>
            </tr>
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
                    <input type='text' class="form-control" v-model="col.length" v-bind:placeholder="col.placeholder" v-bind:disabled="col.functions.length === false"/>
                </td>
                <td><input type="checkbox" value="1" v-model="col.nullable" v-bind:disabled="col.functions.nullable === false"/></td>
                <td><input type="radio" v-bind:value='col' v-model="primaryKey" v-bind:disabled="col.functions.pk === false"/></td>
                <td><input type='text' class="form-control" v-model="col.default" placeholder="none" v-bind:disabled="col.functions.default === false" /></td>
                <td>
                    <button type="button" class="btn btn-default btn-sm" @click='removeColumn(col)'><i class="fa fa-trash"></i> </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="toolbar">
            <a class="btn btn-primary" title="Add Column" @click="addColumn" >Add Column</a>
            <a class="btn btn-default" title="Add Timestamps" @click="addTimestamps" >Add Timestamps</a>
            <a class="btn btn-default" title="Add TimestampsTz" @click="addTimestampsTz" >Add TimestampsTz</a>
        </div>

    </div>
</template>

<script>
import {EnumTypeWarning} from '../blueprint.js';

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
    "string",
    "text",
    "time",
    "timeTz",
    "tinyInteger",
    "timestamp",
    "timestampTz",
    "unsignedBigInteger",
    "unsignedInteger",
    "unsignedMediumInteger",
    "unsignedSmallInteger",
    "unsignedTinyInteger",
    "uuid"
];

const IncrementColumns = [
    "bigIncrements",
    "increments",
    "mediumIncrements",
    "smallIncrements"
];

const AutoUnsignedColumns = [
    "unsignedBigInteger",
    "unsignedInteger",
    "unsignedMediumInteger",
    "unsignedSmallInteger",
    "unsignedTinyInteger",
];

const SpecialColumns = [
    "morphs",
    "nullableMorphs"
];


const AllColumnsFunctionalyChecked = _.concat([], IncrementColumns, AutoUnsignedColumns, SpecialColumns);

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
            hasEnumType: false,
            EnumWarningMsg: EnumTypeWarning
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

                this.hasEnumType = false;

                this.columns.map(function(item) {

                    var defaultFunctions = Object.assign({}, item.functions);
                    var originalCopy = Object.assign({}, item);

                    //check for ENUM col type
                    if(item.type === 'enum') {
                        this.hasEnumType = true;
                        item.placeholder = 'Foo, Bar';
                        item.placeholderOrigin = originalCopy.placeholder;

                        if(item.default == null) {
                            item.nullable = true;
                        }
                    } else {

                        if(item.placeholderOrigin !== undefined) {
                            if(item.placeholder == 'Foo, Bar') {
                                item.placeholder = 'auto';
                            } else {
                                item.placeholder = item.placeholderOrigin;
                            }

                            _.unset(item, 'placeholderOrigin');
                        }
                    }

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




                    //check for functionalities column types
                    if (AllColumnsFunctionalyChecked.indexOf(item.type) !== -1 ) {



                        if (IncrementColumns.indexOf(item.type) !== -1) {

                            item.functions.nullable = false;
                            item.functions.default = false;

                        } else if (AutoUnsignedColumns.indexOf(item.type) !== -1 ) {

                            item.functions.nullable = false;
                            item.functions.default = false;

                        } else if (AutoUnsignedColumns.indexOf(item.type) !== -1 ) {

                            item.functions.nullable = false;
                            item.functions.default = false;

                        } else if (SpecialColumns.indexOf(item.type) !== -1 ) {

                            item.functions.nullable = false
                            item.functions.pk = false;
                            item.functions.default = false;
                            item.functions.length = false;
                            item.pk = false;
                            item.length = null;
                            item.default = null;
                            item.nullable = false;

                        } else {

                            item.functions = defaultFunctions;
                        }
                    }
                    else
                    {
                        item.functions = defaultFunctions;
                    }

                    //check primaryKey
                    if(primaryKeyName !== undefined)
                    {
                        if(item.name === primaryKeyName)
                        {
                            item.pk = true;
                            item.functions.nullable = false;
                            item.functions.default = false;
                            item.default = null;
                            item.nullable = false;
                            item.length = null;

                        } else {
                            item.pk = false;
                            item.functions = defaultFunctions;
                        }
                    }

                    //check if ends with "_id"
                    if(_.endsWith(item.name, '_id'))
                    {
                        if(item.type === 'string') {
                            item.type = 'unsignedInteger';
                        }
                    }

                    return item;
                }.bind(this));

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
                placeholder: 'auto',
                functions: {
                    nullable: true,
                    pk: true,
                    length: true,
                    default: true
                }
            };

            if(this.columns.length == 0)
            {
                col.name = 'id';
                col.type = 'increments';
                col.pk = true;
                col.functions.nullable = false;
                col.functions.default = false;
                col.functions.length = false;

                this.primaryKey = col;
            }


            this.columns.push(col);
        },

        addTimestamps() {

            ['created_at', 'updated_at'].map(function(name){
                if(this.listOfColumns[name] === undefined) {
                    var col = {
                        name: null,
                        type: 'timestamp',
                        length: null,
                        nullable: true,
                        pk: false,
                        default: null,
                        has_error: false,
                        placeholder: 'auto',
                        functions: {}
                    };

                    col.name = name;
                    col.functions = {
                                        nullable: false,
                                        pk: true,
                                        length: false,
                                        default: false
                                    };
                    this.columns.push(col);
                }
            }.bind(this));
        },

        addTimestampsTz() {
            ['created_at', 'updated_at'].map(function(name){
                if(this.listOfColumns[name] === undefined) {
                    var col = {
                        name: null,
                        type: 'timestampTz',
                        length: null,
                        nullable: true,
                        pk: false,
                        default: null,
                        has_error: false,
                        placeholder: 'auto',
                        functions: {}
                    };

                    col.name = name;
                    col.functions = {
                                        nullable: false,
                                        pk: true,
                                        length: false,
                                        default: false
                                    };
                    this.columns.push(col);
                }
            }.bind(this));
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
