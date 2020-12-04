<?php

namespace App\Http\Controllers\Admin;

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
        'enabled',
      )->paginate(20);

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
        'enabled',
      )
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


  /**
   * Enable the specified country.
   *
   * @param  Str  $country_iso2
   * @return \Illuminate\Http\Response
   */
  public function enable($country_iso2)
  {
    if (Country::whereIso2($country_iso2)->exists()) {
      $country = Country::whereIso2($country_iso2)->firstOrFail();
      $country->enabled = true;
      $country->users()->where('status', 'country_disabled_active')->update(['status' => 'active']);
      $country->users()->where('status', 'country_disabled_blocked')->update(['status' => 'blocked']);
      $country->ads()->where('status', 'country_disabled_approved')->update(['status' => 'approved']);
      $country->ads()->where('status', 'country_disabled_pending')->update(['status' => 'pending']);
      $country->ads()->where('status', 'country_disabled_closed')->update(['status' => 'closed']);
      $country->update();
      $response['status'] = 'success';
      $response['message'] = 'Country Enabled';
      return response()->json($response, Response::HTTP_OK);
    } else {
      $response['status'] = 'error';
      $response['message'] = 'Country Not found';
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Disabled the specified country.
   *
   * @param  Str  $country_iso2
   * @return \Illuminate\Http\Response
   */
  public function disable($country_iso2)
  {
    if (Country::whereIso2($country_iso2)->exists()) {
      $country = Country::whereIso2($country_iso2)->firstOrFail();
      $country->enabled = false;
      $country->users()->where('status', 'active')->update(['status' => 'country_disabled_active']);
      $country->users()->where('status', 'blocked')->update(['status' => 'country_disabled_blocked']);
      $country->ads()->where('status', 'approved')->update(['status' => 'country_disabled_approved']);
      $country->ads()->where('status', 'pending')->update(['status' => 'country_disabled_pending']);
      $country->ads()->where('status', 'closed')->update(['status' => 'country_disabled_closed']);
      $country->update();
      $response['status'] = 'success';
      $response['message'] = 'Country Disabled';
      return response()->json($response, Response::HTTP_OK);
    } else {
      $response['status'] = 'error';
      $response['message'] = 'Country Not found';
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
