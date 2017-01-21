<template>
    <div id="columns-form">
        <div class="builder-form">
            <div class="input-title">
                <label>Table Columns </label>
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

            <column v-for="col in columns" :column="col" :editable="editable" :change-types="changeableTypes" :column-update="updateColumn"></column>


            </tbody>
        </table>

        {{ columns }}

        <div class="toolbar">
            <a class="btn btn-primary" title="Add Column" @click="addColumn" >Add Column</a>
            <a class="btn btn-default" title="Add Timestamps" @click="addTimestamps" >Add Timestamps</a>
            <a class="btn btn-default" title="Add TimestampsTz" @click="addTimestampsTz" >Add TimestampsTz</a>
        </div>

    </div>
</template>
<script>
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
        table.name = selectedItem.name;
        table.columns = Object.keys(selectedItem.columns).map(function(key) {

            var item = selectedItem.columns[key];

            var columnObj = {
                key: key,
                name: item.attributes.name,
                type: item.attributes.type,
                length: item.attributes.length,
                nullable: item.attributes.nullable,
                default: item.attributes.default,
                migrated: item.migrated,
                updates: {
                    change_name: null,
                    change_type: null,
                    change_length: null,
                    change_nullable: null,
                    change_default: null
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
        table.columnRefs = columnRefs;
    }

    return table;
}

export default{

    components: { column : UpdateColumnComponent },

    props: {
        selected : {
            type: Object,
            required: true
        }
    },

    computed: {
        totalColumns : function() {
            return 0;
        },

        columns: function() {
            return this.selectedTable.columns;
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
                    var property_change_key = 'change_' + property;

                    value = (value !== '') ? value : null;

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

                    column[property] = value;


                }
            }
        },

        addColumn() {

        },

        addTimestamps() {

        },

        addTimestampsTz() {

        }
    }
}
</script>
