<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductIdAndProductNameToEventiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eventi', function(Blueprint $table)
		{
			$table->text('product_id')->nullable()->after('phone');
			$table->text('product_name')->nullable()->after('product_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eventi', function(Blueprint $table)
		{
			$table->dropColumn('product_id');
			$table->dropColumn('product_name');
		});
	}

}
