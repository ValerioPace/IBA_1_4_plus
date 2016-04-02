<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeveloperIdToCompanyDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_data', function(Blueprint $table)
		{
			$table->integer('developer_id')->after('id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_data', function(Blueprint $table)
		{
			$table->dropColumn('developer_id');
		});
	}

}
