<template>
    <div>
        <h2>Form View</h2>
        {{ formdata.details }}
    </div>
</template>

<script>
export default {

    props: {
        title: String,
        formdata: null
    }
}

</script>

<style lang="sass" scoped>

</style>