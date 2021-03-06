<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class UserController extends Controller
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
  public function default_register(Request $request)
  {
    $this->validate($request, [
      'title' => 'required|in:mr,ms',
      'first_name' => 'required|alpha',
      'last_name' => 'required|alpha',
      'phone' => 'sometimes|nullable|string|max:15|min:8|unique:users,phone',
      'state' => 'required|integer|exists:states,id',
      'lga' => 'required|integer|exists:lgas,id',
      'place' => 'required|integer|exists:places,id',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|',
    ]);

    $attribs = [
      'title',
      'phone',
      'state',
      'lga',
      'place',
      'email',
      'password'
    ];

    $new_user = new User();
    foreach ($attribs as $attrib) {
      if (in_array($attrib, ['state', 'place', 'lga'])) {
        $new_user->{$attrib . '_id'} = $request->{$attrib};
      } else {
        $new_user->{$attrib} = $request->{$attrib};
      }
    }

    $new_user->name = $request->first_name . " " . $request->last_name;
    $new_user->save();
    $response['status'] = 'success';
    $response['message'] = 'Account has been created';
    $response['token'] = $new_user->createToken('jobman_personal_access_token', ['employee'])->accessToken;
    return response()->json($response, Response::HTTP_CREATED);
  }

  /**
   * User Default login
   *
   * @param \illuminate\Http\Client\Request $request
   * @return \Illuminate\Http\
   */
  public function default_login(Request $request)
  {
    $auth_by = $this->find_default_auth_by($request);

    $messages = [
      'identifier.required' => 'Email or Phone Number is Required',
      'email.exists' => 'No Account With That Email',
      'phone.exists' => 'No Account With That Phone Number',
      'password.required' => 'Password cannot be empty',
    ];

    $this->validate($request, [
      'identifier' => 'required|string',
      'password' => 'required|string',
      'email' => 'sometimes|string|exists:users,' . $auth_by,
    ], $messages);

    $credentials = [
      "{$auth_by}" => $request->input('identifier'),
      'password' => $request->input('password'),
    ];

    if (Auth::attempt($credentials, true)) {
      $user = User::where($auth_by, $request->input('identifier'))->first();
      auth('web')->login($user, true);
      $this->auth_success($user);
      $response['status'] = 'success';
      $response['message'] = 'Log-in Successfull';
      $response['token'] = $user->createToken('jobman_personal_access_token', ['employee'])->accessToken;
      return response()->json($response, Response::HTTP_OK);
    } else {
      $response['message'] = 'Invalid Credentials';
      $response['errors'] = ['password' => ['Password Incorrect']];
      return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }


  /**
   * Update User.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $this->validate($request, [
      'first_name' => 'sometimes|nullable|alpha|max:25|min:2',
      'last_name' => 'sometimes|nullable|alpha|max:25|min:2',
      'dob' => 'sometimes|nullable|date',
      'phone' => 'sometimes|nullable|string|max:15|min:8',
      'place' => 'sometimes|nullable|alpha_dash|exists:places,id',
      'state' => 'sometimes|nullable|alpha_dash|exists:states,id',
      'lga' => 'sometimes|nullable|alpha_dash|exists:lgas,id',
      'bio' => 'sometimes|nullable|string|min:5|max:255',
      'address' => 'sometimes|nullable|string|min:5|max:255',
      'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);
    try {
      $user = User::where('id', Auth()->user()->id)->firstOrFail();
      //adding images
      if ($request->hasFile('avatar') && $request->avatar != null) {
        $image = Image::make($request->file('avatar'))->encode('jpg', 1);
        if (Auth()->user()->avatar != null) {
          if (File::exists("images/users/" . Auth()->user()->avatar)) {
            File::delete("images/users/" . Auth()->user()->avatar);
          }
        }
        $img_name = sprintf("%s%s.jpg", strtolower(Str::random(15)));
        $image->save(public_path("images/users/") . $img_name, 70, 'jpg');
        $request->avatar = $img_name;
        $user->avatar = $img_name;
      }
      $attribs = [
        'title',
        'phone',
        'state',
        'lga',
        'place',
        'email',
        'password'
      ];
      foreach ($attribs as $attrib) {
        if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
          if ($attrib == 'dob') {
            $user->{$attrib} = Carbon::parse($request->{$attrib});
          } elseif (in_array($attrib, ['state', 'place', 'lga'])) {
            $user->{$attrib . '_id'} = $request->{$attrib};
          } else {
            $user->{$attrib} = $request->{$attrib};
          }
        }
      }

      $old_names = explode(' ', $user->name);
      if ($request->has('first_name') && $request->first_name != (null || '')) {
        $user->name = $old_names[1] . ' ' . $request->first_name;
      }
      if ($request->has('last_name') && $request->last_name != (null || '')) {
        $user->name = $old_names[0] . ' ' . $request->last_name;
      }
      $user->update();

      $response['status'] = 'success';
      $response['message'] = 'Profile has been updated';
      $response['user'] = $user;
      return response()->json($response, Response::HTTP_OK);
    } catch (ModelNotFoundException $mnt) {
      $response['status'] = 'error';
      $response['message'] = 'User not Found';
      return response()->json($response, Response::HTTP_NOT_FOUND);
    } catch (\Exception $e) {
      $response['status'] = 'error';
      $response['message'] = $e->getMessage();
      return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function logout()
  {
    Auth::user()->token()->revoke();
    Auth::logout();
    $response['status'] = 'success';
    $response['message'] = 'User Logged Out';
    return response()->json($response, Response::HTTP_OK);
  }

  public function list_user_notifications()
  {
    $user = User::find(Auth()->user()->id)->first();
    $notifications = $user->unreadNotifications()->paginate(20);
    $response['status'] = 'success';
    $response['notifications'] = $notifications;
    return response()->json($response, Response::HTTP_OK);
  }

  public function mark_user_notification_as_read($notification_id)
  {
    $user = User::find(Auth()->user()->id)->first();
    $notification = $user->notifications()->whereId($notification_id)->first();
    $notification->markAsRead();
    $response['status'] = 'success';
    $response['messages'] = 'Notification marked as Read';
    return response()->json($response, Response::HTTP_OK);
  }


  public function mark_user_all_notification_as_read()
  {
    $user = User::find(Auth()->user()->id)->first();
    $user->unreadNotifications()->update(['read_at' => now()]);
    $response['status'] = 'success';
    $response['messages'] = 'All notifications marked as Read';
    return response()->json($response, Response::HTTP_OK);
  }

  public function find_default_auth_by(Request $request)
  {
    $login_data = $request->identifier;
    if (filter_var($login_data, FILTER_VALIDATE_EMAIL)) {
      $login_field_type = 'email';
    } else {
      $login_field_type = 'phone';
    }
    request()->merge([$login_field_type => $login_data]);
    return $login_field_type;
  }

  public function update_password(Request $request)
  {
    $this->validate($request, [
      'current_password' => 'required|string',
      'new_password' => 'required|string',
      'retype_new_password' => 'required|string|same:new_password',
    ]);

    $user = User::find(Auth::id());
    $credentials = [
      "email" => $user->email,
      'password' => $request->input('current_password'),
    ];

    if (Auth::guard('web')->attempt($credentials)) {
      $user->password = Hash::make($request->input('new_password'));
      $user->update;
      $this->auth_success($user);
      $response['status'] = 'success';
      $response['message'] = 'Password Change Successfull';
      $response['token'] = $user->createToken('bellefu')->accessToken;
      return response()->json($response, Response::HTTP_OK);
    } else {
      $response['message'] = 'Invalid Credentials';
      $response['errors'] = ['current_password' => ['Current Password Incorrect']];
      return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  protected function auth_success($user)
  {
    $user->update([
      'last_login' => now()->format('Y-m-d H:i:s.u'),
      'last_ip' => request()->getClientIp(),
    ]);
    return;
  }
}
