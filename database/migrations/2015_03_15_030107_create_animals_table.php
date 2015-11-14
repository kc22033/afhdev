<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('animals', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('species')->default('Dog');
            $table->integer('pri_breed_id')->unsigned();
            $table->integer('sec_breed_id')->unsigned()->nullable();
            $table->boolean('mixed_breed')->default(true);
            $table->date('date_of_birth');
            $table->string('gender');
            $table->boolean('altered')->default(false);
            $table->date('intake_date');
            $table->integer('status_id')->unsigned();
            $table->date('status_date');
            $table->string('foster');
            $table->string('picture');
            $table->text('description');

            $table->string('tag_num');
            $table->string('color');
            $table->string('medical_at');
            $table->string('origin');
            $table->string('origin_id');
            $table->string('rabies_tag_num');
            $table->string('rabies_given_by');
            $table->string('next_vax_date');
            $table->string('s_n_date');

            $table->foreign('pri_breed_id')->references('id')->on('breeds');
            $table->foreign('sec_breed_id')->references('id')->on('breeds');

            $table->timestamps();
            $table->softDeletes();
        });	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('animals');
	}

}
