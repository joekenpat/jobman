<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdPlansTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ad_plans', function (Blueprint $table) {
      $table->id();
      $table->efficientUuid('ad_id');
      $table->unsignedBigInteger('downgrade_id')->nullable()->default(null);
      $table->string('name', 50);
      $table->string('status', 40);
      $table->timestamp('expires_at', 6)->nullable()->default(null);
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
    Schema::dropIfExists('ad_plans');
  }
}
