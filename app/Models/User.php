<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Casts\EfficientUuid;

class User extends Authenticatable
{
  use Notifiable, GeneratesUuid;


  public function uuidColumn(): string
  {
    return 'id';
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'name',
    'dob',
    'phone',
    'state_code',
    'lga_code',
    'place_code',
    'address',
    'bio',
    'last_ip',
    'last_login',
    'blocked_at',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'id' => EfficientUuid::class,
    'blocked_at' => 'datetime',
    ''
  ];

  public function applications()
  {
    return $this->hasMany(AdApplication::class);
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_code');
  }

  public function lga()
  {
    return $this->belongsTo(Lga::class, 'lga_code');
  }

  public function place()
  {
    return $this->belongsTo(Place::class, 'place_code');
  }
}
