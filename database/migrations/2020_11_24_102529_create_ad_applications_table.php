<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdApplicationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ad_applications', function (Blueprint $table) {
      $table->efficientUuid('id')->primary();
      $table->efficientUuid('user_id')->index();
      $table->efficientUuid('ad_id')->index();
      $table->text('cover_letter');
      $table->string('status', 30); //accepted,pending,declined,cancelled,
      $table->timestamp('employer_viewed_at', 6)->nullable()->default(null);
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
    Schema::dropIfExists('ad_applications');
  }
}
