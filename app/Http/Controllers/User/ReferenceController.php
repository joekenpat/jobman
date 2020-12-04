<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class ReferenceController extends Controller
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
      'first_name' => 'required|alpha|min:3|max:100',
      'last_name' => 'required|alpha|min:3|max:100',
      'institution_name' => 'required|string|min:3|max:255',
      'position' => 'required|string|min:3|max:255',
      'phone' => 'sometimes|nullable|string|max:15|min:8',
    ]);

    $attribs = ['institution_name', 'position', 'phone',];

    $new_reference = new Reference();
    foreach ($attribs as $attrib) {
      if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
        $new_reference->{$attrib} = $request->{$attrib};
      }
    }

    $new_reference->name = $request->first_name . " " . $request->last_name;
    $new_reference->user_id = Auth()->user()->id;
    $new_reference->save();
    $response['status'] = 'success';
    $response['message'] = 'Reference Added';
    return response()->json($response, Response::HTTP_CREATED);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str  $reference_id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $reference_id)
  {
    if (Reference::whereId($reference_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      $updateable_reference = Reference::whereId($reference_id)->whereUuid(Auth()->user()->id, 'user_id')->firstOrFail();
      $this->validate($request, [
        'first_name' => 'required|alpha|min:3|max:100',
        'last_name' => 'required|alpha|min:3|max:100',
        'institution_name' => 'required|string|min:3|max:255',
        'position' => 'required|string|min:3|max:255',
        'phone' => 'sometimes|nullable|string|max:15|min:8',
      ]);
      $attribs = ['institution_name', 'position', 'phone',];

      foreach ($attribs as $attrib) {
        if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
          $updateable_reference->{$attrib} = $request->{$attrib};
        }
      }

      $old_names = explode(' ', $updateable_reference->name);
      if ($request->has('first_name') && $request->first_name != (null || '')) {
        $updateable_reference->name = $old_names[1] . ' ' . $request->first_name;
      }
      if ($request->has('last_name') && $request->last_name != (null || '')) {
        $updateable_reference->name = $old_names[0] . ' ' . $request->last_name;
      }
      $updateable_reference->update();
      $response['status'] = 'success';
      $response['message'] = 'Reference Updated';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Reference  $reference
   * @return \Illuminate\Http\Response
   */
  public function destroy($reference_id)
  {
    if (Reference::whereId($reference_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      Reference::whereId($reference_id)->whereUuid(Auth()->user()->id, 'user_id')->delete();
      $response['status'] = 'success';
      $response['message'] = 'Reference Removed';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }
}
