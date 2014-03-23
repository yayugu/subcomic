<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 1); // ascii, case insensitive
            $table->binary('password', 255);
            $table->timestamps();

            $table->unique('name');
        });
        DB::statement('ALTER TABLE users MODIFY name varchar(100) CHARACTER SET ascii COLLATE ascii_general_ci');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
