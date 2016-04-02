<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublishedAppTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('published_app', function(Blueprint $table)
		{
                        $table->increments('id');
                        $table->text('name');
                        $table->integer('android_version_code');
                        $table->text('android_version_number');
                        $table->text('google_cloud_apikey')->nullable();
                        $table->text('ios_cert_filename')->nullable();
                        $table->integer('company_id');
                        $table->integer('developer_id');
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
		Schema::drop('published_app');
	}

}
