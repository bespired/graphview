<?php

namespace Bespired\Graphview\Models;

use Bespired\Graphview\Traits\HasShortUuid;
use Illuminate\Database\Eloquent\Model;

class Scaffold extends Model {
	use HasShortUuid;

	public function getKeyName() {
		return 'suid';
	}

	protected $connection = 'graphview';

	protected $table = 'scaffolds';

	protected $casts = [
		'scaffolds' => 'array',
	];

	protected $guarded = [
		'suid',
		'csrfToken',
	];
}
