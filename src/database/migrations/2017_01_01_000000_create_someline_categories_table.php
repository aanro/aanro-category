<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSomelineCategoriesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('someline_categories', function(Blueprint $table) {
            $table->increments('someline_category_id');
            $table->unsignedInteger('user_id')->index();

            // Adding more table related fields here...
            $table->string('type')->nullable();
            $table->string('identifier')->nullable();
            $table->string('category_name')->nullable();;
            $table->string('category_ename')->nullable();;
            $table->unsignedInteger('parent_category_id')->index()->nullable();
            $table->unsignedInteger('someline_image_id')->index()->nullable();
            $table->mediumInteger('sequence')->nullable();
            $table->json('data')->nullable();

            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->ipAddress('created_ip')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->ipAddress('updated_ip')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('someline_categories');
	}

}
