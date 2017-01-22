<template>
    <tr v-bind:class="{ 'drop-column': column_drop }">
        <td v-bind:class="{ migrated: migrated == true, 'not-migrated': migrated == false}" class="tbl-status"></td>
        <td v-bind:class="{'has-error': column_has_error === true}">
            <input type='text' class="form-control" v-model="column_name" v-if="!column_drop && editable"/>
            <span v-if="column_drop || !editable">{{ column_name }}</span>
        </td>
        <td>
            <select v-model="column_type" class="form-control" v-if="isOnFile && !column_drop && canEditType">
                <option v-for="option in changeTypes" v-bind:value="option">
                    {{ option }}
                </option>
            </select>
            <select v-model="column_type" class="form-control" v-if="!isOnFile && !column_drop && canEditType">
                <option v-for="option in createTypes" v-bind:value="option">
                    {{ option }}
                </option>
            </select>
            <span v-if="column_drop || !canEditType">{{ column_type }}</span>
        </td>
        <td>
            <input type='text' class="form-control" v-model="column_length" v-if="!column_drop && canEditType" v-bind:placeholder="placeholder"/>
            <span v-if="column_drop || !canEditType">{{ column_length }}</span>
        </td>
        <td>
            <input type="checkbox" value="1" v-model="column_nullable" v-bind:disabled="!canEditType"/>
        </td>
        <td>
            <input type='text' class="form-control" v-model="column_default" placeholder="none"  v-if="!column_drop && canEditType"/>
            <span v-if="column_drop || !canEditType">{{ column_default }}</span>
        </td>
        <td>
            <button type="button" class="btn btn-default btn-sm" @click='dropColumn()'><i class="fa fa-trash"></i> </button>
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

        createTypes: {
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

        placeholder: function () {
          if(this.column.type === 'enum') {
              return 'Foo, Bar'
          }

          return 'auto';
        },

        canEditType: function () {
            if(this.editable === true) {

                if(this.isOnFile === true) {
                    console.log('isOnFile true');
                    console.log('column Name: ' + this.column.name);
                    console.log('column type: ' + this.column.type);

                    if(this.changeTypes.indexOf(this.column.type) == -1) {
                        return false;
                    }
                }

                return true;
            }

            return this.editable;
        },

        isOnFile: function () {
          return this.column.onFile === true;
        },

        column_has_error: function () {
            console.log('column_has_error:'+this.column.name, this.column.has_error);
            return this.column.has_error;
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

        column_nullable:{
            get: function () {
                return this.column.nullable;
            },
            set: function (value) {
                this.columnUpdate(this.column.key, 'nullable', value);
            }
        },

        column_drop: {
            get: function () {
                return this.column.drop;
            },
            set: function (value) {
                this.columnUpdate(this.column.key, 'drop', value);
            }
        },

//
//        functions: function() {
//
//            return {
//                nullable: false,
//                default: false,
//                length: false
//            }
//        }
    },

    methods: {

        dropColumn() {
            this.column_drop = !this.column_drop;
        }
    }
}
</script>