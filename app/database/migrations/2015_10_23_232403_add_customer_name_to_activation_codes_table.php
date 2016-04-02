<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerNameToActivationCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activation_codes', function(Blueprint $table)
		{
			$table->text('customer_name')->after('buyer_id')->nullable();
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
			$table->dropColumn('customer_name');
		});
	}

}
