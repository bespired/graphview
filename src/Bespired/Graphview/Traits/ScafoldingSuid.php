<?php

namespace Bespired\Graphview\Traits;

use Bespired\Graphview\Models\Scaffold as ScaffoldModel;

trait ScaffoldingSuid {

	private function idBySuid($suid) {

		// create table if not exists
		if (!$this->build->scaffold) {
			$this->build->scaffold = dictionairify(['scaffolds' => [
				'ids' => [], 'names' => [], 'incs' => [], 'made' => [],
			]]);
		}

		// create index if not exists
		if (!isset($this->build->scaffold->scaffolds->ids->$suid)) {
			$inc = count((array) $this->build->scaffold->scaffolds->ids);

			if (!isset($this->schema[$suid])) {
				dd('idBySuid', $this);
			}

			$this->build->scaffold->scaffolds->ids->$suid = 1 + $inc;
			$this->build->scaffold->scaffolds->incs->$suid = 1;
			$this->build->scaffold->scaffolds->names->$suid = $this->schema[$suid]->name;
		}

		// return index
		return $this->build->scaffold->scaffolds->ids->$suid;

	}
	private function incBySuid($suid) {
		// works only if used after idBySuid
		return $this->build->scaffold->scaffolds->incs->$suid++;
	}

	//

	private function saveScaffolding() {

		$this->scaffold = ScaffoldModel::where('belongs_to', $this->build->suid)
			->first();

		if (!$this->scaffold) {
			$this->scaffold = ScaffoldModel::create([
				'belongs_to' => $this->build->suid,
			]);
		}
		$this->scaffold->scaffolds = (array) $this->build->scaffold->scaffolds;
		$this->scaffold->save();

	}

}
