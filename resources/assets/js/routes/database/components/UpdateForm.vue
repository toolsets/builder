<template>
    <div class="table-update-form">
        UPDATE FORM VIEW HERE: {{ title }}
    </div>
</template>

<script>

export default {

    data() {
        return {
            title: 'UPDATE TABLE'
        }
    }
}
</script>
<style lang="sass">

.table-update-form {
    position: absolute;
    top: 0px;
    bottom:0px;
    right: 0px;
    width:300px;
    background-color: #303030;
    z-index: 1100;
}

</style>