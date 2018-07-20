<?php

namespace Bespired\Graphview\Traits;

trait ScaffoldingStub {

	private function getStub($type, $filename) {

		if (isset($this->stubs[$type])) {
			return $this->stubs[$type];
		}

		$this->stubs[$type] = file_get_contents($filename);
		return $this->stubs[$type];
	}

	private function migrationNodeRename($data) {
		$names = $this->build->scaffold->scaffolds->names;

		$oldName = str_name($names->{$data->suid});
		$newName = str_name($data->name);

		if ($oldName == $newName) {
			return null;
		}

		$oldEdgeName = 'edge_' . str_name(str_singular($oldName));
		$newEdgeName = 'edge_' . str_name(str_singular($newName));

		$replacers = [
			'connection' => $this->build->connection,
			'suid' => $data->suid,
			'name' => $data->name,
			'migratename' => $this->migrateNodeName($data, 'class'),
			'oldname' => $oldName,
			'newname' => $newName,
			'oldedgename' => $oldEdgeName,
			'newedgename' => $newEdgeName,
		];

		return $this->tableFiller('rename_node', $replacers);
	}

	private function migrationNodeFile($data) {

		$properties = $this->properties($data);

		if (empty($properties) && ($data->migrate_type != 'create')) {
			return null;
		}

		$replacers = [
			'connection' => $this->build->connection,
			'suid' => $data->suid,
			'name' => $data->name,
			'migratename' => $this->migrateNodeName($data, 'class'),
			'tablename' => $this->migrateNodeName($data, 'table'),
			'properties' => $properties,
		];

		$stubname = $data->migrate_type == 'create' ? 'create_node' : 'alter_node';
		return $this->tableFiller($stubname, $replacers);

	}

	private function migrationEdgeFile($edge, $props) {

		$replacers = [
			'connection' => $this->build->connection,
			'suid' => $edge->suid,
			'name' => $edge->name,
			'migratename' => $this->migrateEdgeName($edge, 'class'),
			'tablename' => $this->migrateEdgeName($edge, 'table'),
			'properties' => join("\n", $props),
		];

		$migrate_type = $this->schema[$edge->startpoint]->migrate_type;
		$stubname = $migrate_type == 'create' ? 'create_edge' : 'alter_edge';
		return $this->tableFiller($stubname, $replacers);

	}

	private function scaffoldConfigFile() {
		$config['with-all'] = [];
		$config['nodes'] = [];
		$config['keys'] = [];

		$nodes = collect($this->build->schema->nodes)->keyBy('suid');
		//	dd($this->build->schema->edges);

		foreach ($this->build->schema->edges as $edge) {
			$startnode = $nodes[$edge->startpoint];
			$endnode = $nodes[$edge->endpoint];

			$startname = str_name(str_singular($startnode->name));
			$endname = str_name($endnode->name);

			$config['with-all'][$startname][$endname] = $endname;
		}

		foreach ($config['with-all'] as $key => $keys) {
			$withAll[$key] = join(',', array_values($keys));
		}

		$config['with-all'] = $withAll;

		foreach ($this->build->schema->nodes as $node) {
			$name = str_name($node->name);
			$config['nodes'][str_singular($name)] = str_plural($name);
			$config['nodes'][str_plural($name)] = str_singular($name);

			if (isset($node->props) && isset($node->props->keys)) {
				foreach ($node->props->keys as $key => $dummy) {
					$config['keys'][str_singular($name) . '*' . $key] = $key;
				}
			}
		}

		$fnd = ["  '", "array (", "  ),"];
		$rep = ["\t'", "[", "\t],"];

		$arr = str_replace("\n", "\n\t", var_export($config, true));
		$arr = str_replace($fnd, $rep, $arr);
		$arr = rtrim($arr, ')') . '];';

		$re = '/(\t\t\'([\S]+)\' \=\>)/m';
		$subst = "\t'$2' =>";
		$arr = preg_replace($re, $subst, $arr);

		$re = '/(\t  \t\'([\S]+)\' \=\> \'([\S]+)\',)/m';
		$subst = "\t\t'$2' => '$3',";

		$arr = preg_replace($re, $subst, $arr);

		return "<?php\nreturn " . $arr;

	}

