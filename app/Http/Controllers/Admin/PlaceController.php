<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\lga;
use App\Models\Place;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($country_code, $state_id, $lga_id)
  {
    try {
      if (Country::whereIso2($country_code)->whereEnabled(true)->exists()) {
        if (State::whereCountryCode($country_code)->whereId($state_id)->exists()) {
          if (lga::whereCountryCode($country_code)->whereStateId($state_id)->whereId($lga_id)->exists()) {
            $lgas = Place::select(
              'id',
              'name',
              'slug',
              'latitude',
              'longitude',
              'timezone'
            )->where('country_code', $country_code)
              ->whereStateId($state_id)
              ->get();

            $response['status'] = 'success';
            $response['lgas'] = $lgas;
            return response()->json($response, Response::HTTP_OK);
          } else {
            $response['message'] = 'Invalid Credentials';
            $response['errors'] = ['lga_id' => [$lga_id . ' is an invalid lga ID for State: ' . $state_id]];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
          }
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
  public function find_index(Request $request, $country_code, $state_id, $lga_id)
  {

    try {
      if (Country::whereIso2($country_code)->whereEnabled(true)->exists()) {
        if (State::whereCountryCode($country_code)->whereId($state_id)->exists()) {
          if (lga::whereCountryCode($country_code)->whereStateId($state_id)->whereId($lga_id)->exists()) {
            if ($request->has('find')) {
              $find_validate = Validator::make($request->only('find'), [
                'find' => 'string|max:150',
              ]);
              if ($find_validate->fails()) {
                $findable =  false;
              } else {
                $findable = $request->find;
              }
            } else {
              $findable = false;
            }
            $lgas = Place::select(
              'id',
              'name',
              'slug',
              'latitude',
              'longitude',
              'timezone'
            )->where('country_code', $country_code)
              ->whereStateId($state_id)
              ->whereLgaId($lga_id)
              ->when($findable, function ($query) use ($findable) {
                return $query->search($findable);
              })
              ->get();

            $response['status'] = 'success';
            $response['lgas'] = $lgas;
            return response()->json($response, Response::HTTP_OK);
          } else {
            $response['message'] = 'Invalid Credentials';
            $response['errors'] = ['lga_id' => [$lga_id . ' is an invalid lga ID for State: ' . $state_id]];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
          }
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
