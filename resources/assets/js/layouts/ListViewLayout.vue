<template>
    <div class="list-view-layout">
        <div class="list-panel">
            <slot name="list">
                List of items in here
            </slot>
        </div>

        <div class="list-view-panel">
            <slot name="view">
                List view here
            </slot>
        </div>
    </div>
</template>

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
}

</style>