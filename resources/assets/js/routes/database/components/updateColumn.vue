<template>
    <tr>
        <td v-bind:class="{ migrated: migrated == true, 'not-migrated': migrated == false}" class="tbl-status"></td>
        <td><input type='text' class="form-control" v-model="column_name" v-bind:disabled="!editable" /></td>
        <td>
            <select v-if='editable' v-model="column_type" class="form-control">
                <option v-for="option in changeTypes" v-bind:value="option">
                    {{ option }}
                </option>
            </select>
            <input v-if='!editable' type='text' class="form-control" v-model="column_type" disabled="disabled" />
        </td>
        <td>
            <input type='text' class="form-control" v-model="column_length" v-bind:disabled="!editable"/>
        </td>
        <td><input type="checkbox" value="1" v-model="column_nullable" v-bind:disabled="!editable"/></td>
        <td><input type='text' class="form-control" v-model="column_default" placeholder="none"  v-bind:disabled="!editable"/></td>
        <td>
            <button type="button" class="btn btn-default btn-sm" @click='removeColumn()'><i class="fa fa-trash"></i> </button>
        </td>
    </tr>
</template>
<script>



export default {
    props: {
        column: {
            type: Object,
            required: true
        },

        editable: {
            type: Boolean,
            required: true,
        },

        changeTypes: {
            type: Array,
            required: true
        },

        columnUpdate: {
            type: Function,
            required: true
        }
    },

    computed: {

        migrated: function () {
            if(this.hasColumnChanges) {
                return false;
            }
            else {
                return this.column.migrated;
            }
        },

        hasColumnChanges: function() {
            var hasChanges = false;

            Object.keys(this.column.updates).map(function(itemKey) {
                if(hasChanges != true) {
                    if(this.column.updates[itemKey] !== null) {
                        hasChanges = true;
                    }
                }
            }.bind(this));

            return hasChanges;
        },

        column_name: {
            get: function() {
                return this.column.name;
            },

            set: function (value) {
                this.columnUpdate(this.column.key, 'name', value);
            }
        },

        column_type: {
            get: function() {
                return this.column.type;
            },
            set: function (value) {
                this.columnUpdate(this.column.key, 'type', value);
            }
        },

        column_length:{
            get: function () {
                return this.column.length;
            },
            set: function (value) {
                this.columnUpdate(this.column.key, 'length', value);
            }
        },

        column_default:{
            get: function () {
                return this.column.default;
            },
            set: function (value) {
                this.columnUpdate(this.column.key, 'default', value);
            }
        },


        functions: function() {

            return {
                nullable: false,
                default: false,
                length: false
            }
        }
    },

    methods: {

        removeColumn() {

        }
    }
}
</script>