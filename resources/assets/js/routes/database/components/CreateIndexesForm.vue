<template>
    <div id="index-form">
        <div class="builder-form">
            <div class="input-title">
                <label>Table Indexes</label>
            </div>
        </div>

        <table class="table table-striped table-responsive">
            <thead>
            <tr>
                <th>Type</th>
                <th>Columns</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="indColumn in indexes">
                    <td>
                        <select v-model="indColumn.type" class="form-control">
                            <option value="index">INDEX</option>
                            <option value="unique">UNIQUE</option>
                            <option value="primary">PRIMARY (composite)</option>
                        </select>
                    </td>
                    <td>
                        <select v-model="indColumn.columns" class="form-control" multiple="multiple">
                            <option v-for="option in columns" v-bind:value="option">
                                {{ option }}
                            </option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-default btn-sm" @click='removeItem(item)'><i class="fa fa-trash"></i> </button>
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


export default {

    props: {
        columns : {
            type: Array,
            required: true
        },
        indexes: {
            type: Array,
            required: true
        }
    },

    methods: {

        addIndex () {
            var newIndex = {
                type: null,
                columns: []
            };

            this.indexes.push(newIndex);
        },

        removeItem (item) {
            var index = this.indexes.indexOf(item);
            this.indexes.splice(index, 1)
        }
    }
}

</script>