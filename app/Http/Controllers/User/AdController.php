<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Services\AdSearch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $ads = AdSearch::apply($request,20);
    $response['status'] = 'success';
    $response['ads'] = $ads;
    return response()->json($response, Response::HTTP_OK);
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
}
