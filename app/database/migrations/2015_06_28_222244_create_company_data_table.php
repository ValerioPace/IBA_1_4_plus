<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name')->nullable();
			$table->text('last_name')->nullable();
			$table->text('company_name')->nullable();
			$table->text('company_description')->nullable();
			$table->text('logo')->nullable();
			$table->text('cover_image')->nullable();
			$table->text('top_slogan')->nullable();
			$table->text('bottom_slogan')->nullable();
			$table->text('phone')->nullable();
			$table->text('mobile')->nullable();
			$table->text('fax')->nullable();
			$table->text('address')->nullable();
			$table->text('email')->nullable();
			$table->text('web_site')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_data');
	}

}
