import Shape  from '@Classes/Shape.js';
import Hover  from '@Classes/Hover.js';
import Move   from '@Classes/Move.js';


export default class Constant {

	constructor() {
	};

	create(){

		const nw = 160; // node width
        const nh =  80; // node height
        const as =  20; // arrow space
        const d2r = 0.0174532925;

        const sf = 60 / nw; // shape factor, 120 is used as 100%... legacy.
        const bf = sf * 0.7; // shape border factor

        const nwh = nw / 2; // half node width
        const nhh = nh / 2; // half node height
        const ash = as / 2; // half arrow space

        const nwa = nw + ash; // node width  + arrow space
        const nha = nh + ash; // node height + arrow space

        const bld = null;

        let constants = {
            nw , nh , as , d2r, nwh, nhh, ash, nwa, nha, sf, bf, bld
        };

        let names= {
            'multiple-private': 'Note',
            'multiple-public':  'Node',
            'single-public':    'Tag',
            'single-private':   'Properties',
        };

        constants.names  = names;
        constants.shape  = new Shape();
        constants.hover  = new Hover();
        constants.move   = new Move();
        constants.plural = global.pluralize;

        constants.getScroll= function(){
            if (window.pageYOffset!= undefined) {
                return {x: pageXOffset, y: pageYOffset};
            }else{
                let sx, sy, d= document, r= d.documentElement, b= d.body;
                sx= r.scrollLeft || b.scrollLeft || 0;
                sy= r.scrollTop || b.scrollTop || 0;
                return {x: sx, y: sy};
            }
        };
        constants.hasClass= function(elm, name){
            return elm.getAttribute("class").indexOf(name) > -1;
        };

        return constants;

	};

}

