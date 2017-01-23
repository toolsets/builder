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
                    <th>Column</th>
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
                    <td>{{ fk.column }}</td>
                    <td>{{ fk.fk_table }}</td>
                    <td>{{ fk.fk_column }}</td>
                    <td>{{ fk.on_update ? fk.on_update : '' }}</td>
                    <td>{{ fk.on_delete ? fk.on_delete : '' }}</td>
                    <td><button type="button" class="btn btn-default btn-sm" @click='dropRelation(fk)'><i class="fa fa-trash"></i> </button></td>
                </tr>
                <tr v-for="nfk in relations_added">
                    <td class="tbl-status not-migrated"></td>
                    <td>
                        <select v-model="nfk.column" class="form-control">
                            <option v-for="col in columnsListArray" v-bind:value="col">{{ col }}</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="nfk.fk_table" class="form-control">
                            <option v-for="tbl in tables" v-bind:value="tbl">{{ tbl }}</option>
                        </select>
                    </td>
                    <td>
                        <select v-if="!nfk.fk_table" v-model="nfk.fk_column" disabled="disabled" class="form-control">
                            <option value="null"></option>
                        </select>

                        <select v-model="nfk.fk_column" class="form-control" v-if="nfk.fk_table && nfk.fk_table === selectedTable.name">
                            <option v-for="option in columnsListArray" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>

                        <select v-model="nfk.fk_column" class="form-control" v-if="nfk.fk_table && nfk.fk_table !== selectedTable.name">
                            <option  v-for="option in table_data[nfk.fk_table].columns" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <select v-model="nfk.on_update" class="form-control" >
                            <option value="none">None</option>
                            <option value="cascade">CASCADE</option>
                            <option value="set_null">SET NULL</option>
                            <option value="restrict">RESTRICT</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="nfk.on_delete" class="form-control" >
                            <option value="none">None</option>
                            <option value="cascade">CASCADE</option>
                            <option value="set_null">SET NULL</option>
                            <option value="restrict">RESTRICT</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-default btn-sm" @click='dropNewRelation(nfk)'><i class="fa fa-trash"></i> </button></td>
                </tr>
            </tbody>
        </table>


        <div class="toolbar">
            <a class="btn btn-primary" title="Add Relation" @click='addRelation'>Add Relation</a>
        </div>



        <div class="builder-form">
            <div class="input-title">
                <label>Table Indexes</label>
            </div>
        </div>

        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th class="tbl-status"></th>
                    <th>Type</th>
                    <th>Columns</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="indx in indexes" v-bind:class="{ 'drop-column': indx.drop }">
                    <td v-bind:class="{ migrated: indx.migrated == true, 'not-migrated': indx.migrated == false}" class="tbl-status"></td>
                    <td>{{ indx.type }}</td>
                    <td>{{ indx.columns }}</td>
                    <td><button type="button" class="btn btn-default btn-sm" @click='dropIndex(indx)'><i class="fa fa-trash"></i> </button></td>
                </tr>
                <tr v-for="indColumn in indexes_added">
                    <td class="tbl-status not-migrated"></td>
                    <td>
                        <select v-model="indColumn.type" class="form-control">
                            <option value="index">INDEX</option>
                            <option value="unique">UNIQUE</option>
                            <option value="primary">PRIMARY (composite)</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="indColumn.columns" class="form-control" multiple="multiple">
                            <option v-for="option in columnsListArray" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-default btn-sm" @click='dropNewIndex(indColumn)'><i class="fa fa-trash"></i> </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="toolbar">
            <a class="btn btn-primary" title="Add Index" @click='addIndex'>Add Index</a>
        </div>




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

            var cols = [];

            this.selectedTable.columns.map(function(item) {
               if(item.drop !== true) {
                   cols.push(item.name);
               }
            });

            return cols;
        },

        columns: function() {
            return this.selectedTable.columns;
        },

        relations: function () {
            return this.selectedTable.relations;
        },

        relations_added: function () {
            return this.selectedTable.relations_added;
        },

        indexes: function () {
            return this.selectedTable.indexes;
        },

        indexes_added: function () {
            return this.selectedTable.indexes_added;
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
            selectedTable: this.selected
        }
    },

    methods: {

        fireUpdate() {
            this.$emit('update', this.selectedTable);
        },

        updateColumn(colKey, property, value) {

            var column = this.selectedTable.columnRefs[colKey];
            if(column){
                if(column[property] !== undefined) {

                    if(property === 'drop') {
                        //check if any index needs to be dropped due to the column
                        this.dropRelationByColumn(column);
                    }

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

            this.fireUpdate();
        },

        dropRelation(relation) {

            relation.drop = !relation.drop;
            if(relation.drop) {
                relation.updates = {
                    change_drop: {
                        from: false,
                        to: true
                    }
                }

            } else {
                relation.updates = {
                    change_drop: null
                }
            }

            this.fireUpdate();
        },

        dropRelationByColumn(column) {

            this.relations.map(function (relation) {
                if(relation.column === column.name && relation.drop === false) {
                    this.dropRelation(relation);
                }
            }.bind(this));

            this.relations_added.map(function (relation) {
                if(relation.column === column.name && relation.drop === false) {
                    this.dropNewRelation(relation);
                }
            }.bind(this));

            this.fireUpdate();
        },

        dropNewRelation(relation) {
            var selectedTable = this.selectedTable;
            var indx = selectedTable.relations_added.indexOf(relation);
            selectedTable.relations_added.splice(indx, 1);
            //this.$set(this.selectedTable, selectedTable);

            this.fireUpdate();
        },

        addRelation() {
            var newRelation = {
                column: null,
                fk_column: null,
                fk_table: null,
                on_delete: null,
                on_update:null
            };
            var selectedTable = this.selectedTable;
            selectedTable.relations_added.push(newRelation);
            //this.$set(this.selectedTable, selectedTable);

            this.fireUpdate();
        },

        addColumn() {
            var tempKey = this.selectedTable.columns.length + 1;
            var column = makeNewColumn(tempKey);

            this.selectedTable.columns.push(column);
            this.selectedTable.columnRefs[tempKey] = column;

            this.fireUpdate();
        },

        dropIndex(indexItem) {
            indexItem.drop = !indexItem.drop;
            if(indexItem.drop) {
                indexItem.updates = {
                    change_drop: {
                        from: false,
                        to: true
                    }
                }

            } else {
                indexItem.updates = {
                    change_drop: null
                }
            }

            this.fireUpdate();
        },

        dropNewIndex(indexItem) {
            var selectedTable = this.selectedTable;
            var indx = selectedTable.indexes_added.indexOf(indexItem);
            selectedTable.indexes_added.splice(indx, 1);
            //this.$set(this.selectedTable, selectedTable);

            this.fireUpdate();
        },

        addIndex() {
           var newIndex = {
               columns: [],
               type:null
           };

            var selectedTable = this.selectedTable;
            selectedTable.indexes_added.push(newIndex);
            //this.$set(this.selectedTable, selectedTable);

            this.fireUpdate();
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

            this.fireUpdate();

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

            this.fireUpdate();
        }
    }
}
</script>
