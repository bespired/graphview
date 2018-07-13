<?php

namespace Bespired\Graphview\Models;

use Bespired\Graphview\Traits\HasShortUuid;
use Illuminate\Database\Eloquent\Model;

class Scafold extends Model {
	use HasShortUuid;

	public function getKeyName() {
		return 'suid';
	}

	protected $connection = 'graphview';

	protected $table = 'scafolds';

	protected $casts = [
		'scafolds' => 'array',
	];

	protected $guarded = [
		'suid',
		'csrfToken',
	];
}
