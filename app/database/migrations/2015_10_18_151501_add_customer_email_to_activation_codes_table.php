<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerEmailToActivationCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activation_codes', function(Blueprint $table)
		{
			$table->text('customer_email')->after('buyer_id')->nullable();
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
			$table->dropColumn('customer_email');
		});
	}

}
