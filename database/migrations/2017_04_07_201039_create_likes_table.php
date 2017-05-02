<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('quote_id')->unsigned();
            $table->timestamps();

            // Create unique contraint with user and quote
            // So that one User can like the Quote only once.
            $table->unique(['user_id', 'quote_id']);

            // remove likes when the quote is removed
            $table->foreign('quote_id')
                  ->references('id')->on('quotes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
