import Arrow from '@Classes/Arrow.js';

export default class Double {

	constructor() {
	}

	arrows(edges, nodes){
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

        return arrows;
    }
}
