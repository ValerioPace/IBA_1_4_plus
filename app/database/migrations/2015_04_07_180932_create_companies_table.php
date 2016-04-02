<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('code');
			$table->integer('license_id')->nullable();
			$table->integer('user_id');
			$table->integer('data_id')->nullable();
			$table->text('name')->nullable();
			$table->text('contact')->nullable();
			$table->text('email')->nullable();
			$table->text('phone')->nullable();
			$table->text('download_link_tag')->nullable();
			$table->integer('company_status_id');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('companies');
	}

}
