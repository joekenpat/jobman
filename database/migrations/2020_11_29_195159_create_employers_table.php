<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('employers', function (Blueprint $table) {
      $table->efficientUuid('id')->primary();
      $table->string('avatar')->nullable()->default(null);
      $table->string('name');
      $table->string('title')->nullable()->default(null); //mr,ms
      $table->string('role');
      $table->string('phone')->unique()->nullable()->default(null);
      $table->string('username')->unique()->nullable()->default(null);
      $table->string('email')->unique();
      $table->string('status');
      // $table->char('origin_country_code', 2)->nullable()->default(null);
      // $table->unsignedBigInteger('origin_state_id')->nullable()->default(null);
      $table->char('resident_country_code', 2)->nullable()->default(null);
      $table->unsignedBigInteger('resident_state_id')->nullable()->default(null);
      $table->unsignedBigInteger('resident_lga_id')->nullable()->default(null);
      $table->unsignedBigInteger('resident_place_id')->nullable()->default(null);
      $table->ipAddress('last_ip');
      $table->string('password');
      $table->rememberToken();
      $table->timestamp('email_verified_at')->nullable();
      $table->timestamp('last_login', 6)->nullable()->default(null);
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
    Schema::dropIfExists('employers');
  }
}
