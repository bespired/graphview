export default class Point {

	constructor(x,y) {
		this.x = x || 0;
		this.y = y || 0;
	};

	clone() {
		return new Point(this.x, this.y);
	};

	add(p) {
		this.x += p.x;
		this.y += p.y;
	};

	sub(p) {
		this.x -= p.x;
		this.y -= p.y;
	};

	offset(dx, dy) {
		this.x += dx;
		this.y += dy;
	};

	origin(elm) {
		this.x = elm.getBoundingClientRect().left;
		this.y = elm.getBoundingClientRect().top;
	};


	degreesTo(p) {
		const dx = this.x - p.x;
		const dy = this.y - p.y;
		const angle = Math.atan2(dy, dx); // radians
		return (angle * (180 / Math.PI) + 360 ) % 360;   // degrees
	};

	degreesToNorth(p) {
		const dx = this.x - p.x;
		const dy = this.y - p.y;
		const angle = Math.atan2(dy, dx); // radians
		return (angle * (180 / Math.PI) + 360 - 90 ) % 360;   // degrees
	};

	distanceTo(p) {
		const x = this.x - p.x;
		const y = this.y - p.y;
		return Math.sqrt(x * x + y * y);
	};

	manhattanTo(p) {
		const x = this.x - p.x;
		const y = this.y - p.y;
		return (x * x + y * y);
	};

	widthTo(p) {
		return Math.abs(this.x - p.x);
	};

	heightTo(p) {
		return Math.abs(this.y - p.y);
	};

	equals(p) {
		return this.x == p.x && this.y == p.y;
	};

	// http://www.jeffreythompson.org/collision-detection/line-point.php
	linePoint(p1, p2) {

  		// get distance from the point to the two ends of the line
  		let d1 = this.distanceTo(p1);
  		let d2 = this.distanceTo(p2);

		// get length of the line-segment
  		let ll = p1.distanceTo(p2);
  		let fuzzy = 1;

  		// if the two distances are equal to the line's length, the point is on the line!
  		return (d1+d2 >= ll-fuzzy && d1+d2 <= ll+fuzzy);
	}
}

