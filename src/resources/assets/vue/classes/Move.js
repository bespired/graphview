import Point from '@Classes/Point.js';

export default class Move {
	constructor() {
	};


	hovering(nodes){
		let hover = null;
		_.forEach(nodes, (node) => {
			if (node.selected) hover= node;
			if (node.on_edge)  hover= node;
		});
		return hover;
	};

	moving(nodes, arrows) {
		_.forEach(nodes, (node) => {
			if (node.selected) {
				node.move();
				node.moved();
			}
		});
		_.forEach(arrows, (arrow) => {
			arrow.moved(nodes);
		});
	};


	starting(mode) {
		switch(mode) {
			case  'create-hover':
				return 'create-start';

			case  'node-hover':
				return 'node-start';

			case 'edge-hover':
				return 'arrow-start';

			case 'arrow-hover':
				return 'arrow-start';
		}
		return mode;
	};

	stopping(mode, nodes, arrows, connect) {
		console.log(mode);
		switch(mode) {

			// no move move is node select I guess.
			case 'node-start':
				return 'node-select';

			case 'arrow-start':
				return 'arrow-select';

			case 'node-move':
				_.forEach(nodes, (node) => {
					if (node.selected) {
						node.stopped();
					}
				});
				return 'node-move-stopped';

			case 'arrow-move':
				// recreate relation?
				return '';

			case 'arrow-create':
			    // document.getElementById('info').innerHTML = '?';
			    // console.log(connect);
				// create relation?
				return 'connect-end';
		}

		return '';
	};

	press(nodes) {
		const hovers = _.filter(nodes, function(n) {
			return n.selected;
		});
		_.forEach(hovers, (node) => {
			node.moving = true;
		});
	};

	release(nodes) {
		const movers = _.filter(nodes, function(n) {
			return n.moving;
		});
		_.forEach(movers, (node) => {
			node.moving = false;
		});
	};

	selectedNode(nodes) {
		let selected = null;
		_.forEach(nodes, (node) => {
			if (node.selected) {
				selected = node;
			}
		});
		return selected;
	};

	selectedArrow(arrows) {
		let selected = null;
		_.forEach(arrows, (arrow) => {
			if (arrow.hover !== '') {
				selected = arrow;
				console.log( '-->', arrow);

			}
		});
		return selected;
	};


	connectable(nodes, ignore) {
		let selected = null;
		_.forEach(nodes, (node) => {
			if ((ignore === undefined ) || ((ignore !== undefined ) && (node.suid != ignore.suid))){
				if ((node.selected) || (node.on_edge)){
					selected = node;
				}
			}
		});
		return selected;
	};

	hovered(hovers) {

		if (_.size(hovers.creates)){
			return 'create-hover';
		}

		let mode = '';
		let incenter;

		_.forEach(hovers.nodes, (node) => {
			incenter = global.constants.hover.set_hovering(node, global.mouse);
			mode = incenter ? 'node-hover' : 'edge-hover';
		});

		_.forEach(hovers.arrows, (arrow)=>{
			const point = new Point( global.mouse.x, global.mouse.y );

			if ( point.linePoint(arrow.abs.p1, arrow.abs.p2) ) {
				arrow.hover = 'arrow-hover';
				mode = 'arrow-hover';
			}
			if ( point.distanceTo(arrow.abs.p1) < 15 ) {
				arrow.hover = 'arrow-start-hover';
				mode = 'arrow-start-hover';
			}
			if ( point.distanceTo(arrow.abs.p2) < 15 ) {
				arrow.hover = 'arrow-end-hover';
				mode = 'arrow-end-hover';
			}
		});

		if (_.startsWith(mode, 'arrow-')) {
			_.forEach(hovers.nodes, (node) => {
				node.selected = false;
				node.on_edge  = false;
			});
		}


		return mode;
	};

	hover(evt, nodes, arrows, clear) {
		let hovernodes={}, hoverarrows={}, hovercreates={}, hovernew, suid, hovid;

		if ( clear === true ){
			_.forEach(nodes, (node) => {
				node.selected = false;
				node.on_edge  = false;
				node.zindex   = 1;
			});
			_.forEach(arrows, (arrow) => {
				arrow.hover    = '';
				arrow.zindex   = 1;
			});
		};

		if (hovid = evt.srcElement.getAttribute('data-create')) {
			hovercreates.type= hovid;
			hovercreates.left= global.mouse.x;
			hovercreates.top = global.mouse.y;
			hovercreates.rect = evt.srcElement.getBoundingClientRect();
		}

		if (suid = evt.srcElement.getAttribute('data-suid')) {

			let type = evt.srcElement.tagName.toLowerCase();

			if (type === '') return;

			let incenter;
			_.forEach(nodes, (node) => {
				if (node.suid == suid) {
					hovernodes[suid] = node;
				}
			});

			_.forEach(arrows, (arrow)=>{
				if (( global.mouse.x > arrow.abs.left ) && ( global.mouse.x < arrow.abs.right )) {
					if (( global.mouse.y > arrow.abs.top ) && ( global.mouse.y < arrow.abs.bottom )) {

						const point = new Point( global.mouse.x, global.mouse.y );

						if ( point.linePoint(arrow.abs.p1, arrow.abs.p2) ) {
							hoverarrows[arrow.suid] = arrow;
						}
						if ( point.distanceTo(arrow.abs.p1) < 15 ) {
							hoverarrows[arrow.suid] = arrow;
						}
						if ( point.distanceTo(arrow.abs.p2) < 15 ) {
							hoverarrows[arrow.suid] = arrow;
						}
					}
				}
			});

		}

		return { nodes:hovernodes, arrows:hoverarrows, creates:hovercreates };
	};

}

