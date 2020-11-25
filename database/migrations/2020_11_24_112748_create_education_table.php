<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('education', function (Blueprint $table) {
      $table->id();
      $table->efficientUuid('user_id')->index();
      $table->string('certificate_obtained');
      $table->string('institution_name');
      $table->string('country_code')->default('US');
      $table->string('state_code')->nullable()->default(null);
      $table->string('lga_code')->nullable()->default(null);
      $table->timestamp('started_at', 6)->nullable()->default(null);
      $table->timestamp('ended_at', 6)->nullable()->default(null);
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
    Schema::dropIfExists('education');
  }
}