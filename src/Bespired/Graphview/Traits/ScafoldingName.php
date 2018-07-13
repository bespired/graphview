<?php

namespace Bespired\Graphview\Traits;

trait ScafoldingName {

	private function migrationNameForNode($node, $type = null) {

		$tid = $this->idBySuid($node->suid);
		$inc = $this->incBySuid($node->suid);

		// alter names need something extra because
		// migrations cant have double classnames

		$tf_type = isset($type) ? $type : ($inc == 1 ? 'create' : 'alter_' . $this->number($inc));
		$tf_name = str_name(str_singular($node->name));
		$node->migrate_type = $tf_type;

		return sprintf("2018_01_%02d_%06d_%s_%s_table.php", $tid, $inc, $tf_type, $tf_name);

	}

	private function migrationNameForEdge($edge) {

		$startnode = $this->schema[$edge->startpoint];

		$tid = $this->idBySuid($startnode->suid);
		$inc = $this->incBySuid($startnode->suid);

		$tf_type = $startnode->migrate_type . '_edge';
		$s_name = str_singular(str_name($this->schema[$startnode->suid]->name));

		return sprintf("2018_01_%02d_%06d_%s_%s_table.php", $tid, $inc, $tf_type, $s_name);

	}

	private function scafoldNameForModel($node) {

		$name = str_name(str_singular($node->name));
		$className = ucFirst(camel_case($name));

		return sprintf("%s.php", $className);

	}

	private function scafoldNameForTrait($node) {

		$name = str_name(str_singular($node->name));
		$className = ucFirst(camel_case($name));

		return sprintf("%sTrait.php", $className);

	}

	private function modelPath($name, $trait = false) {

		$path = $trait ? app_path() . '/Traits' : app_path() . '/Models';
		return $path . '/' . $name;

	}

	private function migrationPath($name) {

		return database_path() . '/migrations/' . $name;

	}

	private function migrateNodeName($node, $type) {

		$name = $node->migrate_type . '_' . str_name(str_singular($node->name)) . '_table';

		if ($type == 'class') {
			return ucFirst(camel_case($name));
		}

		return str_name('node_' . $node->name);
	}

	private function migrateEdgeName($edge, $type) {

		$startnode = $this->schema[$edge->startpoint];

		$s_name = str_singular(str_name($this->schema[$startnode->suid]->name));

		if ($type == 'class') {
			$name = $startnode->migrate_type . '_edge_' . $s_name . '_table';
			return ucFirst(camel_case($name));
		}

		$name = 'edge_' . $s_name;
		return $name;

	}

	private function scafoldingName($data, $propname) {
		return strtolower($data->suid) . '.' . $propname;
	}

	private function stubsPath($name) {

		return realpath(__DIR__ . '/../../../resources/stubs/' . $name);

	}

	private function migrationStub($type) {

		$filepath = $this->stubsPath(sprintf('migrate_%s_table.stub', $type));
		return $this->getStub($type, $filepath);

	}

	private function modelStub($type) {

		$filepath = $this->stubsPath(sprintf('%s.stub', $type));
		return $this->getStub($type, $filepath);

	}

	private function routeStub() {

		return $this->getStub('routes', $this->stubsPath('route.stub'));

	}

	private function number($number) {
		$numbers = [
			'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
			'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen',
			'eightteen', 'nineteen', 'twenty',
		];
		$decimals = [
			'', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
		];
		$centimals = [
			'zero', 'ten', 'twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'zeventy', 'eighty', 'ninety',
		];
		if ($number < 21) {
			return $numbers[$number];
		}
		return $centimals[floor($number / 10)] . $decimals[$number % 10];
	}

}
