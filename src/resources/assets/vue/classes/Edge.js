export default class Edge {

	constructor(edge) {
		this.suid       = edge.suid       || new Date().getTime().toString(32).substr(2,8).toUpperCase();
		this.type       = edge.type       || 'single';
		this.direction  = edge.direction  || 'out';
		this.startpoint = edge.startpoint || null;
		this.endpoint   = edge.endpoint   || null;
	};

	create(start){
		return{
			suid       : new Date().getTime().toString(32).substr(2,8).toUpperCase(),
			type       : 'single',
			direction  : 'out',
			startpoint : start,
			endpoint   : '',
			draw       : null,
		}
	};

	connect(node, start){

		// add edge to draw if none between to nodes.
		// add relation to the (draw)edge

		// let sn_suid = global.on_edge.suid;
		// let en_suid = global.connect.suid;
		// let offset = 0;

		// vuedata.graphic.edges.forEach(function(n, i){
		// 	if ((n.startpoint === sn_suid) && (n.endpoint === en_suid)){
		// 		offset++;
		// 	}
		// 	if ((n.startpoint === en_suid) && (n.endpoint === sn_suid)){
		// 		offset++;
		// 	}
		// });

		// return{
		// 	name       : this.name,
		// 	suid       : new Date().getTime().toString(32).substr(2,8).toUpperCase(),
		// 	type       : 'has-tag',
		// 	direction  : 'out',
		// 	startpoint : sn_suid,
		// 	endpoint   : en_suid,
		// 	offset     : offset, // how many different edges between this start and end node
		// 	draw       : null,
		// }

	};

}

