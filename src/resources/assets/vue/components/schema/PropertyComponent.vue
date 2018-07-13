<template>
    <div class="right-sidebar">
    	<node-properties v-show="selected=='node-properties'"/>
        <edge-properties v-show="selected=='edge-properties'"/>
    </div>
</template>

<script>
    import NodeProperties   from '@Components/schema/properties/NodeProperties';
    import EdgeProperties   from '@Components/schema/properties/EdgeProperties';

    export default {

        name: 'PropertyComponent',

        components: {
            NodeProperties, EdgeProperties
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
		},

    }
</script>
