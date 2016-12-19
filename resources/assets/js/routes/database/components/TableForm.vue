<template>
    <form-panel
            :formdata="selectedItem"
            title="View">
    </form-panel>
</template>
<script>
import { mapState } from 'vuex'
import FormPanel from '../../../layouts/ui/FormPanel.vue'

export default {

    components: {
        'form-panel': FormPanel
    },

    computed: mapState('database',['selectedItem']),

    methods: {

        updateFormData : function(formData)
        {
            console.log('updateFormData method fired');
            this.formData = formData;
        }
    },
//
//    beforeRouteEnter (to, from, next) {
//        next(vm => {
//            console.log('to', to);
//            vm.selectedIndex = to.params.key
//        })
//    },





    mounted() {
        console.log('Table Form Component mounted.')
        //this.listenOnBus('database.table.selected',  this.updateFormData);
    }
}
</script>