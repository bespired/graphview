<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScaffoldsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		if (!Schema::connection('graphview')->hasTable('scaffolds')) {
			Schema::connection('graphview')->create('scaffolds', function (Blueprint $table) {

				$table->char('suid', 16)->nullable()->primary();

				$table->char('belongs_to', 16)->nullable()->default(null);
				$table->binary('scaffolds')->nullable()->default(null);

				$table->timestamps();

			});
		}
	}

	/**
	 * Reverse the scaffolds.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('graphview')->dropIfExists('scaffolds');
	}
}
