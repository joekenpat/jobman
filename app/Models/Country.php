<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'iso2', 'iso3', 'name',
    'slug', 'lang_code', 'phone_code',
    'enabled', 'emoji', 'emojiU',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'enabled' => 'boolean',
  ];


  public function job_seekers_resident()
  {
    return $this->hasMany(User::class, 'resident_country_code');
  }

  public function admin_seekers_resident()
  {
    return $this->hasMany(Admin::class, 'resident_country_code');
  }

  public function employer_seekers_resident()
  {
    return $this->hasMany(Employer::class, 'resident_country_code');
  }


  public function states()
  {
    return $this->hasMany(State::class, 'country_code');
  }

  public function lgas()
  {
    return $this->hasMany(Lga::class, 'country_code');
  }

  public function places()
  {
    return $this->hasMany(Place::class, 'country_code');
  }
}
