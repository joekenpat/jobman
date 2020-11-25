<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
      $table->efficientUuid('id')->primary();
      $table->string('name');
      $table->string('title')->nullable()->default(null); //mr,ms
      $table->date('dob')->nullable()->default(null);
      $table->string('phone')->unique()->nullable()->default(null);
      $table->string('username')->unique()->nullable()->default(null);
      $table->string('email')->unique();
      $table->string('state_code')->nullable()->default(null);
      $table->string('lga_code')->nullable()->default(null);
      $table->string('place_code')->nullable()->default(null);
      $table->text('address')->nullable();
      $table->text('bio')->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->ipAddress('last_ip');
      $table->string('password');
      $table->rememberToken();
      $table->timestamp('last_login', 6)->nullable()->default(null);
      $table->timestamp('blocked_at', 6)->nullable()->default(null);
      $table->timestamp('created_at', 6)->useCurrent();
      $table->timestamp('updated_at', 6)->useCurrent()->nullable();
      $table->timestamp('deleted_at', 6)->nullable()->default(null);
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
}
