<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusIdToActivationCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activation_codes', function(Blueprint $table)
		{
			$table->integer('status_id')->after('code')->default(1);
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
			$table->dropColumn('status_id');
		});
	}

}
