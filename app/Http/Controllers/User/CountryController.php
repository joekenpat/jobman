<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Response;

class CountryController extends Controller
{
  /**
   * Display a list of countries paginated.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      $countries = Country::select(
        'id',
        'emoji',
        'iso2',
        'phone_code',
        'name',
        'slug',
      )->whereEnabled(true)->paginate(20);

      $response['status'] = 'success';
      $response['countries'] = $countries;
      return response()->json($response, Response::HTTP_OK);
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * search a countries.
   *
   * @return \Illuminate\Http\Response
   */
  public function find_index($find)
  {
    try {
      $countries = Country::select(
        'id',
        'emoji',
        'iso2',
        'iso3',
        'phone_code',
        'name',
        'slug',
      )->whereEnabled(true)
        ->search($find)->paginate(20);

      $response['status'] = 'success';
      $response['countries'] = $countries;
      return response()->json($response, Response::HTTP_OK);
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
