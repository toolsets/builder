<template>
    <list-panel
            v-on:create="createNew"
            v-on:selected="tableSelected"
            :list="list"
            :selected="selectedIndex"
            keyBy="table_name"
            display="table_name"
            title="Tables">
    </list-panel>
</template>
<script>
import { mapState, mapGetters } from 'vuex'
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
                vm.$store.dispatch(types.DB_NAMESPACE + '/' + types.SET_SELECTED_ITEM_INDEX, to.params.key)
            } else {
                vm.$store.dispatch(types.DB_NAMESPACE + '/' + types.SET_SELECTED_ITEM_INDEX, null)
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
                this.$store.dispatch(types.DB_NAMESPACE + '/' + types.SELECTED_ITEM, item)
                this.$router.push({
                    path: '/database/'+ item.table_name
                });
            } else {
                vm.$store.dispatch( types.DB_NAMESPACE + '/'  + types.SELECTED_ITEM, null)
            }
        },

        fetch() {
            this.$store.dispatch(types.DB_NAMESPACE + '/'  + types.GET_TABLES);
        }
    },

    mounted() {
        this.fetch();
    }
}
</script>