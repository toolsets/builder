<template>
    <div class="ui-split-view">
        <div class="ui-split-list-view" v-if='mode != "right"'>
            <router-view  name="list">
        </div>
        <div class="ui-split-detail-view" v-if="mode != 'left' && $mq.resize && $mq.above('1024px')">
            <router-view  name="form"></router-view>
        </div>
    </div>
</template>
<style lang="sass">
    .ui-split-view {
        position: relative;
        display: flex;
        flex-direction: row;

        .ui-split-list-view {
            flex:1;
        }

        .ui-split-detail-view {
            flex:3;
        }
    }
</style>
<script>
    export default{

        props: {
            mode : {
                type: String,
                default: 'both' //can be both or left or right
            }
        },
        data(){
            return{
                msg:'hello vue'
            }
        }
    }
</script>
