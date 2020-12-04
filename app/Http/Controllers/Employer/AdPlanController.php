<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdPlan;
use App\Models\Employer;
use App\Models\SiteConfig;
use App\Models\Transaction;

class AdPlanController extends Controller
{
  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function request_upgrade_ad($ad_slug, $upgrade_plan)
  {
    if (Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->exists()) {
      $upgradable_ad = Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->firstOrFail();
      $ad_upgrade_plan = strtolower($upgrade_plan);
      $upgrade_config = SiteConfig::where('key', "{$ad_upgrade_plan}_upgrade_fee")->first();
      $standard_upgrade_amount = $upgrade_config->value;
      //create transaction
      $upgrade_ad_duration = SiteConfig::where('key', $ad_upgrade_plan . "_product_duration")->first();
      $downgrade_plan = AdPlan::where('ad_id', $upgradable_ad->id)->latest()->first();
      $transaction =  new Transaction([
        'status' => 'created',
        'total_amount' => $standard_upgrade_amount,
        'user_id' => Auth()->user()->id,
        'type' => 'ad_plan_upgrade'
      ]);
      $upgrade_ad_plan = new AdPlan([
        'ad_id' => $upgradable_ad->id,
        'downgrade_id' => $downgrade_plan->id,
        'name' => $ad_upgrade_plan,
        'status' => 'active',
        'expires_at' => now()->addDays($upgrade_ad_duration->value),
      ]);
      $transaction->save();
    }
  }
}
