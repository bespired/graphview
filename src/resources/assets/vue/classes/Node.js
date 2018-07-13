export default class Node {

	constructor(node) {
        if (!node) node = this.newNode();

		this.name     = node.name;
		this.selected = node.selected;
		this.suid     = node.suid;
		this.type     = node.type;
		this.draw     = node.draw;
		this.spot     = _.clone(this.draw);
        this.props    = node.props;

		this.draw.zindex = node.selected ? 3 : 1;
        this.position = `
            z-index: ${node.draw.zindex};
            top: ${node.draw.top}px;
            left: ${node.draw.left}px;
        `;
	};

    create(hover) {
        this.name        = 'New ' + global.constants.names[hover.type];
        this.type        = hover.type;
        this.draw.top    = hover.rect.top  - 52;
        this.draw.left   = hover.rect.left - 24;
        this.draw.zindex = 3;
        this.props       = { 'keys' : {}, 'strings' : {} };
        this.selected    = true;
        this.position    = `
            z-index: ${this.draw.zindex};
            top: ${this.draw.top}px;
            left: ${this.draw.left}px;
        `;
    };

    newNode() {
        return {
            name : "New Node",
            suid : new Date().getTime().toString(32).substr(2,8).toUpperCase(),
            type : "multiple-public",
            draw : { top: 0, left: 0, zindex: 1 },
            selected : false
        };
    };

	move() {
        let inLegenda = this.draw.left - 120;
        let scale = Math.max( 0.7, 1-(-inLegenda / 450));
        this.draw.top  = this.spot.top  + global.movepoint.y;
        this.draw.left = this.spot.left + global.movepoint.x;
        this.draw.size = inLegenda > 0 ? 1 : scale;
        this.draw.zindex = this.selected ? 3 : 0;
	};

	moved() {
        this.position = `
            z-index: ${this.draw.zindex};
            top: ${this.draw.top}px;
            left: ${this.draw.left}px;
            transform: scale(${this.draw.size});
        `;
	};

	stopped() {
        this.spot = _.clone(this.draw);
	};



}

