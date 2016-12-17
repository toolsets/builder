<template>
    <list-view-layout>
        <div slot="list">
            <div class="panel-header">
                Tables
            </div>
            List of db tables here
        </div>
        <div slot="view">
            <div class="panel-header">
                Create Table
            </div>
            <section class='form-highlighted'>
                Here we put the form element
            </section>
            <section class="form-highlighted">
                Here we show table columns
            </section>
        </div>
    </list-view-layout>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style lang='sass' scoped>
    .form-highlighted {
        background-color: #E4E4E4;
    }
</style>
