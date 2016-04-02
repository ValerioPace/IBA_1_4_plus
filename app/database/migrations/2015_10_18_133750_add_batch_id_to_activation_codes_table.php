<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchIdToActivationCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activation_codes', function(Blueprint $table)
		{
			$table->integer('batch_id')->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activation_codes', function(Blueprint $table)
		{
			$table->dropColumn('batch_id');
		});
	}

}
