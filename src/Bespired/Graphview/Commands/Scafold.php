<?php

namespace Bespired\Graphview\Commands;

use Bespired\Graphview\Models\Building;
use Bespired\Graphview\Scafolds\Scafolding;
use Illuminate\Console\Command;

class Scafold extends Command {

	protected $signature = 'graphview:scafold {connection}';

	protected $description = 'Creates migrations for your project from GraphView file.';

	public function __construct() {
		parent::__construct();

	}

	public function handle() {

		$connection = $this->argument('connection');

		$suid = Building::whereConnection($connection)->first();
		if (!$suid) {
			$this->info(sprintf('No database with connection "%s" found in Graphview.', $connection));
			exit;
		}

		$scafold = new Scafolding();
		$scafold->migrates($suid);

	}

}
