<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'icon','slug',
  ];

  /**
   * The attributes that are countable.
   *
   * @var array
   */
  protected $withCount = [
    'ads',
  ];


  public function ads()
  {
    return $this->hasMany(Ad::class);
  }

  public function getIconAttribute()
  {
    return $this->icon !== null ? asset('images/categories/' . $this->icon) : null;
  }
}
