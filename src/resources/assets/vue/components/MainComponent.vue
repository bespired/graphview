<template>
    <div>
        <svg-markers />
        <schema-component />
        <header-component />
        <legenda-component />
        <property-component />
        <alert-success/>
        <div id="info"/>
    </div>
</template>

<script>

    import Constant          from '@Classes/Constant.js';
    import Preload           from '@Classes/Preload.js';
    import Arrow             from '@Classes/Arrow.js';
    import Edge              from '@Classes/Edge.js';
    import Node              from '@Classes/Node.js';
    import Save              from '@Classes/Save.js';

    import HeaderComponent   from '@Components/schema/HeaderComponent';
    import LegendaComponent  from '@Components/schema/LegendaComponent';
    import SchemaComponent   from '@Components/schema/SchemaComponent';
    import PropertyComponent from '@Components/schema/PropertyComponent';
    import SvgMarkers        from '@Components/draws/SvgMarkers';
    import AlertSuccess      from '@Components/alerts/AlertSuccess';

    export default {

        name: 'MainComponent',

        components: {
            AlertSuccess, HeaderComponent, LegendaComponent, PropertyComponent, SchemaComponent, SvgMarkers
        },

        data(){
            new Preload();

            global.constants = new Constant().create();
            global.save      = new Save();

            global.mouse     = null;
            global.mousedown = false;

            console.log('C:', global.constants );

            return {};
        },

        mounted() {

            this.load();

            this.$root.$on('save-schema', ()=>{
                this.save();
            });
            this.$root.$on('index-schema', ()=>{
                window.location = '../';
            });

            this.$root.$on('schema-loaded', ()=>{
                this.load();
                this.$root.$emit('schema-update');
                // location.reload();
            });

            this.$root.$on('set-schema-name', event => {
                global.vuedata.name = event;
            });

            console.log('Builder ready.');
            this.$root.$emit('schema-update');

        },

        methods: {

            load(){
                let nodes = [];
                _.forEach(global.vuedata.schema.nodes, function(node) {
                    nodes.push(new Node(node));
                });
                let edges = [];
                _.forEach(global.vuedata.schema.edges, function(edge) {
                    edges.push(new Edge(edge));
                });

                let arrows = [];
                _.forEach(edges, function(edge) {
                    let aid = edge.startpoint + '.' + edge.endpoint;
                    let exists= _.filter(arrows, function(a) { return a.suid == aid; })[0];
                    if (exists === undefined)
                    {
                        arrows.push(new Arrow(edge, nodes));
                    }else{
                        exists.append(edge, arrows);
                    }
                });

                // draw edges are different from data edges...
                global.vuedata.graphic = {
                    nodes  : nodes,
                    edges  : edges,
                    arrows : arrows
                };
            },

            save(){
                console.log('Save Schema.');
                // data edges are different from draw edges...

                axios.post(`/_/graphview/${global.vuedata.suid}/build/save`, global.save.savedata() )
                    .then(
                        (response) => {
                            console.log(response);
                            this.$root.$emit('alert-success', 'Schema is saved.');
                        },
                        (error) => {
                            console.log(error);
                            this.$root.$emit('alert-error', 'Error while saving schema.');
                        }
                );

                this.$root.$emit('saved');
            },

            import(){
            },

        }
    }
</script>
