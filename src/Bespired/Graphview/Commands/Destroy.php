<?php

namespace Bespired\Graphview\Commands;

use Illuminate\Console\Command;

class Destroy extends Command {

	protected $signature = 'graphview:destroy';

	protected $description = 'Remove migrations for your project from GraphView file.';

	public function __construct() {
		parent::__construct();

	}

	public function handle() {

		$this->removeOldFiles();

		$this->info('Removed.');

	}

	private function removeOldFiles() {

		$this->removeGraphFiles(database_path('migrations'), 'generated migration file.');
		$this->removeGraphFiles(app_path('Traits'), 'generated trait file.');
		$this->removeGraphFiles(app_path('Models'), 'generated model file.');

	}

	private function removeGraphFiles($where, $what) {
		$pattern = $where . '/*.php';
		foreach (glob($pattern) as $filename) {
			$file = file_get_contents($filename);
			if (strpos($file, $what) !== false) {
				unlink($filename);
				$removed = true;
			}
		}
		if (isset($removed)) {
			$this->info('Removed ' . $what);
		}
	}

}
