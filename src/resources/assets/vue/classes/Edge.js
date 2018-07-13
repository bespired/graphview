export default class Edge {

	constructor(edge) {
		if (!edge) edge = this.newEdge();

		this.suid       = edge.suid       || new Date().getTime().toString(32).substr(2,8).toUpperCase();
		this.name       = edge.name       || 'No Name';
		this.type       = edge.type       || 'single';
		this.direction  = edge.direction  || 'out';
		this.startpoint = edge.startpoint || null;
		this.endpoint   = edge.endpoint   || null;
	};

	create(startNode, endNode){
		this.suid       = this.suid  || new Date().getTime().toString(32).substr(2,8).toUpperCase();
		this.name       = this.name  || 'No Name';
		this.type       = this.type  ||  'single';
		this.direction  = this.direction || 'out';
		this.startpoint = startNode.suid;
		this.endpoint   = endNode.suid;
		this.draw       = null;
	};

    newEdge() {
        return {
            name       : "New Edge",
			suid       : new Date().getTime().toString(32).substr(2,8).toUpperCase(),
			type       : 'single',
			direction  : 'out',
			startpoint : null,
			endpoint   : null,
			draw       : null,
        };
    };

}

