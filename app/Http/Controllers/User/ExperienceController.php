<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
      'company_name' => 'required|string|min:3|max:255',
      'position' => 'required|string|min:3|max:255',
      'summary' => 'sometimes|nullable|string|min:3',
      'state' => 'required|integer|exists:states,id',
      'lga' => 'required|integer|exists:lgas,id',
      'started' => 'required  |date',
      'ended' => 'sometimes|nullable|date',
    ]);

    $attribs = [
      'company_name', 'position', 'summary',
      'state', 'lga', 'started', 'ended',
    ];

    $new_experience = new Experience();
    foreach ($attribs as $attrib) {
      if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
        if ($attrib === 'ended' || $attrib === 'started') {
          $new_experience->{$attrib . '_at'} = Carbon::parse($request->$attrib)->format('Y-m-d');
        } elseif (in_array($attrib, ['state', 'lga'])) {
          $new_experience->{$attrib . '_id'} = $request->{$attrib};
        } else {
          $new_experience->{$attrib} = $request->{$attrib};
        }
      }
    }

    $new_experience->user_id = Auth()->user()->id;
    $new_experience->save();
    $response['status'] = 'success';
    $response['message'] = 'Experience Added';
    return response()->json($response, Response::HTTP_CREATED);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str  $experience_id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $experience_id)
  {
    if (Experience::whereId($experience_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      $updateable_experience = Experience::whereId($experience_id)->whereUuid(Auth()->user()->id, 'user_id')->firstOrFail();
      $this->validate($request, [
        'company_name' => 'sometimes|nullable|string|min:3|max:255',
        'position' => 'sometimes|nullable|string|min:3|max:255',
        'summary' => 'sometimes|nullable|string|min:3',
        'state' => 'sometimes|nullable|integer|exists:states,id',
        'lga' => 'sometimes|nullable|integer|exists:lgas,id',
        'started' => 'sometimes|nullable|date',
        'ended' => 'sometimes|nullable|date',
      ]);

      $attribs = [
        'company_name', 'position', 'summary',
        'state', 'lga', 'started', 'ended',
      ];

      foreach ($attribs as $attrib) {
        if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
          if ($attrib === 'ended' || $attrib === 'started') {
            $updateable_experience->{$attrib . '_at'} = Carbon::parse($request->$attrib)->format('Y-m-d');
          } elseif (in_array($attrib, ['state', 'lga'])) {
            $updateable_experience->{$attrib . '_id'} = $request->{$attrib};
          } else {
            $updateable_experience->{$attrib} = $request->{$attrib};
          }
        }
      }
      $updateable_experience->update();
      $response['status'] = 'success';
      $response['message'] = 'Experience Updated';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Experience  $experience
   * @return \Illuminate\Http\Response
   */
  public function destroy($experience_id)
  {

    if (Experience::whereId($experience_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      Experience::whereId($experience_id)->whereUuid(Auth()->user()->id, 'user_id')->delete();
      $response['status'] = 'success';
      $response['message'] = 'Experience Removed';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }
}
