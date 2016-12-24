<template>
    <div v-if="selectedItem" class="table-form">

        <div class="list-panel">
            <div class="table-section">
                <div class="title-header">
                    Table Configuration
                </div>
                <div class="toolbar">
                    <a class="btn btn-default" title="Back" v-if="showBackButton">
                        <i class="fa fa-chevron-left" v-on:click.stop="goBack()"></i>
                    </a>
                    <a class="btn btn-primary" title="Add" v-on:click.stop="onCreate()">
                        <i class="fa fa-plus"></i>
                    </a>

                </div>
                <div class="builder-form">
                    <div class="form-group input-title">
                        <label for="table_name">Table Name</label>
                        <div class="form-control">{{selectedItem.table_name}}</div>
                    </div>
                </div>
                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th class="tbl-status"></th>
                        <th>Column</th>
                        <th>Type</th>
                        <th>Length</th>
                        <th>Unsigned</th>
                        <th>Nullable</th>
                        <th>AI</th>
                        <th>PK</th>
                        <th>Default</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="col in selectedItem.columns">
                        <td v-bind:class="{ migrated: col.migrated == true, 'not-migrated': col.migrated == false}" class="tbl-status"></td>
                        <td>{{ col.attributes.name }}</td>
                        <td>{{ col.attributes.type }}</td>
                        <td>{{ col.attributes.length }}</td>
                        <td>
                            <input type="checkbox" disabled="disabled" v-model='col.attributes.unsigned' />
                        </td>
                        <td><input type="checkbox" disabled="disabled" v-model='col.attributes.nullable' /></td>
                        <td><input type="checkbox" disabled="disabled" v-model='col.attributes.autoIncrement' /></td>
                        <td><input type="checkbox" disabled="disabled" v-model='col.attributes.primaryKey' /></td>
                        <td>{{ col.attributes.default }}</td>
                    </tr>
                    </tbody>
                </table>
                <div v-if='selectedItem.relations' class="builder-form">
                    <div class="input-title">
                        <label>Table Relations</label>
                    </div>

                </div>
                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Columns</th>
                        <th>FK Table</th>
                        <th>FK Columns</th>
                        <th>On Update</th>
                        <th>On Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="fk in selectedItem.relations">
                        <td>{{ fk.index }}</td>
                        <td>{{ fk.columns }}</td>
                        <td>{{ fk.fk_table }}</td>
                        <td>{{ fk.fk_column }}</td>
                        <td>{{ fk.on_update ? fk.on_update : '' }}</td>
                        <td>{{ fk.on_delete ? fk.on_delete : '' }}</td>
                    </tr>
                    </tbody>
                </table>

                <div v-if='selectedItem.indexes' class="builder-form">
                    <div class="input-title">
                        <label>Table Indexes</label>
                    </div>

                </div>
                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Columns</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="indx in selectedItem.indexes">
                        <td>{{ indx.index }}</td>
                        <td>{{ indx.columns }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
</template>
<script>
import { mapState } from 'vuex'


export default {

    data() {

        return {
            formData : {}
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

        ...mapState('database',['selectedItem', 'selectedIndex']),
    },


    methods: {

        goBack() {
            this.$router.push({ path: '/database' })
        }
    },


    mounted() {
        console.log('Table Form Component mounted.')
        console.log(this.$store.state);
        //this.listenOnBus('database.table.selected',  this.updateFormData);
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