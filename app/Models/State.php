<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

  const filterables = [
    'name', 'country',
  ];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'name', 'slug', 'country_code', 'code',
  ];

  public function ads()
  {
    return $this->hasMany(Ad::class, 'state_code');
  }

  public function job_seekers_resident()
  {
    return $this->hasMany(User::class, 'resident_state_code');
  }

  public function job_seekers_origin()
  {
    return $this->hasMany(User::class, 'origin_state_code');
  }

  public function experiences()
  {
    return $this->hasMany(Experience::class, 'state_code');
  }

  public function educations()
  {
    return $this->hasMany(Education::class, 'state_code');
  }

  public function references()
  {
    return $this->hasMany(Reference::class, 'state_code');
  }

  public function lgas()
  {
    return $this->hasMany(Lga::class, 'state_code');
  }

  public function places()
  {
    return $this->hasMany(Place::class, 'state_code');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code');
  }
}
