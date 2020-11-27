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
    'origin_country_code',
    'origin_state_code',
    'resident_country_code',
    'resident_state_code',
    'resident_lga_code',
    'resident_place_code',
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

  // current location details
  public function resident_country()
  {
    return $this->belongsTo(Country::class, 'resident_country_code');
  }

  public function resident_state()
  {
    return $this->belongsTo(State::class, 'resident_state_code');
  }

  public function resident_lga()
  {
    return $this->belongsTo(Lga::class, 'resident_lga_code');
  }

  public function resident_place()
  {
    return $this->belongsTo(Place::class, 'resident_place_code');
  }


  //birth location country
  public function origin_country()
  {
    return $this->belongsTo(Country::class, 'origin_country_code');
  }

  public function origin_state()
  {
    return $this->belongsTo(State::class, 'origin_state_code');
  }
}
