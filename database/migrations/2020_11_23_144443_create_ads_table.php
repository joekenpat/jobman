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
      $table->efficientUuid('employer_id');
      $table->unsignedBigInteger('category_id');
      $table->efficientUuid('resolved_by')->nullable()->default(null);
      $table->unsignedBigInteger('state_id')->nullable()->default(null);
      $table->unsignedBigInteger('lga_id')->nullable()->default(null);
      $table->unsignedBigInteger('place_id')->nullable()->default(null);
      $table->integer('inorganic_view')->default(0);
      $table->string('title');
      $table->string('slug')->unique();
      $table->string('plan');
      $table->unsignedBigInteger('plan_id');
      $table->integer('avail_slot');
      $table->string('wage_rate')->default('fixed'); //fixed,negotiable
      $table->string('wage_plan'); //hourly,daily,weekly,monthly
      $table->string('status')->default('pending'); //pending,declined,approved,closed,expired
      $table->string('ad_presence'); //remote,on-site
      $table->string('ad_type'); //full_time,part_time,once',
      $table->decimal('min_wage_amount', 12, 2);
      $table->decimal('max_wage_amount', 12, 2);
      $table->decimal('fixed_wage_amount', 12, 2);
      $table->text('description');
      $table->text('summary');
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
