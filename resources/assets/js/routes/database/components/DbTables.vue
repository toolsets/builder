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
import ListPanel from '../../../layouts/ui/ListPanel.vue'

const store = {

    list: [
        {
            id : 1,
            name: 'ABC',
            details: 'ABC details'
        },

        {
            id : 2,
            name: 'DEF',
            details: 'DEF details'
        },

        {
            id : 3,
            name: 'GHI',
            details: 'GHI details'
        },

        {
            id : 4,
            name: 'JKL',
            details: 'JKL details'
        }
    ],

    selectedIndex : null
};


export default {

    components: {
        'list-panel': ListPanel
    },

    data: function () {
        return store;
    },

    beforeRouteEnter (to, from, next) {
        next(vm => {

            if(vm.selectedIndex === undefined)
            {
                vm.fireOnBus('database.table.selected', null);
            }
            else
            {
                console.log('called vm.selectByKey(to.params.key);');
                vm.selectByKey(to.params.key);
            }

            console.log('params key', to.params.key);
        })
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
                console.log('parent: child selected item is ' + item.name);
                this.selectedIndex = item.id;
                this.fireOnBus('database.table.selected', item);
                this.$router.push({
                    path: '/database/'+ item.id
                });
            }
            else
            {
                this.selectedIndex = null;
                this.fireOnBus('database.table.selected', {});
            }

        },

        selectByKey(key)
        {
            var item = _.find(this.list, function(o)
            {
                return o.id == key
            });

            this.tableSelected(item);

        }
    },

    mounted() {
        console.log('Db Tables Component mounted.')
    }
}
</script>