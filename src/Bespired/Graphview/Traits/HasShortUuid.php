<?php

namespace Bespired\Graphview\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait HasShortUuid {

	protected static $local;

	protected static function bootHasShortUuid() {
		static::creating(function (Model $model) {
			if ($model->{$model->getKeyName()}) {
				return;
			}
			$prefix = isset($model->prfx) ? strtolower($model->prfx) : '';
			$model->{$model->getKeyName()} = $prefix . static::encodeUuid(Uuid::uuid1());
			//$model->{$model->getKeyName()} = $prefix . static::uuid();
		});
	}

	// 58t3-ktbp-6fy1-9boq

	static function encodeUuid($uid) {
		$suid = str_replace('-', '', $uid->toString());
		$luid = substr($suid, 0, 10);
		$ruid = substr($suid, 8, 6) . substr($suid, 18, 4);

		return join('-', str_split(base_convert($luid, 16, 36) . base_convert($ruid, 16, 36), 8));
	}

	public function getRouteKeyName() {
		return 'suid';
	}

	public function getIncrementing() {
		return false;
	}

}
