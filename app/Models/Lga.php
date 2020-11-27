<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lga extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'country_code', 'state_code', 'code',
  ];

  public function ads()
  {
    return $this->hasMany(Ad::class, 'lga_code');
  }

  public function job_seekers_resident()
  {
    return $this->hasMany(User::class, 'resident_lga_code');
  }

  public function job_seekers_origin()
  {
    return $this->hasMany(User::class, 'origin_lga_code');
  }

  public function experiences()
  {
    return $this->hasMany(Experience::class, 'lga_code');
  }

  public function educations()
  {
    return $this->hasMany(Education::class, 'lga_code');
  }

  public function references()
  {
    return $this->hasMany(Reference::class, 'lga_code');
  }

  public function places()
  {
    return $this->hasMany(Place::class, 'lga_code');
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_code');
  }

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_code');
  }
}