	private function scaffoldModelFile($node) {

		$singular_name = str_name(str_singular($node->name));
		$plural_name = str_name(str_plural($node->name));
		$className = ucFirst(camel_case($singular_name));

		$relations = [];
		if (isset($this->scaffold[$node->suid])) {
			foreach ($this->scaffold[$node->suid]['props'] as $relation => $dummy) {
				$relations[] = $this->model_prop($singular_name, $relation);
			}
		}

		$replacers = [
			'suid' => $node->suid,
			'name' => $node->name,
			'connection' => $this->build->connection,
			'modelname' => $className,
			'tablename' => 'node_' . $plural_name,
			'relations' => join("\n", $relations),
			'casts' => 'casts',
		];

		return $this->modelFiller('app_model', $replacers);
	}

	private function scaffoldTraitFile($node) {

		$singular_name = str_name(str_singular($node->name));
		$plural_name = str_name(str_plural($node->name));
		$className = ucFirst(camel_case($singular_name));

		$relations = [];
		if (isset($this->scaffold[$node->suid])) {
			foreach ($this->scaffold[$node->suid]['props'] as $relation => $dummy) {
				$relations[] = $this->model_prop($singular_name, $relation);
			}
		}

		$replacers = [
			'suid' => $node->suid,
			'name' => $node->name,
			'connection' => $this->build->connection,
			'modelname' => $className,
			'tablename' => 'node_' . $plural_name,
			'relations' => join("\n", $relations),
			'casts' => 'casts',
		];

		return $this->modelFiller('app_trait', $replacers);
	}

	private function tableFiller($type, $replacers = []) {

		$defaults = ['suid', 'name', 'migratename', 'connection', 'properties',
			'tablename', 'oldname', 'newname', 'oldedgename', 'newedgename'];

		$migrate_table = $this->migrationStub($type);
		foreach ($defaults as $key) {
			$value = array_get($replacers, $key, '');
			$migrate_table = str_replace('{{ ' . $key . ' }}', $value, $migrate_table);
		}

		return $migrate_table;

	}

	private function modelFiller($type, $replacers = []) {

		$defaults = ['suid', 'name', 'modelname', 'tablename', 'connection', 'casts', 'relations'];

		$model_file = $this->modelStub($type);
		foreach ($defaults as $key) {
			$value = array_get($replacers, $key, '');
			$model_file = str_replace('{{ ' . $key . ' }}', $value, $model_file);
		}

		return $model_file;

	}

	private function routeFiller($routes) {
		return str_replace('{{ routes }}', join("\n", $routes), $this->routeStub());
	}

	private function prop_key($name, $after = '') {
		$order = empty($after) ? '' : "->after('$after')";
		return "\t\t\t\t\t" . "\$table->string('$name')->index()->default('')$order;";
	}

	private function prop_string($name, $after = '') {
		$order = empty($after) ? '' : "->after('$after')";
		return "\t\t\t\t\t" . "\$table->string('$name')->nullable()$order;";
	}

	private function prop_suid($name, $after = '') {
		$order = empty($after) ? '' : "->after('$after')";
		return "\t\t\t\t\t" . "\$table->char('$name', 40)->nullable()$order;";
	}

	private function model_prop($current, $relation) {
		$stub = [];
		$belong = sprintf("->belongsToMany(\App\Models\%s::class, 'edge_%s', '%s_id', '%s_id')",
			ucFirst(camel_case(str_name($relation))), $current, $current, $relation);

		$stub[] = sprintf("\tpublic function %s() {", str_plural($relation));
		$stub[] = "\t\treturn \$this";
		$stub[] = "\t\t\t" . $belong;
		$stub[] = "\t\t\t->withPivot('suid', 'domain', 'tag', 'name');";
		$stub[] = "\t}";

		return join("\n", $stub);
	}

	private function properties($node) {
		$made = array_keys((array) $this->build->scaffold->scaffolds->made);
		$after = $node->migrate_type == 'create' ? '' : 'tag';
		$props = [];

		if (isset($node->props->keys)) {
			foreach ($node->props->keys as $key => $type) {
				$name = $this->scaffoldingName($node, $key);
				if (!in_array($name, $made)) {
					$props[] = $this->prop_key($key, $after);
					$this->build->scaffold->scaffolds->made->$name = 'key';
				}
			}
		}

		if (isset($node->props->strings)) {
			foreach ($node->props->strings as $string => $type) {
				$name = $this->scaffoldingName($node, $string);
				if (!in_array($name, $made)) {
					$props[] = $this->prop_string($string, $after);
					$this->build->scaffold->scaffolds->made->$name = 'string';
				}
			}
		}

		return join("\n", $props);
	}
}
