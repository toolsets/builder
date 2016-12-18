<template>
    <div class="list-view-layout">
        <div class="list-panel">
            <router-view  name="list">
        </div>

        <div class="list-view-panel">
            <router-view  name="form"></router-view>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            transitionName: 'slide-left'
        }
    },

    methods() {

    }
}
</script>

<style lang="sass" scoped>
.list-view-layout {
    position: relative;
    height:100%;
    top:0;
    bottom:0;
    left:0;
    right:0;
    background-color: #1b6d85;

    .list-panel {
        width: 350px;
        position: absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
        background-color: #cdcdcd;
    }

    .list-view-panel {
        position: absolute;
        top:0;
        left:350px;
        right:0;
        bottom:0;
    }
}

</style>