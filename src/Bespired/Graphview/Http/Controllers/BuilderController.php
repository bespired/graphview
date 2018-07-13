<?php

namespace Bespired\Graphview\Http\Controllers;

use App\Http\Controllers\Controller;
use Bespired\Graphview\Models\Building;
use Bespired\Graphview\Scafolds\Scafolding;
use Bespired\Graphview\Traits\Yamlaar;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class BuilderController extends Controller {

	use Yamlaar;

	//

	public function index() {

		$index = Building::select('name', 'suid')->get();

		return view('bespired.graphview::index', [
			'index' => $index,
		]);

	}

	//

	public function show(Building $suid) {

		$schema = json_encode($suid->toArray());

		return view('bespired.graphview::builder', [
			'schema' => $schema,
		]);

	}

	//

	public function create() {

		$build = Building::create([
			"name" => "No name",
			"schema" => ["nodes", "edges"]]);
		return $build->suid;

	}

	//

	public function update(Request $request, Building $suid) {

		$suid->update($request->all());

	}

	public function delete(Building $suid) {

		$suid->delete();

	}

	//

	public function export(Building $suid) {

		$build = $suid->toArray();
		return Yamlaar::dump($build);

	}

	public function import(Request $request, Building $suid) {

		$file = $request->file('file');
		$yaml = file_get_contents($file);
		$data = objectify(Yaml::parse($yaml));

		$suid->name = $data->name;
		$suid->connection = $data->connection;
		$suid->schema = $data->schema;
		$suid->save();

		return $suid;

	}

	//

	public function scafold(Building $suid) {

		$scafold = new Scafolding();
		$scafold->migrates($suid->with(['scafold'])->first());

	}

}
