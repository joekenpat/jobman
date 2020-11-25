<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Casts\EfficientUuid;

class Ad extends Model
{
  use GeneratesUuid;

  public function uuidColumn(): string
  {
    return 'id';
  }

  public function uuidColumns(): Array
  {
    return ['id','employer_id'];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'employer_id', 'category_id', 'resolved_by',
    'state_code', 'lga_code', 'place_code',
    'inorganic_view', 'title', 'plan', 'wage_negotiable',
    'wage_type', 'wage_amount', 'status', 'service_type',
    'latitude', 'longitude', 'description', 'address',
    'decline_reason', 'end_at',
  ];


  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => EfficientUuid::class,
    'end_at' => 'datetime',
    'wage_negotiable' => 'boolean',
  ];


  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'resolved_by',
  ];

  public function applications()
  {
    return $this->hasMany(AdApplication::class);
  }

  public function employer()
  {
    return $this->belongsTo(User::class);
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
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
