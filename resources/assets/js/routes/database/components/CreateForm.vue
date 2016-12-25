<template>
    <div class="list-panel">
        <div class="table-section">
            <div class="title-header">
                New Table Migration
            </div>
            <div class="toolbar">

            </div>
            <div class="builder-form">

            </div>

        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex'

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

const formSchema = {
    "title": "New Migration",
    "type": "object",
    "properties": {
        "table_name": {
            "title": "Table Name",
            "type": "string",
            "description": "Name of the table to be created",
            "minLength": 4
        },

        "columns": {
            "type": "array",
            "format": "table",
            "title": "Columns",
            "uniqueItems": true,
            "items": {
                "type": "object",
                "title": "Column",
                "properties": {
                    "type": {
                        'title': "Type",
                        "type": "string",
                        "enum": ColumnTypes,
                        "default": "string"
                    },
                    "name": {
                        "title": "Name",
                        "type": "string"
                    },
                    "length": {
                        "title": "Length",
                        "type": "string"
                    },
                    "default": {
                        "title": "Default",
                        "type": "string"
                    },
                    "nullable" : {
                        "title": "Nullable",
                        "type": "boolean",
                        "format": "checkbox"
                    },
                    "index" : {
                        "title": "Index",
                        "type": "boolean",
                        "format": "checkbox"
                    }
                }
            }
        },

        "fk_relations": {
            "type": "array",
            "format": "table",
            "title": "FK Relations",
            "uniqueItems": true,
            "items": {
                "type": "object",
                "title": "Relation",
                "properties": {

                    "field": {
                        'title': "Field",
                        "type": "string",
                        "watch": {
                            "fields": "columns"
                        },
                        "enumSource": [
                            {
                                "source" : "fields",
                                "title" : "{{ item.name }}",
                                "value" : "{{ item.name }}"
                            }
                        ]
                    },

                    "fk_table": {
                        'title': "FK Table",
                        "type": "string",
                        "enum": []
                    },

                    "fk_field": {
                        'title': "FK Field",
                        "type": "string",
                        "enum": []
                    },

                }
            }
        }
    }
};

import SchemaFormEditor from '../../../layouts/ui/SchemaFormEditor.vue'

export default {

    data() {

        return {
            formData : {},
            formSchema: {}
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

        ...mapState('database',['tables_list']),
    },


    methods: {

        goBack() {
            this.$router.push({ path: '/database' })
        },

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
}
</style>