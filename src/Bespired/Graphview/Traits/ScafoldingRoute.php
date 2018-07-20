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

		$this->routeservice();

	}

	private function routeservice() {
		$rspfile = app_path('Providers/RouteServiceProvider.php');
		$rsp = file_get_contents($rspfile);

		if (strpos($rsp, '$this->mapGraphRoutes') === false) {
			$pos = strrpos($rsp, '}');
			$stub = $this->getStub('routeservice', $this->stubsPath('routeservice.stub'));
			$rsp = substr_replace($rsp, $stub, $pos, 0);

			$re = '/function map\(([\s\S])*(})/muU';
			preg_match_all($re, $rsp, $matches, PREG_OFFSET_CAPTURE, 0);
			$pos = $matches[1][0][1];
			$stub = "\t\t\$this->mapGraphRoutes();\n";
			$rsp = substr_replace($rsp, $stub, $pos, 0);

			file_put_contents($rspfile, $rsp);

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
