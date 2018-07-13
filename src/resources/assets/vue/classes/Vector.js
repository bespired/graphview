import Point from '@Classes/Point.js';

export default class Vector {

	constructor(p1, p2) {
		this.p1 = p1 || new Point(0,0);
		this.p2 = p2 || new Point(0,0);
	};

	degrees(q1, q2) {
		const p1 = q1 || this.p1;
		const p2 = q2 || this.p2;
		return p1.degreeTo(p2);
	};

	distance(q1, q2) {
		const p1 = q1 || this.p1;
		const p2 = q2 || this.p2;
		return p1.distanceTo(p2);
	};

	width(q1, q2) {
		const p1 = q1 || this.p1;
		const p2 = q2 || this.p2;
		return p1.widthTo(p2);
	};

	height(q1, q2) {
		const p1 = q1 || this.p1;
		const p2 = q2 || this.p2;
		return p1.heigtTo(p2);
	};

	manhattan(q1, q2) {
		const p1 = q1 || this.p1;
		const p2 = q2 || this.p2;
		return p1.manhattanTo(p2);
	};

	// http://www.jeffreythompson.org/collision-detection/line-point.php
	linePoint(q1, q2, mp) {
		const p1 = q1 || this.p1;
		const p2 = q2 || this.p2;

  		// get distance from the point to the two ends of the line
  		let d1 = this.distance(p1, mp);
  		let d2 = this.distance(p2, mp);

		// get length of the line-segment
  		let ll = this.distance(p1, p2);
  		let fuzzy = 0.1;

  		// if the two distances are equal to the line's length, the point is on the line!
  		return (d1+d2 >= ll-fuzzy && d1+d2 <= ll+fuzzy);
	}


}

