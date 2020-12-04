<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
  const filterables = [
    'name', 'state', 'lga', 'country',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'name', 'slug', 'country_code', 'state_id',
    'lga_id', 'latitude', 'longitude', 'timezone'
  ];

  public function ads()
  {
    return $this->hasMany(Ad::class, 'place_id');
  }

  public function job_seekers_resident()
  {
    return $this->hasMany(User::class, 'resident_place_id');
  }

  public function job_seekers_origin()
  {
    return $this->hasMany(User::class, 'origin_place_id');
  }

  public function experiences()
  {
    return $this->hasMany(Experience::class, 'place_id');
  }

  public function educations()
  {
    return $this->hasMany(Education::class, 'place_id');
  }

  public function references()
  {
    return $this->hasMany(Reference::class, 'place_id');
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_id');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code');
  }
}
