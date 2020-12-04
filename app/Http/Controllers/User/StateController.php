<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Services\StateSearch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $states = StateSearch::apply($request, 20);
    $response['status'] = 'success';
    $response['states'] = $states;
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * find a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function find_index(Request $request, $country_code)
  {
    try {
      if (Country::whereIso2($country_code)->whereEnabled(true)->exists()) {
        $country = Country::whereIso2($country_code)->whereEnabled(true)->first();
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
        $states = State::select(
          'id',
          'code',
          'name',
          'slug',
          'country_code'
        )->where('country_code', $country->iso2)
          ->when($findable, function ($query) use ($findable) {
            return $query->search($findable);
          })->get();
        $response['status'] = 'success';
        $response['states'] = $states;
        return response()->json($response, Response::HTTP_OK);
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
