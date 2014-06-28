<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagMapsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('comic_id')->unsigned();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE `tag_maps` ADD `tag_name_sha1` BINARY(20) NOT NULL AFTER `comic_id`');
        DB::statement('ALTER TABLE `tag_maps` ADD UNIQUE (`comic_id`, `tag_name_sha1`)');
        DB::statement('ALTER TABLE `tag_maps` ADD INDEX (`tag_name_sha1`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tag_maps');
    }

}
