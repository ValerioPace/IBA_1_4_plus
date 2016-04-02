<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activation_codes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reseller_id')->nullable();
			$table->integer('buyer_id')->nullable();
			$table->integer('license_id')->nullable();
			$table->integer('company_id')->nullable();
			$table->text('code');
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
		Schema::drop('activation_codes');
	}

}
