<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordRelevanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_relevance', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('relevance',3,2);
            $table->integer('from_keyword_id')->unsigned();
            $table->integer('to_keyword_id')->unsigned();
        });
        Schema::table('keyword_relevance', function($table) {
            $table->foreign('from_keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            $table->foreign('to_keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            
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
        Schema::drop('keyword_relevance');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
