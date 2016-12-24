<template>
    <list-panel
            v-on:create="createNew"
            v-on:selected="tableSelected"
            :list="list"
            :selected="getSelectedItem.table_name"
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
            if(to.params.key)
            {
                   console.log('param key = ' + to.params.key);
                vm.$store.dispatch('database/selectedItem', to.params.key)
            }
            else
            {
                console.log('no key found');
                vm.$store.dispatch('database/selectedItem', null)
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

        tableSelected(item){

            if(item)
            {
            console.log('table item clicked:', item.table_name);
                this.$store.dispatch('database/selectedItem', item.table_name)

                this.$router.push({
                    path: '/database/'+ item.table_name
                });

            }
            else
            {
                vm.$store.dispatch('database/selectedItem', null)
            }

        },

        fetch()
        {
            console.log('firing dispath to get Tables');
            this.$store.dispatch('database/getTables');
        }
    },

    mounted() {
        console.log('Db Tables Component mounted.')

        this.fetch();
    }
}
</script>