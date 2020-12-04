<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdApplicationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $ad_applications = AdApplication::whereUuid([Auth()->user()->id, 'user_id'])->simplePaginate(20);
    $response['status'] = 'success';
    $response['ad_applications'] = $ad_applications;
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, $ad_slug)
  {
    if (Ad::whereSlug($ad_slug)->exists()) {
      $applicable_ad = Ad::whereSlug($ad_slug)->firstOrFail();
      $this->validate($request, [
        'cover_letter' => 'required|string|min:5',
      ]);
      $new_ad_application = new AdApplication();
      $new_ad_application->cover_letter = $request->cover_letter;
      $new_ad_application->user_id = Auth()->user()->id;
      $new_ad_application->ad_id = $applicable_ad->id;
      $new_ad_application->status = 'pending';
      $new_ad_application->save();

      $response['status'] = 'success';
      $response['message'] = 'Application Sent Successfull';
      return response()->json($response, Response::HTTP_CREATED);
    } else {
      $response['status'] = 'error';
      $response['message'] = 'No Such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * show the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str $ad_application_id
   * @return \Illuminate\Http\Response
   */
  public function show($ad_application_id)
  {
    if (AdApplication::whereUuid([Auth()->user()->id, 'user_id'])->whereUuid($ad_application_id)->exists()) {
      $ad_application = AdApplication::whereUuid([Auth()->user()->id, 'user_id'])->whereUuid($ad_application_id)->firstOrFail();
      $ad = Ad::whereUuid($ad_application->ad_id)->firstOrFail();
      $response['status'] = 'success';
      $response['ad_application'] = $ad_application;
      $response['ad'] = $ad;
      return response()->json($response, Response::HTTP_OK);
    } else {
      $response['status'] = 'error';
      $response['message'] = 'No Such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * Cancel the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str $ad_application_id
   * @return \Illuminate\Http\Response
   */
  public function cancel($ad_application_id)
  {
    if (AdApplication::whereUuid([Auth()->user()->id, 'user_id'])->whereUuid($ad_application_id)->exists()) {
      $ad_application = AdApplication::whereUuid([Auth()->user()->id, 'user_id'])->whereUuid($ad_application_id)->firstOrFail();
      $ad_application->status = 'cancel';
      $ad_application->save();

      $response['status'] = 'success';
      $response['message'] = 'Application Cancelled Successfull';
      return response()->json($response, Response::HTTP_OK);
    } else {
      $response['status'] = 'error';
      $response['message'] = 'No Such Ad!';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    }
  }
}
