<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComicsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('path');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE `comics` ADD `filename_sha1` BINARY(20) NOT NULL AFTER `path`');
        DB::statement('ALTER TABLE `comics` ADD UNIQUE (`filename_sha1`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comics');
    }

}
