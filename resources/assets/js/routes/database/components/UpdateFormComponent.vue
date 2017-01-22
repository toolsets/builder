<template>
    <div id="table-update-form">
        <div class="builder-form">
            <div class="input-title">
                <label>Table Columns | {{ Object.keys(columnsList).length }}</label>
            </div>
        </div>

        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th class="tbl-status"></th>
                <th class="col-name">Column</th>
                <th class="col-type">Type</th>
                <th>Length</th>
                <th>Nullable</th>
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

            <column v-for="col in columns"
                    :column="col"
                    :editable="editable"
                    :change-types="changeableTypes"
                    :create-types="columnTypes"
                    :column-update="updateColumn"></column>

            </tbody>
        </table>

        <div class="toolbar">
            <a class="btn btn-primary" title="Add Column" @click="addColumn" >Add Column</a>
            <a class="btn btn-default" title="Add Timestamps" @click="addTimestamps" >Add Timestamps</a>
            <a class="btn btn-default" title="Add TimestampsTz" @click="addTimestampsTz" >Add TimestampsTz</a>
        </div>

        <div class="builder-form">
            <div class="input-title">
                <label>Table Relations</label>
            </div>
        </div>

        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th class="tbl-status"></th>
                    <th>Name</th>
                    <th>Columns</th>
                    <th>FK Table</th>
                    <th>FK Columns</th>
                    <th>On Update</th>
                    <th>On Delete</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fk in relations" v-bind:class="{ 'drop-column': fk.drop }">
                    <td v-bind:class="{ migrated: fk.migrated == true, 'not-migrated': fk.migrated == false}" class="tbl-status"></td>
                    <td>{{ fk.index }}</td>
                    <td>{{ fk.columns }}</td>
                    <td>{{ fk.fk_table }}</td>
                    <td>{{ fk.fk_column }}</td>
                    <td>{{ fk.on_update ? fk.on_update : '' }}</td>
                    <td>{{ fk.on_delete ? fk.on_delete : '' }}</td>
                    <td><button type="button" class="btn btn-default btn-sm" @click='dropRelation(row)'><i class="fa fa-trash"></i> </button></td>
                </tr>
            </tbody>
        </table>

    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import {EnumTypeWarning} from '../blueprint.js';
import UpdateColumnComponent from './updateColumn.vue';

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

const NotChangeable = [
    'char',
    'double',
    'enum',
    'mediumInteger',
    'timestamp',
    'tinyInteger',
    'ipAddress',
    'json',
    'jsonb',
    'macAddress',
    'mediumIncrements',
    'morphs',
    'nullableMorphs',
    'nullableTimestamps',
    'timeTz',
    'unsignedMediumInteger',
    'unsignedTinyInteger',
    'uuid'
];

const Changeable = [
    "bigIncrements",
    "bigInteger",
    "binary",
    "boolean",
    "date",
    "dateTime",
    "dateTimeTz",
    "decimal",
    "float",
    "increments",
    "integer",
    "longText",
    "mediumText",
    "smallIncrements",
    "smallInteger",
    "string",
    "text",
    "time",
    "unsignedBigInteger",
    "unsignedInteger",
    "unsignedSmallInteger"
];

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

        table.updates = selectedItem.updates;
        table.hasEnumColumns = selectedItem.hasEnumColumns;
        table.updates = selectedItem.updates;
        table.relations = selectedItem.relations;
        table.indexes = selectedItem.indexes;
        table.columnRefs = columnRefs;
    }

    return table;
}


function makeNewColumn(tempKey) {

    var column = {
        key: tempKey,
        name: null,
        type: 'string',
        length: null,
        nullable: false,
        default: null,
        migrated: false,
        drop: false,
        onFile: false,
        updates: {
            change_name: null,
            change_type: null,
            change_length: null,
            change_nullable: null,
            change_default: null,
            change_drop: null
        }
    };

    return column;
}

export default{

    components: { column : UpdateColumnComponent },

    props: {
        selected : {
            type: Object,
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
        }),

        totalColumns : function() {
            return 0;
        },

        columnsList : function() {
           var list = {};

           this.selectedTable.columns.map(function(item) {

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
           });

           return list;
        },

        columnsListArray: function() {
            return Object.keys(this.columnsList);
        },

        columns: function() {
            return this.selectedTable.columns;
        },

        relations: function () {
            return this.selectedTable.relations;
        },

        editable: function () {
            return this.selectedTable.hasEnumColumns !== true;
        }
    },

    data(){
        return{
            columnTypes : ColumnTypes,
            changeableTypes : Changeable,
            hasEnumType: this.selected.hasEnumColumns,
            EnumWarningMsg: EnumTypeWarning,
            selectedTable: makeUpdatableTableObject(this.selected)
        }
    },

    methods: {

        updateColumn(colKey, property, value) {

            var column = this.selectedTable.columnRefs[colKey];
            if(column){
                if(column[property] !== undefined) {

                    //check for newly created columns drop
                    if(property === 'drop' && column.onFile === false) {

                       var index = this.selectedTable.columns.indexOf(column);
                       if(index !== -1) {
                           this.selectedTable.columns.splice(index, 1);
                           delete this.selectedTable.columnRefs[colKey];
                       }

                       return;
                    }

                    value = (value !== '') ? value : null;

                    if(column.onFile === true) {

                        var property_change_key = 'change_' + property;

                        if(column.updates[property_change_key] === null) {
                            column.updates[property_change_key] = {
                                from: column[property],
                                to: value
                            }
                        } else {
                            if(column.updates[property_change_key].from == value) {
                                column.updates[property_change_key] = null;
                            } else {
                                column.updates[property_change_key].to = value;
                            }
                        }
                    }


                    column[property] = value;

                }
            }
        },

        dropRelation(row) {
            // TODO
        },

        addColumn() {
            var tempKey = this.selectedTable.columns.length + 1;
            var column = makeNewColumn(tempKey);

            this.selectedTable.columns.push(column);
            this.selectedTable.columnRefs[tempKey] = column;
        },

        addTimestamps() {

            ['created_at', 'updated_at'].map(function(columnName) {
                if(this.columnsList[columnName] === undefined) {
                    var tempKey = columnName;
                    var column = makeNewColumn(tempKey);
                    column.name = columnName;
                    column.type = 'timestamp';
                    column.nullable = true;

                    this.selectedTable.columns.push(column);
                    this.selectedTable.columnRefs[tempKey] = column;

                }
            }.bind(this));

        },

        addTimestampsTz() {
            ['created_at', 'updated_at'].map(function(columnName) {
                if(this.columnsList[columnName] === undefined) {
                    var tempKey = columnName;
                    var column = makeNewColumn(tempKey);
                    column.name = columnName;
                    column.type = 'timestampTz';
                    column.nullable = true;

                    this.selectedTable.columns.push(column);
                    this.selectedTable.columnRefs[tempKey] = column;

                }
            }.bind(this));
        }
    }
}
</script>
