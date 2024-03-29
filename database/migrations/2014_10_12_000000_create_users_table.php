<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('thumbnail')->default('assets/LaracastImage.png');
			$table->integer('is_verified')->default(0);
			$table->string('password');
			$table->string('verification_code')->nullable();
			$table->rememberToken();
			$table->timestamps();
			$table->string('google_id')->nullable();
			$table->string('google_token')->nullable();
			$table->string('google_refresh_token')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
};
