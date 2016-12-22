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
import { mapState } from 'vuex'
import ListPanel from '../../../layouts/ui/ListPanel.vue'

export default {

    components: {
        'list-panel': ListPanel
    },

    computed: {
        selectedItem(){
            return this.$store.state.selectedItem;
        },

        selectedIndex(){
            console.log('index computed changed', this.$store.state.selectedIndex);
            return this.$store.state.selectedIndex
        },

        ...mapState('database',['list'])

    },

    beforeRouteEnter (to, from, next) {
        next(vm => {
            console.log('beforeRouteEnter', vm.$store);
            if(to.params.key)
            {
                vm.$store.dispatch('database/selectedItem', {
                    key: to.params.key
                })
            }
            else
            {
                vm.$store.dispatch('database/selectedItem', {key:null})
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
                this.$store.dispatch('database/selectedItem', {
                    key: item.table_name
                })

                this.$router.push({
                    path: '/database/'+ item.table_name
                });

            }
            else
            {
                vm.$store.dispatch('database/selectedItem', {key:null})
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