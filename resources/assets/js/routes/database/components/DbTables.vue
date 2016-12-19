<template>
    <list-panel
            v-on:create="createNew"
            v-on:selected="tableSelected"
            :list="list"
            :selected="selectedIndex"
            keyBy="id"
            display="name"
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
            return this.$store.state.database.selectedItem;
        },

        selectedIndex(){
            console.log('index computed changed', this.$store.state.database.selectedIndex);
            return this.$store.state.database.selectedIndex
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
                    key: item.id
                })

                this.$router.push({
                    path: '/database/'+ item.id
                });

            }
            else
            {
                vm.$store.dispatch('database/selectedItem', {key:null})
            }

        }
    },

    mounted() {
        console.log('Db Tables Component mounted.')
    }
}
</script>