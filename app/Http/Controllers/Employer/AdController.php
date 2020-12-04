<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Services\AdSearch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AdPlan;
use App\Models\Employer;
use App\Models\SiteConfig;
use App\Models\Tag;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $ads = AdSearch::apply($request, 20);
    $response['status'] = 'success';
    $response['ads'] = $ads;
    return response()->json($response, Response::HTTP_OK);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required|string|min:5',
      'wage_amount' => 'required_if:wage_rate:fixed|numeric|between:1,100000000',
      'min_wage_amount' => 'required_if:wage_rate,negotiable|numeric|between:1,100000000',
      'max_wage_amount' => 'required_if:wage_rate,negotiable|numeric|between:1,100000000',
      'wage_plan' => 'required|in:hourly,daily,weekly,monthly',
      'wage_rate' => 'required|in:fixed,negotiable',
      'ad_type' => 'required|in:full_time,part_time,once',
      'ad_presence' => 'required|in:remote,dail_commuting',
      'avail_slot' => 'required|integer|digits_between:1,1000',
      'category' => 'required|exists:categories,id',
      'plan' => 'required|in:free,featured,urgent,highlighted',
      'ends' => 'required|date',
      'state' => 'sometimes|nullable|integer|exists:states,id',
      'lga' => 'sometimes|nullable|integer|exists:lgas,id',
      'place' => 'sometimes|nullable|integer|exists:places,id',
      'address' => 'required|string|',
      'summary' => 'required|string|',
      'description' => 'required|string',
    ]);

    $free_product_duration = SiteConfig::firstOrCreate(['key' => "free_product_duration"], ['value' => 30]);
    $ad_duration = now()->addDays($free_product_duration->value);
    $free_product_plan = new AdPlan([
      'name' => 'free',
      'status' => 'active',
      'expires_at' => $ad_duration
    ]);
    $free_product_plan->save();
    $employer = Employer::find(Auth::user()->id);
    $new_ad = new Ad();

    $attribs = [
      'title', 'wage_amount', 'wage_type',
      'avail_slot', 'category', 'plan',
      'ends', 'state', 'lga', 'place',
      'address', 'description'
    ];

    foreach ($attribs as $attrib) {
      if ($attrib === 'ends') {
        $new_ad->end_at = Carbon::parse($request->ends)->format('Y-m-d H:i:s');
      } elseif (in_array($attrib, ['state', 'place', 'lga'])) {
        $new_ad->{$attrib . '_id'} = $request->{$attrib};
      } else {
        $new_ad->{$attrib} = $request->{$attrib};
      }
    }
    $new_ad->status = 'pending';
    $new_ad->plan = 'free';
    $new_ad->plan_id = $free_product_plan->id;
    $new_ad->employer_id = $employer->id;
    $new_ad->country_code = 'US';
    $new_ad->update();

    $tags = [];
    foreach (explode('-', $new_ad->slug) as $tag) {
      $creatable_tag = Tag::firstOrCreate(
        ['name' => $tag]
      );
    }
    $tags[] = $creatable_tag->id;
    $new_ad->tags()->attach($tags);

    $new_ad->refresh();
    if ($request->plan == 'free') {
      $response['status'] = 'success';
      $response['message'] = 'Ad has been uploaded';
      $response['is_upgradable'] = false;
      $response['ad_details'] = ['product_slug' => $new_ad->slug, 'product_plan' => $new_ad->plan];

      return response()->json($response, Response::HTTP_CREATED);
    } else {
      $response['status'] = 'success';
      $response['message'] = 'Ad Has been Uploaded';
      $response['is_upgradable'] = true;
      $response['ad_details'] = ['product_slug' => $new_ad->slug, 'product_plan' => $request->plan];
      return response()->json($response, Response::HTTP_CREATED);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str $ad_slug
   * @return \Illuminate\Http\Response
   */
  public function show($ad_slug)
  {
    if (Ad::whereSlug($ad_slug)->exists()) {
      $viewable_ad = Ad::whereSlug($ad_slug)->firstOrFail();
      $viewable_ad->inorganic_views++;
      $viewable_ad->update();
      $response['status'] = 'success';
      $response['ad'] = $viewable_ad;
      return response()->json($response, Response::HTTP_CREATED);
    } else {
      $response['status'] = 'error';
      $response['message'] = 'No Such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str $ad_slug
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $ad_slug)
  {
    if (Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->exists()) {
      $updateable_ad = Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->firstOrFail();
      $this->validate($request, [
        'title' => 'sometimes|nullable|string|min:5',
        'fixed_wage_amount' => 'required_if:wage_rate:fixed|numeric|between:1,100000000',
        'min_wage_amount' => 'required_if:wage_rate,negotiable|numeric|between:1,100000000',
        'max_wage_amount' => 'required_if:wage_rate,negotiable|numeric|between:1,100000000',
        'wage_plan' => 'sometimes|nullable|in:hourly,daily,weekly,monthly',
        'wage_rate' => 'sometimes|nullable|in:fixed,negotiable',
        'ad_type' => 'sometimes|nullable|in:full_time,part_time,once',
        'ad_presence' => 'sometimes|nullable|in:remote,dail_commuting',
        'avail_slot' => 'sometimes|nullable|integer|digits_between:1,1000',
        'category' => 'sometimes|nullable|exists:categories,id',
        'plan' => 'sometimes|nullable|in:free,featured,urgent,highlighted',
        'ends' => 'sometimes|nullable|date',
        'state' => 'sometimes|nullable|integer|exists:states,id',
        'lga' => 'sometimes|nullable|integer|exists:lgas,id',
        'place' => 'sometimes|nullable|integer|exists:places,id',
        'address' => 'sometimes|nullable|string|',
        'summary' => 'sometimes|nullable|string|',
        'description' => 'sometimes|nullable|string',
      ]);

      $attribs = [
        'title', 'wage_amount', 'wage_type',
        'avail_slot', 'category', 'plan',
        'ends', 'state', 'lga', 'place',
        'address', 'description'
      ];
      foreach ($attribs as $attrib) {
        if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
          if ($attrib === 'ends') {
            $updateable_ad->end_at = Carbon::parse($request->ends)->format('Y-m-d H:i:s');
          } elseif (in_array($attrib, ['state', 'place', 'lga'])) {
            $updateable_ad->{$attrib . '_id'} = $request->{$attrib};
          } else {
            $updateable_ad->{$attrib} = $request->{$attrib};
          }
        }
      }
      $updateable_ad->update();


      $tags = [];
      foreach (explode('-', $updateable_ad->slug) as $tag) {
        $creatable_tag = Tag::firstOrCreate(
          ['name' => $tag]
        );
        $tags[] = $creatable_tag->id;
      }
      $updateable_ad->tags()->sync($tags);

      $updateable_ad->refresh();
      if ($request->plan == 'free') {
        $response['status'] = 'success';
        $response['message'] = 'Ad has been Updated';
        $response['is_upgradable'] = false;
        $response['ad_details'] = ['product_slug' => $updateable_ad->slug, 'product_plan' => $updateable_ad->plan];

        return response()->json($response, Response::HTTP_CREATED);
      } else {
        $response['status'] = 'success';
        $response['message'] = 'Ad Has been Updated';
        $response['is_upgradable'] = true;
        $response['ad_details'] = ['product_slug' => $updateable_ad->slug, 'product_plan' => $request->plan];
        return response()->json($response, Response::HTTP_CREATED);
      }
    } else {
      $response['status'] = 'error';
      $response['message'] = 'You do not have such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Str  $ad_slug
   * @return \Illuminate\Http\Response
   */
  public function destroy($ad_slug)
  {
    if (Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->exists()) {
      try {
        $deletable_ad = Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->firstOrFail();
        $deletable_ad->tags()->detach();
        $deletable_ad->applications()->delete();
        $deletable_ad->delete();

        $response['status'] = 'success';
        $response['message'] = 'Your Ad was deleted';
        return response()->json($response, Response::HTTP_OK);
      } catch (\Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    } else {
      $response['status'] = 'error';
      $response['message'] = 'You do not have such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * Close Ad as Employment is no longer in session.
   *
   * @param  Str  $ad_slug
   * @return \Illuminate\Http\Response
   */
  public function close($ad_slug)
  {
    if (Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->exists()) {
      try {
        $deletable_ad = Ad::whereSlug($ad_slug)->whereEmployerId(Auth()->user()->id)->firstOrFail();
        $deletable_ad->close();
        $response['status'] = 'success';
        $response['message'] = 'Your Ad was deleted';
        return response()->json($response, Response::HTTP_OK);
      } catch (\Exception $e) {
        $response['status'] = 'error';
        $response['message'] = $e->getMessage();
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    } else {
      $response['status'] = 'error';
      $response['message'] = 'You do not have such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }
}