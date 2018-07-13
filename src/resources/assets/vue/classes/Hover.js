import Point from '@Classes/Point.js';

export default class Hover {

	constructor() {
	};

	set_hovering(node, mouse) {

		let local  = _.clone(mouse);
		local.offset(-node.draw.left, -node.draw.top);

		let center = new Point( global.constants.nwh, global.constants.nhh );

		const shape    = node.type;
		const distance = center.distanceTo(local);
		const degree   = center.degreesTo(local);
		const incenter = global.constants.shape.in_center(shape, degree, distance);

		node[incenter ? 'selected' : 'on_edge'] = true;

		return incenter;
	};

}

