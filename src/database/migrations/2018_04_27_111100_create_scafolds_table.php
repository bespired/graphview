<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScafoldsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		if (!Schema::connection('graphview')->hasTable('scafolds')) {
			Schema::connection('graphview')->create('scafolds', function (Blueprint $table) {

				$table->char('suid', 16)->nullable()->primary();

				$table->char('belongs_to', 16)->nullable()->default(null);
				$table->binary('scafolds')->nullable()->default(null);

				$table->timestamps();

			});
		}
	}

	/**
	 * Reverse the scafolds.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('graphview')->dropIfExists('scafolds');
	}
}
