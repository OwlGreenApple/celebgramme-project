<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// id, type(follow,mention,likes,tagged), description, profil_pic_object (user activity tsb), profil_pic_subject(yg like, comment, follow dsb), created_at,updated_at, posted (timestamp)
		Schema::create('activities', function (Blueprint $table) {
			$table->increments('id');
			$table->string('media_id',255)->nullable();
			$table->string('type')->nullable();
			$table->text('description')->nullable();
			$table->string('profil_pic_subject')->nullable();
			$table->string('profil_pic_object')->nullable();
			$table->boolean('is_welcome')->nullable();
			$table->timestamp('posted')->nullable();
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
		Schema::drop('activities');
	}
}
