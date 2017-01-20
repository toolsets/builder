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
                <td v-bind:class="{'has-error': col.has_error == true}"><input type='text' class="form-control" v-model="col.attributes.name"  /></td>
                <td >
                    <select v-model="col.attributes.type" class="form-control">
                        <option v-for="option in changeableTypes" v-bind:value="option">
                            {{ option }}
                        </option>
                    </select>
                </td>
                <td>
                    <input type='text' class="form-control" v-model="col.attributes.length"/>
                </td>
                <td><input type="checkbox" value="1" v-model="col.attributes.nullable" /></td>
                <td><input type="radio" v-bind:value='col' v-model="primaryKey" /></td>
                <td><input type='text' class="form-control" v-model="col.attributes.default" placeholder="none"  /></td>
                <td>
                    <button type="button" class="btn btn-default btn-sm" @click='removeColumn(col)'><i class="fa fa-trash"></i> </button>
                </td>
            </tr>

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


export default{

    props: {
        columns : {
            type: Object,
            required: true
        }
    },

    computed: {
        totalColumns : function() {
            return 0;
        }
    },

    data(){
        return{
            columnTypes : ColumnTypes,
            changeableTypes : Changeable
        }
    }
}
</script>
