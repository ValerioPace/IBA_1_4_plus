<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eventi', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->nullable();
			$table->text('title')->nullable();
			$table->text('description')->nullable();
			$table->text('image')->nullable();
			$table->timestamp('activated_at')->nullable();
			$table->timestamp('expire_on')->nullable();
			$table->text('contact')->nullable();
			$table->text('email')->nullable();
			$table->text('phone')->nullable();
			$table->boolean('push_flag')->default(true);
			$table->integer('pushed_devices')->default(0);
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
		Schema::drop('eventi');
	}

}
