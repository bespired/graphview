<template>
    <div class="right-sidebar">
    	<node-properties  v-show="selected=='node-properties'"/>
        <edge-properties  v-show="selected=='edge-properties'"/>
        <arrow-properties v-show="selected=='arrow-properties'"/>
    </div>
</template>

<script>
    import NodeProperties   from '@Components/schema/properties/NodeProperties';
    import EdgeProperties   from '@Components/schema/properties/EdgeProperties';
    import ArrowProperties  from '@Components/schema/properties/ArrowProperties';

    export default {

        name: 'PropertyComponent',

        components: {
            NodeProperties, EdgeProperties, ArrowProperties
        },

        data(){
            return {
                selected: null
            };
        },

        mounted() {
            this.$root.$on('node-select', ()=>{
                this.selected = 'node-properties';
            });
            this.$root.$on('edge-select', ()=>{
                this.selected = 'edge-properties';
            });
            this.$root.$on('arrow-select', ()=>{
                this.selected = 'arrow-properties';
            });
		},

    }
</script>
