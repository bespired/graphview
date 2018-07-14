<?php

namespace Bespired\Graphview\Commands;

use Artisan;
use Illuminate\Console\Command;

class Install extends Command {

	protected $signature = 'graphview:install:fresh';

	protected $description = 'Creates setup for GraphView';

	protected $sqlitefile;
	protected $sqlitepath;

	public function __construct() {
		parent::__construct();

		$route = explode(DIRECTORY_SEPARATOR, config('database.connections.graphview.database'));

		$this->sqlitefile = array_pop($route);
		$this->sqlitepath = array_pop($route);

	}

	public function handle() {

		// $this->checkEnv();

		$this->removeOldFiles();

		$this->removeOldDb();

		$this->createNewDb();

		$this->doMigrations();

		$this->publishAssets();

		$this->demoGraph();

	}

	// PRIVATE

	private function warning($text) {
		$this->line(sprintf('<fg=red>%s</>', $text));
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
	private function removeOldDb() {

		if (file_exists(config('database.connections.graphview.database'))) {
			@unlink(config('database.connections.graphview.database'));
		}

	}

	private function createNewDb() {

		touch(config('database.connections.graphview.database'));
		$this->info('Created database ' . $this->sqlitefile);

	}

	private function doMigrations() {

		$root = realpath(app_path() . '/..');
		$base = realpath(__DIR__ . '/../../../database/migrations');
		$path = substr($base, strlen($root) + 1);

		Artisan::call('migrate:fresh', [
			'--force' => true,
			'--database' => 'graphview',
			'--path' => $path,
		]);

		$this->info('Migrated fresh database.');
	}

	private function publishAssets() {

		Artisan::call('vendor:publish', [
			'--tag' => 'graphview-public',
			'--force' => true,
		]);

		$this->info('Assets published.');
	}

	private function demoGraph() {

		// dd(env('DB_CONNECTION'), config('database.default'));
		// for some reason config('database.default') has the wrong DB..

		// add config to set other connections?
		// or add GUI to change connection name?

		$model = \Bespired\Graphview\Models\Building::create();
		$model->name = 'Demo Schema';
		$model->suid = 'demo';
		$model->connection = env('DB_CONNECTION');
		$model->schema = config('bespired.graphview.demo');
		$model->save();

		$Scaffold = \Bespired\Graphview\Models\Scaffold::where('belongs_to', 'demo')->delete();

		$this->info(sprintf('Demo Graph created.'));
	}

}
