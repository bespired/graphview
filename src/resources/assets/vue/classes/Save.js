export default class Save {

	constructor() {
	}

	savedata() {

		this.graphicToSchema();

		console.log(global.vuedata.schema.nodes);

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
