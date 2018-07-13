<template>
    <div>

        <h2 class="sidebar-title">Edges</h2>
        <div v-for= "(edge) in edges" class="edge">

            <label >Name</label>
            <a class="icon right" @click="trash(edge.suid)"><i class="icon-trash"></i></a>

                <input type="text"
                    v-model="edge.name"
                    @change="edgylize()"
            >
            <div class= "poprow">
                <label >Direction</label>
                <select v-model="edge.direction"  >
                    <option value="out">Out</option>
                    <option value="in">In</option>
                    <option value="both">Both</option>
                </select>
            </div>

            <div class= "poprow">
                <label>Type</label>
                <select v-model="edge.type">
                    <option value="single">Single</option>
                    <option value="multi">Multi</option>
                </select>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: 'ArrowProperies',

        data(){
            return {
                payload: {
                },
            };
        },

        computed: {
            edges: function(){
                console.log('payload edges', this.payload.edges);
                if (this.payload.edges === undefined ) return null;
                if (_.size(this.payload.edges) == 0 ) return null;
                return this.payload.edges;
            },
        },

        mounted() {
            this.$root.$on('arrow-select', (data)=>{
                console.log('data', data);
                this.payload = data;
            });
		},

    }
</script>
