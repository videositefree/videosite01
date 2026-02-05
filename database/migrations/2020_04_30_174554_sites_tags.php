<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SitesTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites_tags', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->integer('tag_id');
            $table->integer('tag_db');
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
        //
    }
}
