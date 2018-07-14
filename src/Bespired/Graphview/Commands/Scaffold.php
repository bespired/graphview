<?php

namespace Bespired\Graphview\Commands;

use Bespired\Graphview\Models\Building;
use Bespired\Graphview\Scaffolds\Scaffolding;
use Illuminate\Console\Command;

class Scaffold extends Command {

	protected $signature = 'graphview:scaffold';

	protected $description = 'Creates migrations for your project from GraphView file.';

	public function __construct() {
		parent::__construct();

	}

	private function optionList() {
		$builds = \Bespired\Graphview\Models\Building::select('suid', 'name')
			->get()
			->toArray();

		$names = [];
		foreach ($builds as $key => $value) {
			$name = $value['name'];
			if (in_array($name, $names)) {
				$counts = array_count_values($names);
				$name .= ' (' . ($counts[$name] + 1) . ')';
			}
			$keys[1 + $key] = $value['suid'];
			$values[1 + $key] = $name;
			$names[] = $value['name'];
		}
		return [$keys, $values];
	}

	public function handle() {

		list($keys, $values) = $this->optionList();

		if (count($keys) == 0) {
			$this->info("No builds in database.");
			exit;
		}

		if (count($keys) > 1) {
			$name = $this->choice('What build do you want to scaffold?', $values, 1);
			$key = $keys[array_search($name, $values)];
		} else {
			$key = reset($keys);
		}

		$build = \Bespired\Graphview\Models\Building::whereSuid($key)->first();

		$scaffold = new Scaffolding();
		$scaffold->migrates($build);

	}

}
