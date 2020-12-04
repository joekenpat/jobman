<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SkillController extends Controller
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
      'name' => 'required|alpha|min:3|max:255',
      'percentage' => 'sometimes|nullable|string|max:15|min:8',
    ]);
    $attribs = ['institution_name', 'position', 'phone',];

    $new_skill = new Skill();
    foreach ($attribs as $attrib) {
      if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
        $new_skill->{$attrib} = $request->{$attrib};
      }
    }

    $new_skill->name = $request->first_name . " " . $request->last_name;
    $new_skill->user_id = Auth()->user()->id;
    $new_skill->save();
    $response['status'] = 'success';
    $response['message'] = 'Skill Added';
    return response()->json($response, Response::HTTP_CREATED);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Str  $skill_id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $skill_id)
  {
    if (Skill::whereId($skill_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      $updateable_skill = Skill::whereId($skill_id)->whereUuid(Auth()->user()->id, 'user_id')->firstOrFail();
      $this->validate($request, [
        'name' => 'required|alpha|min:3|max:255',
        'percentage' => 'sometimes|nullable|string|max:15|min:8',
      ]);
      $attribs = ['institution_name', 'position', 'phone',];

      foreach ($attribs as $attrib) {
        if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
          $updateable_skill->{$attrib} = $request->{$attrib};
        }
      }

      $old_names = explode(' ', $updateable_skill->name);
      if ($request->has('first_name') && $request->first_name != (null || '')) {
        $updateable_skill->name = $old_names[1] . ' ' . $request->first_name;
      }
      if ($request->has('last_name') && $request->last_name != (null || '')) {
        $updateable_skill->name = $old_names[0] . ' ' . $request->last_name;
      }
      $updateable_skill->update();
      $response['status'] = 'success';
      $response['message'] = 'Skill Updated';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Skill  $skill
   * @return \Illuminate\Http\Response
   */
  public function destroy($skill_id)
  {
    if (Skill::whereId($skill_id)->whereUuid(Auth()->user()->id, 'user_id')->exists()) {
      Skill::whereId($skill_id)->whereUuid(Auth()->user()->id, 'user_id')->delete();
      $response['status'] = 'success';
      $response['message'] = 'Skill Removed';
      return response()->json($response, Response::HTTP_CREATED);
    }
  }
}
