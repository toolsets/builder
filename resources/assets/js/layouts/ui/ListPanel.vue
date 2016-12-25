<template>
    <div class="list-panel">
        <div class="title-header">
            {{ title }} s: {{ selected }}
        </div>
        <div class="toolbar">
            <a class="btn btn-primary" title="Add" v-on:click.stop="onCreate()">
                <i class="fa fa-plus"></i>
            </a>
        </div>
        <div class="list-group scrollable" ref="listItemGroup">
            <a  v-for="(item, index) in list" v-bind:class="{ active: isSelected(item, index) }" class="list-group-item" v-on:click.stop="onItemClick(item, index)">{{ item[display] }}</a>
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

        selected: String
    },


    methods: {

        onCreate: function () {
            this.$emit('create')
        },

        onItemClick: function (item) {
            this.$emit('selected', item)
        },

        isSelected: function (item, index) {

            if(this.selected)
            {

                if(this.selected == item[this.keyBy])
                {
                     this.$nextTick(function () {
                        var parentContainer = this.$el.querySelector('div.list-group');
                        var activeElm = parentContainer.querySelectorAll('.active');
                        if(activeElm.length)
                        {
                            var parentScrollTop = parentContainer.scrollTop;
                            var visibleHeight = parentContainer.offsetHeight;
                            var activeElementTop = activeElm[0].offsetTop;
                            var visibleViewPortEnd = parentScrollTop + visibleHeight;

                            if((activeElementTop > visibleViewPortEnd) || (activeElementTop < parentScrollTop)) {
                                parentContainer.scrollTop = activeElementTop;
                            }
                        }
                     });

                    return true;
                }

                return false;
            }

        }
    }
}

</script>

<style lang="sass" scoped>
.list-panel {
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;

}

.toolbar {
    padding:8px;
    background-color: #ebeaee;

    .btn {
        font-size: .8em;
    }
}
.list-group {
    font-size: 1em;

    &.scrollable {
        position: absolute;
        top:78px;
        bottom:0px;
        width:100%;
    }
}
</style>