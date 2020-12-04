<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Casts\EfficientUuid;

class Ad extends Model
{
  use GeneratesUuid;

  const filterables = [
    'wage_rate', 'wage_type', 'max_wage_amount',
    'min_wage_amount', 'fixed_wage_amount', 'status',
    'service_type', 'state', 'lga', 'plan', 'title',
    'ad_presence', 'ad_type',
  ];

  public function uuidColumn(): string
  {
    return 'id';
  }

  public function uuidColumns(): array
  {
    return ['id', 'employer_id'];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'employer_id', 'category_id', 'resolved_by',
    'state_id', 'lga_id', 'place_id', 'wage_plan',
    'inorganic_view', 'title', 'plan', 'wage_rate',
    'plan_id', 'wage_type', 'max_wage_amount',
    'min_wage_amount', 'fixed_wage_amount', 'status',
    'ad_type', 'description', 'address', 'decline_reason',
    'end_at', 'avail_slot', 'summary', 'ad_presence',
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
    'plan_id', ''
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

  /**
   * Mark Ad as Closed.
   *
   * @return Boolean
   */
  public function close(): bool
  {
    if ($this->update(['status' => 'closed'])) {
      $this->refresh();
      return true;
    } else {
      return false;
    }
  }
}
