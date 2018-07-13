<?php

namespace Bespired\Graphview\Http\Controllers;

use App\Http\Controllers\Controller;

class PrepareController extends Controller {

	/**
	 * Display the specified resource.
	 *
	 * @param  char $suid
	 * @return \Illuminate\Http\Response
	 */
	public function show() {

		$pattern = public_path() . '/vendor/bespired/graphview/img/prepare/*';
		$files = glob($pattern, GLOB_BRACE);
		foreach ($files as $key => $file) {
			$files[$key] = str_replace(public_path() . '/', asset(''), $file);
		}
		return view('bespired.graphview::prepare', [
			'files' => $files,
		]);

	}

}
