<template>
    <list-panel
            v-on:create="createNew"
            v-on:selected="tableSelected"
            :list="list"
            :selected="selectedIndex"
            keyBy="table_name"
            display="table_name"
            title="Tablessss">
    </list-panel>
</template>
<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import ListPanel from '../../../layouts/ui/ListPanel.vue'
import types from '../store/types'

export default {

    components: {
        'list-panel': ListPanel
    },

    computed: {
        ...mapGetters( types.DB_NAMESPACE, [ types.GET_SELECTED_ITEM ]),
        ...mapState( types.DB_NAMESPACE, ['list', 'selectedItem', 'selectedIndex']),
    },

    beforeRouteEnter (to, from, next) {
        next(vm => {
            if(to.params.key) {
                vm.selectedItemIndex(to.params.key);
            } else {
                vm.selectedItemIndex(null);
            }
        });
    },

    methods: {
        createNew() {
            this.$router.push({
                path: '/database/create'
            });
        },

        tableSelected(item) {

            if(item) {
                this.selectItem(item);
                this.$router.push({
                    path: '/database/'+ item.table_name
                });
            } else {
                this.selectItem(null);
            }
        },

        ...mapActions(types.DB_NAMESPACE, {
            fetch: types.GET_TABLES,
            selectItem: types.SELECTED_ITEM,
            selectedItemIndex: types.SET_SELECTED_ITEM_INDEX
        })
    },

    mounted() {
        this.fetch();
    }
}
</script>