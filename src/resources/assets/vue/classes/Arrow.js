import Point from '@Classes/Point.js';

export default class Arrow {

	constructor(edge, nodes) {

        this.startpoint = edge.startpoint;
        this.endpoint   = edge.endpoint;
        this.direction  = edge.direction;

        this.suid  = edge.startpoint + '.' + edge.endpoint;
        this.hover = false;
        this.count = 'one',

        this.moved(nodes);
		this.edges = [edge];

	};

    moved(nodes){

        const sp = this.startpoint;
        const ep = this.endpoint;

        const g  = global.constants; // see classes/Constant for values

        const s_node= _.filter(nodes, function(o) { return o.suid == sp; })[0];
        const e_node= _.filter(nodes, function(o) { return o.suid == ep; })[0];

        const p1 = new Point(
                s_node.draw.left + g.nwh,
                s_node.draw.top  + g.nhh
            );
        const p2 = new Point(
                e_node.draw.left + g.nwh,
                e_node.draw.top  + g.nhh
            );

        this.width  = g.as + p1.widthTo(p2);
        this.height = g.as + p1.heightTo(p2);

        const svgleft = Math.min(p1.x, p2.x) - g.ash;
        const svgtop  = Math.min(p1.y, p2.y) - g.ash;

        this.position = `left: ${svgleft}px; top: ${svgtop}px;`;

        const degin  = p1.degreesTo(p2);
        const degout = p2.degreesTo(p1);

        const dir = this.direction;

        let w1 = p1.clone();
            w1.offset(-svgleft , -svgtop);
            w1.offset(
                    Math.floor( g.shape.offset(s_node.type, degout, dir, 'out').x),
                    Math.floor( g.shape.offset(s_node.type, degout, dir, 'out').y)
            );

        let w2 = p2.clone();
            w2.offset(-svgleft , -svgtop);
            w2.offset(
                Math.floor( g.shape.offset(e_node.type, degin, dir, 'in').x),
                Math.floor( g.shape.offset(e_node.type, degin, dir, 'in').y)
            );


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

	append(edge, arrows) {
		// calculate dir
		let amounts  = ['none', 'one', 'two', 'three', 'four', 'five', 'lots'];

        const aid    = edge.startpoint + '.' + edge.endpoint;
        const found  = _.filter(arrows, function(a) { return a.suid == aid; });
		const amount = found.length < 5 ? found.length + 1 : 6

		this.count = amounts[amount];
		this.edges.push(edge);
	};

}