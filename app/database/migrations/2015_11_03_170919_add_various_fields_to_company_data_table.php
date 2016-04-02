<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariousFieldsToCompanyDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_data', function(Blueprint $table)
		{
			$table->text('app_name')->nullable()->after('last_name');
			$table->text('position')->nullable()->after('company_description');
			$table->text('icon_image')->nullable()->after('cover_image');
			$table->text('email_2')->nullable()->after('email');
			$table->text('web_site_2')->nullable()->after('web_site');
			$table->text('note')->nullable()->after('web_site_2');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_data', function(Blueprint $table)
		{
			$table->dropColumn('app_name');
			$table->dropColumn('position');
			$table->dropColumn('icon_image');
			$table->dropColumn('email_2');
			$table->dropColumn('web_site_2');
			$table->dropColumn('note');
		});
	}

}
