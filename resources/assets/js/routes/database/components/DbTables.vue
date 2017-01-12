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

export default {

    components: {
        'list-panel': ListPanel
    },

    computed: {
        ...mapGetters('database',['getSelectedItem']),
        ...mapState('database',['list', 'selectedItem', 'selectedIndex']),
    },

    beforeRouteEnter (to, from, next) {
        next(vm => {
            console.log('beforeRouteEnter', vm.$store);
            if(to.params.key) {
                vm.$store.dispatch('database/setSelectedItemIndex', to.params.key)
            } else {
                vm.$store.dispatch('database/setSelectedItemIndex', null)
            }
        });
    },

    methods: {
        createNew() {
            console.log('updated');
            console.log('parent: child fired create event');
            this.$router.push({
                path: '/database/create'
            });
        },

        tableSelected(item) {

            if(item) {
                this.$store.dispatch('database/selectedItem', item)
                this.$router.push({
                    path: '/database/'+ item.table_name
                });
            } else {
                vm.$store.dispatch('database/selectedItem', null)
            }
        },

        fetch() {
            this.$store.dispatch('database/getTables');
        }
    },

    mounted() {
        this.fetch();
        console.log('mounted');
    }
}
</script>