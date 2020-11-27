<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('places', function (Blueprint $table) {
      $table->id();
      $table->string('country_code');
      $table->unsignedBigInteger('state_id')->nullable();
      $table->unsignedBigInteger('lga_id')->nullable();
      $table->string('name');
      $table->decimal('latitude', 10, 8)->nullable()->default(null);
      $table->decimal('longitude', 11, 8)->nullable()->default(null);
      $table->string('timezone', 40)->nullable()->default(null);
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
    Schema::dropIfExists('places');
  }
}
