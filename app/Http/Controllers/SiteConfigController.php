<?php

namespace App\Http\Controllers;

use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SiteConfigController extends Controller
{
  public function get_ad_upgrade_fee()
  {
    try {
      $featured_ad_fee = SiteConfig::firstOrCreate(['key' => 'featured_upgrade_fee'], ['value' => 3]);
      $highlighted_ad_fee = SiteConfig::firstOrCreate(['key' => 'highlighted_upgrade_fee'], ['value' => 4]);
      $urgent_ad_fee = SiteConfig::firstOrCreate(['key' => 'urgent_upgrade_fee'], ['value' => 2]);

      $response['status'] = 'success';
      $response['featured_upgrade_fee'] = $featured_ad_fee->value;
      $response['highlighted_upgrade_fee'] = $highlighted_ad_fee->value;
      $response['urgent_upgrade_fee'] = $urgent_ad_fee->value;
      return response()->json($response, Response::HTTP_OK);
    } catch (\Throwable $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function get_ad_plan_duration()
  {
    try {
      $free_plan_duration = SiteConfig::firstOrCreate(['key' => 'free_plan_duration'], ['value' => 30]);
      $featured_plan_duration = SiteConfig::firstOrCreate(['key' => 'featured_plan_duration'], ['value' => 30]);
      $highlighted_plan_duration = SiteConfig::firstOrCreate(['key' => 'highlighted_plan_duration'], ['value' => 30]);
      $urgent_plan_duration = SiteConfig::firstOrCreate(['key' => 'urgent_plan_duration'], ['vaplan' => 30]);
      $response['status'] = 'success';
      $response['free_plan_duration'] = $free_plan_duration->value;
      $response['featured_plan_duration'] = $featured_plan_duration->value;
      $response['highlighted_plan_duration'] = $highlighted_plan_duration->value;
      $response['urgent_plan_duration'] = $urgent_plan_duration->value;
      return response()->json($response, Response::HTTP_OK);
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
