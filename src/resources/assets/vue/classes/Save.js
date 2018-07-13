export default class Save {

	constructor() {
	}

	savedata() {

		this.graphicToSchema();

		return {
			"uuid" : global.vuedata.uuid,
  			"name" : global.vuedata.name,
  			"schema" : {
    			"nodes" : global.vuedata.schema.nodes,
    			"edges" : global.vuedata.schema.edges,
  			}
  		};
	}

	graphicToSchema() {
		this.graphicNodesToSchema();
		this.graphicEdgesToSchema();
	}

	graphicEdgesToSchema() {
		global.vuedata.schema.edges=[];
		_.each(global.vuedata.graphic.edges, (edge)=>{
			let data = {
				suid       : edge.suid,
				name       : edge.name,
				type       : edge.type,
				direction  : edge.direction,
				startpoint : edge.startpoint,
				endpoint   : edge.endpoint
			};
			global.vuedata.schema.edges.push(data);
		});
	}

	graphicNodesToSchema() {
		global.vuedata.schema.nodes=[];
		_.each(global.vuedata.graphic.nodes, (node)=>{
			let data = {
				suid : node.suid,
				name : node.name,
				type : node.type,
				draw : {
					top   : node.draw.top,
					left  : node.draw.left,
					zindex: 1
				},
				props : node.props
			};
			global.vuedata.schema.nodes.push(data);
		});
	}

}
