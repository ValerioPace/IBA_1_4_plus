<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeIdToCompanyDataSocialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_data_socials', function(Blueprint $table)
		{
			$table->text('type_id')->after('company_data_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_data_socials', function(Blueprint $table)
		{
			$table->dropColumn('type_id');
		});
	}

}
