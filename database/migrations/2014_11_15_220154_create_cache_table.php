<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacheTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement('
            CREATE TABLE cache(
                `key` VARBINARY(767) PRIMARY KEY,
                `value` VARBINARY(60000)
            );
        ');
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
