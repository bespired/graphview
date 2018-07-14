<?php

namespace Bespired\Graphview\Scaffolds;

use Bespired\Graphview\Traits\ScaffoldingName;
use Bespired\Graphview\Traits\ScaffoldingRoute;
use Bespired\Graphview\Traits\ScaffoldingStub;
use Bespired\Graphview\Traits\ScaffoldingSuid;

class Scaffolding {

	use ScaffoldingSuid;
	use ScaffoldingStub;
	use ScaffoldingRoute;
	use ScaffoldingName;

	protected $build, $scaffold, $schema, $stubs;

	public function migrates($build) {
		$this->scaffold = $build->scaffold;
		$this->build = objectify($build);
		$this->schema = collect($this->build->schema->nodes)->keyBy('suid');
		$this->schema = $this->schema->merge(collect($this->build->schema->edges)->keyBy('suid'));

		// write nodes
		$this->writeFolders();

		// write nodes
		$this->writeNodes();

		// write edges
		$this->writeEdges();

		// write models
		$this->writeModels();

		// write config
		$this->writeConfig();

		// write controllers
		// $this->writeControllers();

		// write routes
		$this->writeRoutes();

		// fix VerifyCsrfToken.php

		// save creation data for the next time
		$this->saveScaffolding();

	}

	//

	private function writeRoutes() {

		$routes = [];

		foreach ($this->build->schema->nodes as $node) {
			// if ('multiple-public' == $node->type) {
			$name = str_name(str_singular($node->name));
			$types['get'][] = $name;
			// }
		}

		foreach ($this->build->schema->nodes as $node) {
			if ('multiple-public' == $node->type) {
				$name = str_name(str_singular($node->name));
				$types['find'][] = $name;
			}
		}

		$this->saveRoutes($types);

	}

	private function writeFolders() {
		$path = app_path() . '/Traits';
		if (!file_exists($path)) {
			@mkdir($path);
			$src = realpath(__DIR__ . '/../Traits/HasShortUuid.php');
			$dst = $path . '/HasShortUuid.php';
			$frm = 'namespace Bespired\Graphview\Traits;';
			$file = str_replace($frm, 'namespace App\Traits;', file_get_contents($src));
			file_put_contents($dst, $file);
		}

		$path = app_path() . '/Models';
		if (!file_exists($path)) {
			@mkdir($path);
		}
	}

	private function writeNodes() {

		// model new properties
		foreach ($this->build->schema->nodes as $node) {
			$filename = $this->migrationPath($this->migrationNameForNode($node));

			if ($filedata = $this->migrationNodeRename($node)) {
				file_put_contents($filename, $filedata);
				echo ($filename) . "<br>";
			}

			if ($filedata = $this->migrationNodeFile($node)) {
				file_put_contents($filename, $filedata);
				echo ($filename) . "<br>";
			}
		}

		// model removed properties
		// ?
	}

	private function writeEdges() {
		$froms = [];
		$made = array_keys((array) $this->build->scaffold->scaffolds->made);
		foreach ($this->build->schema->edges as $edge) {

			$startnode = $this->schema[$edge->startpoint];
			$endnode = $this->schema[$edge->endpoint];

			$nuid = $startnode->suid;
			$s_name = str_singular(str_name($this->schema[$startnode->suid]->name));
			$e_name = str_singular(str_name($this->schema[$endnode->suid]->name));

			$name = strtolower($startnode->suid) . '.' . $s_name . '-' . strtolower($endnode->suid) . '.' . $e_name;

			if (!in_array($name, $made)) {
				$froms[$nuid]['edge'] = $edge;
				$froms[$nuid]['props'][$s_name] = $this->prop_suid($s_name . '_id');
				$froms[$nuid]['props'][$e_name] = $this->prop_suid($e_name . '_id');
				$this->build->scaffold->scaffolds->made->$name = 'edge';
			}
		}

		if (!empty($froms)) {
			foreach ($froms as $key => $from) {
				$filename = $this->migrationPath($this->migrationNameForEdge($from['edge']));
				$filedata = $this->migrationEdgeFile($from['edge'], $from['props']);
				file_put_contents($filename, $filedata);
				//
				$this->scaffold[$key] = $from;
			}
		}
	}

	private function writeConfig() {
		$filename = config_path('model.php');
		$filedata = $this->scaffoldConfigFile();
		file_put_contents($filename, $filedata);
	}

	// models only if not exist
	// the models have traits that can be altered
	private function writeModels() {

		foreach ($this->build->schema->nodes as $node) {

			$filename = $this->modelPath($this->scaffoldNameForModel($node));
			if (!file_exists($filename)) {
				$filedata = $this->scaffoldModelFile($node);
				file_put_contents($filename, $filedata);
				echo ($filename) . "<br>";
			}

			$filename = $this->modelPath($this->scaffoldNameForTrait($node), true);
			$filedata = $this->scaffoldTraitFile($node);
			file_put_contents($filename, $filedata);
			echo ($filename) . "<br>";

		}

	}

}
