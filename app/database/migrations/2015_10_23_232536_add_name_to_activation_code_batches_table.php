<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameToActivationCodeBatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activation_code_batches', function(Blueprint $table)
		{
			$table->text('name')->after('id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activation_code_batches', function(Blueprint $table)
		{
			$table->dropColumn('name');
		});
	}

}
