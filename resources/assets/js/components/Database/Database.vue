<template>
    <list-view-layout>
        <div slot="list">
            List of db tables here
        </div>
        <div slot="view">
            Table detail view here
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
