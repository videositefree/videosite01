<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('short');
            $table->string('thumbnail');
            $table->string('type');
            $table->string('size');
            $table->integer('rating');
            $table->integer('duration');
            $table->integer('activ');
            $table->integer('no_films');
            $table->integer('no_thumbnail');
            $table->integer('no_short');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
}
