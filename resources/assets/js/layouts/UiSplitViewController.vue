<template>
    <div class="ui-split-view">
        <div class="ui-split-list-view" v-if="showLeft">
            <div class="scrollable-container">
                <router-view  name="list"></router-view>
            </div>
        </div>

        <div class="ui-split-detail-view" v-if="showRight">
            <div class="scrollable-container">
                <router-view  name="form"></router-view>
            </div>
        </div>

    </div>
</template>
<style lang="sass">
    .ui-split-view {
        position: relative;
        display: flex;
        flex-direction: row;
        transition: all .3s;

        .ui-split-list-view {
            flex:1;
            position: relative;
            top:0;
            bottom:0;

            .scrollable-container {
                position: absolute;
                top:0;
                left:0;
                right:0;
                bottom:0;
                overflow-y: auto;
                width:100%;
            }
        }

        .ui-split-detail-view {
            flex: 3;
            position: relative;
            top:0;
            bottom:0;

            .scrollable-container {
                position: absolute;
                top:0;
                left:0;
                right:0;
                bottom:0;
                overflow-y: auto;
                width:100%;
            }
        }
    }

</style>
<script>
    export default{

        data () {
          return {
              transitionName: 'fade',
              routeDepth: 1,
              isSmallViewPort: false
          }
        },

        props: {
            mode : {
                type: String,
                default: 'both' //can be both or left or right
            },
            vpSmallSize : {
                type: Number,
                default: 1200
            }
        },

        computed: {

            layoutMode() {

                this.isSmallViewPort = this.$mq.below(this.vpSmallSize);

                if (this.routeDepth > 2) {
                    if (this.isSmallViewPort) {
                        console.log('layout mode: right');
                        return 'right';
                    } else {
                        return 'both'
                    }
                } else {
                    if (this.isSmallViewPort) {
                        return 'left';
                    } else {
                        return 'both'
                    }
                }
            },

            showLeft(){
                return this.layoutMode !== 'right';
            },

            showRight() {
                return this.layoutMode !== 'left';
            }

        },

        beforeRouteEnter (to, from, next) {
            next(vm => {
                const toDepth = to.path.split('/').length;
                vm.routeDepth = toDepth;
            });
        },

        mounted()
        {
            this.isSmallViewPort = this.$mq.below(this.vpSmallSize);
        },

        watch: {
            '$route': function(to, from) {
                const toDepth = to.path.split('/').length;
                this.routeDepth = toDepth;
            },

            '$mq.resize': 'screenResize'
        },

        methods: {

            screenResize() {
                this.isSmallViewPort = this.$mq.below(this.vpSmallSize);
            }
        }
    }
</script>
