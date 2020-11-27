<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignKeysAssignment extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->foreign('origin_country_code')->references('iso2')->on('countries');
      $table->foreign('origin_state_id')->references('id')->on('states');
      $table->foreign('resident_country_code')->references('iso2')->on('countries');
      $table->foreign('resident_state_id')->references('id')->on('states');
      $table->foreign('resident_lga_id')->references('id')->on('lgas');
      $table->foreign('resident_place_id')->references('id')->on('places');
    });

    Schema::table('experiences', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('country_code')->references('iso2')->on('countries');
      $table->foreign('state_id')->references('id')->on('states');
      $table->foreign('lga_id')->references('id')->on('lgas');
    });

    Schema::table('education', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('country_code')->references('iso2')->on('countries');
      $table->foreign('state_id')->references('id')->on('states');
      $table->foreign('lga_id')->references('id')->on('lgas');
    });

    Schema::table('skills', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('references', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::table('states', function (Blueprint $table) {
      $table->foreign('country_code')->references('iso2')->on('countries');
    });

    Schema::table('lgas', function (Blueprint $table) {
      $table->foreign('country_code')->references('iso2')->on('countries');
      $table->foreign('state_id')->references('id')->on('states');
    });

    Schema::table('places', function (Blueprint $table) {
      $table->foreign('country_code')->references('iso2')->on('countries');
      $table->foreign('state_id')->references('id')->on('states');
      $table->foreign('lga_id')->references('id')->on('lgas');
    });

    Schema::table('ads', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('resolved_by')->references('id')->on('users');
      $table->foreign('category_id')->references('id')->on('categories');
      $table->foreign('state_id')->references('id')->on('states');
      $table->foreign('lga_id')->references('id')->on('lgas');
      $table->foreign('place_id')->references('id')->on('places');
    });

    Schema::table('ad_tag', function (Blueprint $table) {
      $table->foreign('tag_id')->references('id')->on('tags');
      $table->foreign('ad_id')->references('id')->on('ads');
    });

    Schema::table('ad_applications', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('ad_id')->references('id')->on('ads');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign('origin_country_code');
      $table->dropForeign('origin_state_id');
      $table->dropForeign('resident_country_code');
      $table->dropForeign('resident_state_id');
      $table->dropForeign('resident_lga_id');
      $table->dropForeign('resident_place_id');
    });

    Schema::table('experiences', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('country_code');
      $table->dropForeign('state_id');
      $table->dropForeign('lga_id');
    });

    Schema::table('education', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('country_code');
      $table->dropForeign('state_id');
      $table->dropForeign('lga_id');
    });

    Schema::table('skills', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });

    Schema::table('references', function (Blueprint $table) {
      $table->dropForeign('user_id');
    });

    Schema::table('states', function (Blueprint $table) {
      $table->dropForeign('country_code');
    });

    Schema::table('lgas', function (Blueprint $table) {
      $table->dropForeign('country_code');
      $table->dropForeign('state_id');
    });

    Schema::table('places', function (Blueprint $table) {
      $table->dropForeign('country_code');
      $table->dropForeign('state_id');
      $table->dropForeign('lga_id');
    });

    Schema::table('ads', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('resolved_by');
      $table->dropForeign('category_id');
      $table->dropForeign('state_id');
      $table->dropForeign('lga_id');
      $table->dropForeign('place_id');
    });

    Schema::table('ad_tag', function (Blueprint $table) {
      $table->dropForeign('tag_id');
      $table->dropForeign('ad_id');
    });

    Schema::table('ad_applications', function (Blueprint $table) {
      $table->dropForeign('user_id');
      $table->dropForeign('ad_id');
    });
  }
}
