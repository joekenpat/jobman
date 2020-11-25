<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ads', function (Blueprint $table) {
      $table->efficientUuid('id')->primary();
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('category_id');
      $table->unsignedBigInteger('resolved_by');
      $table->string('state_code')->nullable()->default(null);
      $table->string('lga_code')->nullable()->default(null);
      $table->string('place_code')->nullable()->default(null);
      $table->integer('inorganic_view')->default(0);
      $table->string('title');
      $table->string('plan');
      $table->boolean('wage_negotiable')->default(false);
      $table->string('wage_type'); //hourly,daily,weekly,monthly
      $table->string('status')->default('pending'); //pending,declined,approved,closed,expired
      $table->string('service_type'); //remote,on-site
      // $table->decimal('min_wage_amount', 12, 2);
      // $table->decimal('max_wage_amount', 12, 2);
      $table->decimal('wage_amount', 12, 2);
      $table->decimal('latitude', 10, 8)->nullable()->default(null);
      $table->decimal('longitude', 11, 8)->nullable()->default(null);
      $table->text('description');
      $table->text('address');
      $table->text('decline_reason');
      $table->timestamp('end_at', 6);
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
    Schema::dropIfExists('ads');
  }
}
