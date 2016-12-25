<template>
    <div class="schema-form-editor"></div>
</template>
<script>

export default {


    data() {
      return {
          editorInstance : null
      }
    },

    props: {
        schema: {
            type: Object,
            default: {},
            required: true
        },

        hooks: Function
    },

    watch: {
      'schema' : 'refreshEditor'
    },

    methods: {

        refreshEditor: function() {
            console.log('refresh editor called');

            var currentValue = {};



            this.$nextTick(function()
            {
                if(this.editorInstance)
                {
                    currentValue = this.editorInstance.getValue();
                    console.log('current value', currentValue);

                    this.editorInstance.destroy();
                }

                console.log('new editorInstance before set ', this.editorInstance);
                console.log('schema', this.schema);

                this.editorInstance = new JSONEditor(this.$el,{
                    schema: this.schema
                });

                console.log('new editorInstance after set ', this.editorInstance);


            });



            //this.applyHooks();
        },

        applyHooks: function() {

//            if(this.editorInstance)
//            {
//                var hooks = this.hooks();
//
//                if(hooks.length)
//                {
//                    hooks.map(function(item)
//                    {
//                        console.log('hooking ' + item.path);
//                        this.editorInstance.watch(item.path, function()
//                        {
//                            console.log('root this', this);
//                            item.callback(editor);
//                        });
//                    });
//                }
//            }

        }
    },

    mounted()
    {
        this.refreshEditor();

        console.log('mounted editor', this.schema);
        console.log('$el', this.$el);
    }
}
</script>
<style lang="sass" scoped>
.schema-form-editor {
    padding: 16px;
}
</style>