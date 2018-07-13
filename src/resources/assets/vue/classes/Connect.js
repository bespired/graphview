import Point from '@Classes/Point.js';

export default class Connect {

	constructor(s_node) {
        const g = global.constants; // see classes/Constant for values
        this.s_node = s_node;
        this.e_node = null;
        this.p1 = new Point(
            this.s_node.draw.left + g.nwh,
            this.s_node.draw.top  + g.nhh
        );
        this.lp1 = new Point( 0,0 );
        this.lp2 = new Point( 0,0 );
	};

    moved(e_node){

        const s_node = this.s_node;
        const g  = global.constants; // see classes/Constant for values

        let p1 = this.p1;
        let p2 = new Point(
                global.mouse.x,
                global.mouse.y,
            );

        this.width  = g.as + p1.widthTo(p2);
        this.height = g.as + p1.heightTo(p2);

        const svgleft = Math.min(p1.x, p2.x) - g.ash;
        const svgtop  = Math.min(p1.y, p2.y) - g.ash;

        this.position = `left: ${svgleft}px; top: ${svgtop}px;`;


        const degin  = p1.degreesTo(p2);
        const degout = p2.degreesTo(p1);

        const dir = 'out';

        let w1 = p1.clone();
            w1.offset(-svgleft , -svgtop);
            w1.offset(
                    Math.floor( g.shape.offset(s_node.type, degout, dir, 'out').x),
                    Math.floor( g.shape.offset(s_node.type, degout, dir, 'out').y)
            );

        let w2 = p2.clone();
            w2.offset(-svgleft , -svgtop);

        if ((this.e_node !== undefined) && (this.e_node !== null) && (this.e_node !== s_node)) this.e_node.on_edge = false;
        if (e_node !== undefined) e_node.on_edge = true;

        this.e_node = e_node;
        this.style = `edge-${dir}-dir`;

        this.lp1 = new Point( w1.x, w1.y );
        this.lp2 = new Point( w2.x, w2.y );

        this.abs = {
            p1 : new Point( svgleft + w1.x, svgtop + w1.y ),
            p2 : new Point( svgleft + w2.x, svgtop + w2.y ),
        };

        this.abs.left   = Math.min(this.abs.p1.x, this.abs.p2.x ) - 10;
        this.abs.right  = Math.max(this.abs.p1.x, this.abs.p2.x ) + 10;
        this.abs.top    = Math.min(this.abs.p1.y, this.abs.p2.y ) - 10;
        this.abs.bottom = Math.max(this.abs.p1.y, this.abs.p2.y ) + 10;

    };



}