<?php

namespace App\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Model
{
  use HasApiTokens, Notifiable, GeneratesUuid;

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
    'avatar',
    'title',
    'name',
    'phone',
    // 'origin_country_code',
    // 'origin_state_id',
    'resident_country_code',
    'resident_state_id',
    'resident_lga_id',
    'resident_place_id',
    'address',
    'status',
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

  // current location details
  public function resident_country()
  {
    return $this->belongsTo(Country::class, 'resident_country_code');
  }

  public function resident_state()
  {
    return $this->belongsTo(State::class, 'resident_state_id');
  }

  public function resident_lga()
  {
    return $this->belongsTo(Lga::class, 'resident_lga_id');
  }

  public function resident_place()
  {
    return $this->belongsTo(Place::class, 'resident_place_id');
  }


  //birth location country
  // public function origin_country()
  // {
  //   return $this->belongsTo(Country::class, 'origin_country_code');
  // }

  // public function origin_state()
  // {
  //   return $this->belongsTo(State::class, 'origin_state_id');
  // }
}
