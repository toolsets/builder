<template>
    <div class="list-panel scrollable">
        <div class="title-header">
            {{ title }}
        </div>
        <div class="toolbar">
            <a class="btn btn-primary" title="Add" v-on:click.stop="onCreate()">
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="list-group">
            <a  v-for="item in list" v-bind:class="{ active: isSelected(item) }" class="list-group-item" v-on:click.stop="onItemClick(item)">{{ item[display] }}</a>
        <div>
    </div>
</template>

<script>
export default {

    props: {
        title: String,

        list: {
            type: Array,
            required: true
        },

        display: {
            type: String,
            required: true
        },

        keyBy: {
            type: String,
            required: true
        },

        selected: null
    },


    methods: {

        onCreate: function () {
            this.$emit('create')
        },

        onItemClick: function (item) {
            this.$emit('selected', item)
        },

        isSelected: function (item) {
            console.log('isSelected', this.selected, item[this.keyBy]);
            return this.selected == item[this.keyBy];
        }
    }
}

</script>

<style lang="sass" scoped>
.toolbar {
    padding:8px;
    background-color: #ebeaee;

    .btn {
        font-size: .8em;
    }
}
.list-group {
    font-size: 1em;
}
</style>