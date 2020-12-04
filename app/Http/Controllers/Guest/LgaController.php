<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Lga;
use App\Models\State;
use Illuminate\Http\Response;

class LgaController extends Controller
{
  /**
   * Display a list of state
   *
   * @return \Illuminate\Http\Response
   */
  public function index($country_code, $state_id)
  {
    try {
      if (Country::whereIso2($country_code)->whereEnabled(true)->exists()) {
        if (State::whereCountryCode($country_code)->whereId($state_id)->exists()) {
          $lgas = Lga::select(
            'id',
            'name',
            'slug',
          )->where('country_code', $country_code)
            ->get();

          $response['status'] = 'success';
          $response['lgas'] = $lgas;
          return response()->json($response, Response::HTTP_OK);
        } else {
          $response['message'] = 'Invalid Credentials';
          $response['errors'] = ['state_id' => [$state_id . ' is an invalid State ID for ' . $country_code]];
          return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
      } else {
        $response['message'] = 'Invalid Credentials';
        $response['errors'] = ['country_code' => [$country_code . ' is an invalid Country Code or is Disabled']];
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
      }
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * find a state.
   *
   * @return \Illuminate\Http\Response
   */
  public function find_index($country_code, $state_id, $findable)
  {
    try {
      if (Country::whereIso2($country_code)->whereEnabled(true)->exists()) {
        if (State::whereCountryCode($country_code)->whereId($state_id)->exists()) {
          $lgas = Lga::select(
            'id',
            'name',
            'slug',
          )->where('country_code', $country_code)
            ->when($findable, function ($query) use ($findable) {
              return $query->search($findable);
            })
            ->get();

          $response['status'] = 'success';
          $response['lgas'] = $lgas;
          return response()->json($response, Response::HTTP_OK);
        } else {
          $response['message'] = 'Invalid Credentials';
          $response['errors'] = ['state_id' => [$state_id . ' is an invalid State ID for ' . $country_code]];
          return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
      } else {
        $response['message'] = 'Invalid Credentials';
        $response['errors'] = ['country_code' => [$country_code . ' is an invalid Country Code or is Disabled']];
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
      }
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }
}
