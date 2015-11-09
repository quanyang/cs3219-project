<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_requirements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('job_id')->unsigned();
            $table->integer('keyword_id')->unsigned();
            $table->boolean('is_required');
            $table->boolean('is_available');
            $table->decimal('weightage',5,2);
        });

        Schema::table('job_requirements', function($table) {
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('job_requirements');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
