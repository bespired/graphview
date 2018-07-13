<template>
    <div class="schema" :key="update">
        <draw-arrow v-for="(arrow, index) in arrows"
            :key="`arrow-${index}`"
            :arrow="arrow"
        />
        <draw-node v-for="(node, index) in nodes"
            :key="`node-${index}`"
            :node="node"
        />
        <draw-connect :arrow="connect "v-if="connect"/>
    </div>
</template>

<script>

    import Node         from '@Classes/Node.js';
    import Point        from '@Classes/Point.js';
    import Connect      from '@Classes/Connect.js';
    import DrawNode     from '@Components/draws/DrawNode';
    import DrawArrow    from '@Components/draws/DrawArrow';
    import DrawConnect  from '@Components/draws/DrawConnect';

    export default {

        name: 'SchemaComponent',

        components: {
            DrawNode,
            DrawArrow,
            DrawConnect,
        },

        data(){
            return {
                update   : 0,
                arrows   : null,
                edges    : null,
                nodes    : null,
                mode     : '',
                hover    : '',
                prevmode : '',
                connect  : null,
            };

        },

        mounted() {

            global.schemapoint = new Point();
            global.schemapoint.origin(document.getElementsByClassName("schema")[0]);

            this.$root.$on('schema-update', ()=>{
                this.arrows = global.vuedata.graphic.arrows;
                this.edges  = global.vuedata.graphic.edges;
                this.nodes  = global.vuedata.graphic.nodes;
                this.update++;
            });

            document.getElementById('app').onmousedown = (evt)=>{
                global.mousedown = true;
                global.constants.move.press(this.nodes);
                global.clickpoint= _.clone(global.mouse);

                this.mode = global.constants.move.starting(this.mode);
                this.update++;
            }

            document.getElementById('app').onmouseup = (evt)=>{
                global.mousedown = false;
                global.constants.move.release(this.nodes);

                // creation / selection / ignore?

                this.mode = global.constants.move.stopping(
                    this.mode,
                    this.nodes,
                    this.arrows,
                    this.connect
                );

                if ( this.mode == 'node-select') {
                    let node = global.constants.move.selected(this.nodes);
                    this.$root.$emit('node-select', node);
                }

                if ( this.mode == 'node-move-stopped') {
                    // mark for remove
                    _.forEach(this.nodes, (node) => {
                        if( node.draw.left < 0 ){
                            node.remove = true;
                        }
                    });
                    // what edges are effected?
                    // ...
                    // then remove nodes
                    this.nodes= _.reject(this.nodes, ['remove', true]);
                    global.vuedata.graphic.nodes = this.nodes;
                    // and the remove edges
                    // ...
                }

                if ( this.mode == 'connect-end') {
                    this.connect = null;
                }

                // start next action
                this.mode = global.constants.move.hover(evt, this.nodes, this.arrows);
                this.update++;

            }

            document.getElementById('app').onmousemove = (evt)=>{

                this.prevmode = this.mode;

                global.mouse = new Point( evt.clientX, evt.clientY );
                global.mouse.sub(global.schemapoint);

                if ( !global.mousedown ) {
                    let hovers= global.constants.move.hover(evt, this.nodes, this.arrows, true);
                    this.mode = global.constants.move.hovered(hovers);
                }

                if ( global.mousedown ) {

                    this.hover= global.constants.move.hover(evt, this.nodes, this.arrows);

                    global.movepoint = new Point( evt.clientX, evt.clientY );
                    global.movepoint.sub(global.schemapoint);
                    global.movepoint.sub(global.clickpoint);

                    if (this.mode === 'create-start' ){
                        let node = new Node(null);
                        node.create(this.hover.creates);
                        node.stopped();
                        global.vuedata.graphic.nodes.push(node);

                        console.log(node.suid);
                        this.mode= 'node-move';
                    }

                    if (this.mode === 'node-start' ){
                        this.mode= 'node-move';
                    }

                    if (this.mode === 'arrow-start' ){
                        let node = global.constants.move.hovering(this.nodes);
                        this.connect= new Connect(node);
                        this.mode= 'arrow-create';
                    }

                    switch(this.mode)
                    {
                        case 'node-move':
                            global.constants.move.moving(this.nodes, this.arrows);
                        break;

                        case 'arrow-create':
                            let key= Object.keys(this.hover.nodes)[0];
                            this.connect.moved(this.hover.nodes[key]);
                        break;
                    }

                }

                if (
                    (this.prevmode != this.mode) ||
                    (this.mode == 'node-move') ||
                    (this.mode == 'arrow-create')
                    )
                {
                    this.update++;
                }
            }

            console.log('Schema ready.');
        },

    }
</script>
