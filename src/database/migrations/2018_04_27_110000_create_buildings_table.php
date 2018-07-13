<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		if (!Schema::connection('graphview')->hasTable('buildings')) {
			Schema::connection('graphview')->create('buildings', function (Blueprint $table) {

				$table->char('suid', 16)->nullable()->primary();

				$table->char('name', 191)->nullable()->default(null);
				$table->char('connection', 32)->nullable();
				$table->binary('schema')->nullable()->default(null);

				$table->timestamps();

			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('graphview')->dropIfExists('buildings');
	}
}
