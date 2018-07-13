<template>
    <div>
    	<h2 class="sidebar-title">Attributes</h2>
    	<label for='i-name'>Name</label>
    	<input type="text" id='i-name'
            v-model="payload.name"
            @change="pluralize()"
        >
    	<h2 class="sidebar-title">Properties</h2>
        <div v-for= "(data, name) in keys" class="key">
            <a class="icon"><i class="icon-key"></i></a>
            <a class="icon"><i class="icon-lock"></i></a>
                {{ name }}
            <a class="icon right"><i class="icon-trash"></i></a>
        </div>
        <div v-for= "(data, name) in properties" class="prop">
            <a class="icon"><i class="icon-string"></i></a>
            <a class="icon"><i class="icon-lock"></i></a>
                {{ name }}
            <a class="icon right" @click="trash(name)"><i class="icon-trash"></i></a>
        </div>
        <div class="create">
            <i class="icon-string"></i>
            <i class="icon-lock"></i>
            <input type="text" id='i-property' v-model="new_property" @change="singelize()">
        </div>
    	<!-- <label>This node has no properties</label> -->
    </div>
</template>

<script>
    export default {
        name: 'NodeProperies',

        data(){
            return {
                payload  : {
                	name: '',
                    props: {},
                },
                new_property: '',
            };
        },

        mounted() {
            this.$root.$on('node-select', (data)=>{
                this.payload = data;
                console.log(data);
            });
        },

        computed: {

            keys: function(){
                if (_.size(this.payload.props) == 0 ) return {};
                return this.payload.props.keys;
            },

            properties: function(){
                if (_.size(this.payload.props) == 0 ) return {};
                return this.payload.props.strings;
            },

        },

        methods: {
            trash(name){
                console.log(name);
                if( undefined !== this.payload.props.strings[name]){
                    Vue.delete(this.payload.props.strings, name);
                    return;
                }
                if (undefined !== this.payload.props.keys[name]){
                    Vue.delete(this.payload.props.keys, name);
                    return;
                }
            },

            pluralize() {
                this.payload.name = _.deburr(global.pluralize(this.payload.name))
                this.payload.name = _.startCase(_.camelCase(this.payload.name));
                this.payload.name = this.payload.name.length === 0 ? 'Nodes' : this.payload.name;

                if ( this.payload.name === 'Migrations' ) this.payload.name = 'Transfers';
            },

            singelize(){
                const reserved= ['','suid','domain','tag','created_by','deleted_at','created_at','updated_at'];
                let str= this.new_property;
                str = _.deburr(global.pluralize(str, 1));
                str = _.snakeCase(str.toLowerCase());
                str = _.trim(str.replace(/^\d+\s*/, ''));
                this.new_property  = str;
                if ( reserved.indexOf(str) !== -1 ) return;
                if ( this.payload.props === undefined ) this.payload.props = {};
                if ( this.payload.props.strings === undefined ) this.payload.props.strings = {};

                this.payload.props.strings[str] = [];
                this.new_property  = '';
            }
        }

    }
</script>
