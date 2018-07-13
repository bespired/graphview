export default class Preload {

	constructor() {
		var images = [];
		function preload(arr)
		{
    		for (var i = 0; i < arr.length; i++) {
        		images[i] = new Image();
        		images[i].src = arr[i];
    		}
    	}
		preload([
			'/vendor/bespired/graphview/img/schema.png',
		]);
	}

}
