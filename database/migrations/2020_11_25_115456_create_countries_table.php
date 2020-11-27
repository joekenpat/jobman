<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('countries', function (Blueprint $table) {
      $table->id();
      $table->char('iso2', 2)->unique();
      $table->char('iso3', 3)->unique();
      $table->char('lang_code', 3)->nullable()->default(null);
      $table->boolean('enabled')->default(false);
      $table->string('name', 100);
      $table->string('slug', 110)->unique();
      $table->string('phone_code', 30)->nullable()->default(null);
      $table->binary('emoji', 50)->nullable()->default(null);
      $table->binary('emojiU', 50)->nullable()->default(null);
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
    Schema::dropIfExists('countries');
  }
}
