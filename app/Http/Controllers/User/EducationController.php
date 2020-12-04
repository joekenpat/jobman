<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class EducationController extends Controller
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
      'institution_name' => 'required|string|min:3|max:255',
      'certificate_obtained' => 'required|string|min:3|max:255',
      'state' => 'required|integer|exists:states,id',
      'lga' => 'required|integer|exists:lgas,id',
      'started' => 'required  |date',
      'ended' => 'sometimes|nullable|date',
    ]);

    $attribs = [
      'institution_name', 'certificate_obtained',
      'state', 'lga', 'started', 'ended',
    ];

    $new_education = new Education();
    foreach ($attribs as $attrib) {
      if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
        if ($attrib === 'ended' || $attrib === 'started') {
          $new_education->{$attrib . '_at'} = Carbon::parse($request->$attrib)->format('Y-m-d');
        } elseif (in_array($attrib, ['state', 'lga'])) {
          $new_education->{$attrib . '_id'} = $request->{$attrib};
        } else {
          $new_education->{$attrib} = $request->{$attrib};
        }
      }
    }

    $new_education->user_id = Auth()->user()->id;
    $new_education->save();
    $response['status'] = 'success';
    $response['message'] = 'Education Added';
    return response()->json($response, Response::HTTP_CREATED);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str  $education_id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $education_id)
  {
    if (Education::whereId($education_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      $updateable_education = Education::whereId($education_id)->whereUuid(Auth()->user()->id, 'user_id')->firstOrFail();
      $this->validate($request, [
        'institution_name' => 'sometimes|nullable|string|min:3|max:255',
        'certificate_obtained' => 'sometimes|nullable|string|min:3|max:255',
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
            $updateable_education->{$attrib . '_at'} = Carbon::parse($request->$attrib)->format('Y-m-d');
          } elseif (in_array($attrib, ['state', 'lga'])) {
            $updateable_education->{$attrib . '_id'} = $request->{$attrib};
          } else {
            $updateable_education->{$attrib} = $request->{$attrib};
          }
        }
      }
      $updateable_education->update();
      $response['status'] = 'success';
      $response['message'] = 'Education Updated';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Education  $education
   * @return \Illuminate\Http\Response
   */
  public function destroy($education_id)
  {
    if (Education::whereId($education_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      Education::whereId($education_id)->whereUuid(Auth()->user()->id, 'user_id')->delete();
      $response['status'] = 'success';
      $response['message'] = 'Education Removed';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }
}
