<template>
    <div>
        <h2>Form View</h2>
        <div v-if='formdata'>
            <div>
                {{formdata.details}}
            </div>
        </div>
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