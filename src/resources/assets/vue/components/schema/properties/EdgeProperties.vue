<template>
    <div>
    	<h2 class="sidebar-title">Attributes</h2>
    	<label for='i-name'>Name</label>
    	<input type="text" id='i-name' v-model="payload.name">

    	<h2 class="sidebar-title">Properties</h2>
    	<label>This edge has no properties</label>
    </div>
</template>

<script>
    export default {
        name: 'NodeProperies',

        data(){
            return {
                payload  : {
                	name: ''
                },
            };
        },

        mounted() {
            this.$root.$on('edge-select', (data)=>{
                this.payload = data;
            });
		},

    }
</script>
