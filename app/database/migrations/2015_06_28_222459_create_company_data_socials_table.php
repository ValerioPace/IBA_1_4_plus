<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDataSocialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_data_socials', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_data_id');
			$table->text('network')->nullable();
			$table->text('link');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_data_socials');
	}

}
