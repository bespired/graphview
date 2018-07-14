<?php
namespace Bespired\Graphview;

use Bespired\Graphview\Commands\Destroy;
use Bespired\Graphview\Commands\Install;
use Bespired\Graphview\Commands\Scaffold;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	protected $namespace = 'Bespired\Graphview\Http\Controllers';
	protected $path;

	public function boot() {

		$this->publishes([
			__DIR__ . '/../../public' => public_path('vendor/bespired/graphview'),
		], 'graphview-public');

		$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'bespired.graphview');

	}

	public function register() {
		$this->mergeConfigFrom(__DIR__ . '/../../config/demo.php', 'bespired.graphview.demo');
		$this->mergeConfigFrom(__DIR__ . '/../../config/names.php', 'graph.names');

		$this->mergeConnections();
		$this->map();

		$this->commands([
			Install::class,
			Scaffold::class,
			Destroy::class,
		]);

		require_once __DIR__ . '/Scaffolds/helpers.php';

	}

	private function mergeConnections() {

		$sqlite = [
			'driver' => 'sqlite',
			'database' => database_path('graphview.sqlite'),
		];
		$config = $this->app['config']->get('graphview.connections', []);
		$this
			->app['config']
			->set('database.connections.graphview', array_merge($sqlite, $config));

	}

	public function map() {

		$this->path = __DIR__ . '/../../routes';

		Route::namespace ($this->namespace)
			->middleware('web')
			->group(function () {
				require $this->path . '/web.php';
			});

	}

}
