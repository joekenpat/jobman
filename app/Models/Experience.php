<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;

class Experience extends Model
{
  public function uuidColumns(): array
  {
    return ['user_id'];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'position', 'company_name',
    'summary', 'country_code', 'state_code',
    'lga_code', 'started_at', 'ended_at',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'user_id' => EfficientUuid::class,
    'started_at' => EfficientUuid::class,
    'ended_at' => EfficientUuid::class,
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function state()
  {
    return $this->belongsTo(State::class, 'state_code');
  }

  public function lga()
  {
    return $this->belongsTo(Lga::class, 'lga_code');
  }
}
