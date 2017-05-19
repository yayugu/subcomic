<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->timestamps();

            $table->unique('name');
        });

        DB::statement('ALTER TABLE `tags` ADD `name_sha1` BINARY(20) NOT NULL AFTER `name`');
        DB::statement('ALTER TABLE `tags` ADD UNIQUE (`name_sha1`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }

}
