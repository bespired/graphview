<?php

namespace Bespired\Graphview\Traits;

trait ScaffoldingRoute {

	public function routePath($name) {

		return realpath(app_path('../routes')) . '/' . $name;

	}

	private function saveRoutes($types) {

		$gets = join('|', $types['get']);
		$sets = $gets;
		$finds = join('|', $types['find']);

		$routes = [
			[
				"_/get/{type}/{suid}",
				"getNode",
			], [
				"_/get/{type}/{suid}/with/{node1}/{node2?}/{node3?}",
				"getNode",
			], [
				"_/get/{type}/{suid}/with/{node2}/via/{node1}",
				"getNodeVia",
			], [
				"_/get/{type}/{suid}/with/all",
				"getNodeAll",
			], [
				"_/get/{type}/by/{property_name}/{property_value}",
				"getNodeBy",
			], [
				"_/get/{type}/by/{property_name}/{property_value}/with/{node}",
				"getNodeBy",
			], [
				"_/find/{type}/by/{property}/{search}",
				"findNode",
			], [
				"_/find/{type}/like/{property}/{search}",
				"findNodeLike",
			], [
				"_/find/{type}/by/{property}/{search}/with/{node1}/{node2?}/{node3?}",
				"findNode",
			], [
				"_/find/{type}/like/{property}/{search}/with/{node1}/{node2?}/{node3?}",
				"findNodeLike",
			], [
				"_/find/{type}/like/{property}/{search}/with-all",
				"findAll",
			], [
				"_/set/token/{token}",
				"setNode",
			], [
				"_/set",
				"setNode",
			], [
				"_/set/{token}/{data}",
				"setNodeToken",
			],
		];

		$laravel_routes = $this->routing($routes, $gets);

		file_put_contents($this->routePath('graphview.php'), $this->routeFiller($laravel_routes));

		$web = file_get_contents($this->routePath('web.php'));
		if (strpos($web, 'graphview.php') === false) {
			$web = $web . "\n\n" . '@include_once "graphview.php";' . "\n";
			file_put_contents($this->routePath('web.php'), $web);
		}

	}

	private function routing($routes, $gets) {
		$laravel = [];
		foreach ($routes as $route) {
			$ex = explode('/', $route[0])[1];
			$path = $route[0];
			$func = $route[1];
			$where = "\n\t->where(['type' => '(${gets})'])";
			$rest = ($ex != 'set') ? 'get' : 'post';
			if ($ex == 'set') {$where = '';}
			if ($func == 'setNodeToken') {$rest = 'get';}

			$laravel[] = "Route::${rest}('${path}', 'GraphController@${func}')" . $where . ";\n";
		}
		return $laravel;
	}

}
