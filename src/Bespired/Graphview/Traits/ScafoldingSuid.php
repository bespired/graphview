<?php

namespace Bespired\Graphview\Traits;

use Bespired\Graphview\Models\Scafold as ScafoldModel;

trait ScafoldingSuid {

	private function idBySuid($suid) {

		// create table if not exists
		if (!$this->build->scafold) {
			$this->build->scafold = dictionairify(['scafolds' => [
				'ids' => [], 'names' => [], 'incs' => [], 'made' => [],
			]]);
		}

		// create index if not exists
		if (!isset($this->build->scafold->scafolds->ids->$suid)) {
			$inc = count((array) $this->build->scafold->scafolds->ids);

			if (!isset($this->schema[$suid])) {
				dd('idBySuid', $this);
			}

			$this->build->scafold->scafolds->ids->$suid = 1 + $inc;
			$this->build->scafold->scafolds->incs->$suid = 1;
			$this->build->scafold->scafolds->names->$suid = $this->schema[$suid]->name;
		}

		// return index
		return $this->build->scafold->scafolds->ids->$suid;

	}
	private function incBySuid($suid) {
		// works only if used after idBySuid
		return $this->build->scafold->scafolds->incs->$suid++;
	}

	//

	private function saveScafolding() {

		$this->scafold = ScafoldModel::where('belongs_to', $this->build->suid)
			->first();

		if (!$this->scafold) {
			$this->scafold = ScafoldModel::create([
				'belongs_to' => $this->build->suid,
			]);
		}
		$this->scafold->scafolds = (array) $this->build->scafold->scafolds;
		$this->scafold->save();

	}

}
