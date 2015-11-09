<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('applications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('contact',150);
            $table->string('resume_path',255);
            $table->integer('job_id')->unsigned();
            $table->string('email');
            $table->string('name');
            $table->boolean('is_parsed')->default(0);
            $table->boolean('is_selected')->default(0);
        });

        Schema::table('applications', function($table) {
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('applications');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
