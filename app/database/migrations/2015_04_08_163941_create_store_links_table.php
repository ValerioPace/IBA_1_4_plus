<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_links', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id');
			$table->text('android')->nullable();
			$table->text('ios')->nullable();
			$table->text('w_phone')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('store_links');
	}

}
