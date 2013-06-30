<?php

use Illuminate\Database\Migrations\Migration;

class Base extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users', function($table){
            $table->increments('id');
            $table->string('email',50);
            $table->string('password',50);
            $table->enum('role', array('Admin','Basic','Publisher'));
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->boolean('is_publisher');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories', function($table){
            $table->increments('id');
            $table->string('name',50);
            $table->integer('category_id')->nullable()->unsigned();
            $table->string('slug',80);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publishers', function($table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->enum('publisher_type',array('Person','Bussiness'));
            $table->string('seller_name',80);
            $table->string('rif_ci',20);
            $table->integer('state');
            $table->string('city',50);
            $table->string('phone1',20);
            $table->string('phone2',20)->nullable();
            $table->string('media',150)->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publications', function($table){
            $table->increments('id');
            $table->string('title',50);
            $table->string('short_description',50);
            $table->string('long_description',300);
            $table->enum('status',array('Draft','Published','On_Hold','Suspended','Finished','Trashed'));
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('visits_number');
            $table->integer('publisher_id')->unsigned();
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publications_categories', function($table){
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('publication_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('publication_id')->references('id')->on('publications');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bussiness_sectors', function($table){
            $table->increments('id');
            $table->string('name',50);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publishers_sectors', function($table){
            $table->increments('id');
            $table->integer('publisher_id')->unsigned();
            $table->integer('bussiness_sector_id')->unsigned();
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->foreign('bussiness_sector_id')->references('id')->on('bussiness_sectors');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publications_raitings', function($table){
            $table->increments('id');
            $table->integer('publisher_id')->unsigned();
            $table->integer('publication_id')->unsigned();
            $table->integer('vote');
            $table->string('comment',300);
            $table->dateTime('date');
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->foreign('publication_id')->references('id')->on('publications');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publications_reports', function($table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('publication_id')->unsigned();
            $table->string('comment',300);
            $table->dateTime('date');
            $table->enum('status',array('Pending','Correct','Incorrect'));
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('publication_id')->references('id')->on('publications');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('publishers_reports', function($table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('publisher_id')->unsigned();
            $table->string('comment',300);
            $table->dateTime('date');
            $table->enum('status',array('Pending','Correct','Incorrect'));
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contacts', function($table){
            $table->increments('id');
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('email',50);
            $table->string('phone',20);
            $table->integer('publisher_id')->unsigned();
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('advertising', function($table){
            $table->increments('id');
            $table->string('name',50);
            $table->enum('status',array('Draft','Published','Trashed'));
            $table->integer('category_id')->unsigned();
            $table->string('image_url',50);
            $table->string('external_url',50)->nullable();;
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('email',50);
            $table->string('phone1',20);
            $table->string('phone2',20)->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('log', function($table){
            $table->increments('id');
            $table->enum('operation',array('Edit_user','Delete_user','Add_admin','Edit_advertising','Delete_advertising','Add_advertising'));
            $table->dateTime('date');
            $table->string('value_field',50);
            $table->string('previous_field',50);
            $table->string('final_value',50);
            $table->integer('from_user_id')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->integer('publication_id')->nullable();
            $table->integer('advertising_id')->nullable();
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
        Schema::drop('users');
        Schema::drop('categories');
        Schema::drop('publishers');
        Schema::drop('publications');
        Schema::drop('publications_categories');
        Schema::drop('bussiness_sectors');
        Schema::drop('publishers_sectors');
        Schema::drop('publications_raitings');
        Schema::drop('publications_reports');
        Schema::drop('publishers_reports');
        Schema::drop('contacts');
        Schema::drop('advertising');
        Schema::drop('log');
	}

}