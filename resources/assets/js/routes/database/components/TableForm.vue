<template>
    <form-panel
            :formdata="formData"
            title="View">
    </form-panel>
</template>
<script>
import FormPanel from '../../../layouts/ui/FormPanel.vue'

export default {

    components: {
        'form-panel': FormPanel
    },

    data: function () {
        return {
            formData : {
                details: 'hard coded'
            }
        }
    },

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
        this.listenOnBus('database.table.selected',  this.updateFormData);
    }
}
</script>