<?php

namespace Bespired\Graphview\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GraphController extends Controller {

	public function getNode($type, $suid, $node1 = null, $node2 = null, $node3 = null) {

		$withs = not_nulls([$node1, $node2, $node3]);
		$Class = '\\App\\Models\\' . ucFirst(camel_case(str_singular($type)));
		return $Class::whereSuid($suid)
			->with($withs)
			->first();
	}

	public function getNodeAll($type, $suid) {
		return 'todo';
	}

	public function getNodeVia($type, $suid, $node2 = null, $node1 = null) {
		return 'todo';
	}

	public function getNodeBy($type, $property, $search, $node1 = null, $node2 = null, $node3 = null) {
		$withs = not_nulls([$node1, $node2, $node3]);
		$Class = '\\App\\Models\\' . ucFirst(camel_case(str_singular($type)));
		return $Class::where($property, $search)
			->with($withs)
			->get();
	}

	public function findNode($type, $property, $search, $node1 = null, $node2 = null, $node3 = null) {
		$withs = not_nulls([$node1, $node2, $node3]);
		$Class = '\\App\\Models\\' . ucFirst(camel_case(str_singular($type)));
		return $Class::where($property, $search)
			->with($withs)
			->get();
	}

	public function findNodeLike($type, $property, $search, $node1 = null, $node2 = null, $node3 = null) {

		$withs = not_nulls([$node1, $node2, $node3]);
		$Class = '\\App\\Models\\' . ucFirst(camel_case(str_singular($type)));
		return $Class::where($property, 'like', '%' . $search . '%')
			->with($withs)
			->get();

	}

	public function findAll($type, $property, $search) {

		$withs = explode(',', config('model.with-all.' . str_name($type)));

		$Class = '\\App\\Models\\' . ucFirst(camel_case(str_singular($type)));
		return $Class::where($property, 'like', '%' . $search . '%')
			->with($withs)
			->get();

	}

	public function setNode(Request $request, $token = null) {

		\App\Handlers\PostHandler::post(objectify($request->all()));

	}

	public function setNodeToken($token, $data) {

		$keyed = \App\Handlers\PostHandler::keyed(json_decode(urldecode($data)));

		\App\Handlers\PostHandler::post($keyed);

	}

}
