<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Casts\EfficientUuid;

class AdApplication extends Model
{
  use GeneratesUuid;


  public function uuidColumn(): string
  {
    return 'id';
  }

  public function uuidColumns(): array
  {
    return ['id', 'user_id', 'ad_id',];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'ad_id', 'cover_letter', 'status', 'employer_viewed_at'
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => EfficientUuid::class,
    'user_id' => EfficientUuid::class,
    'ad_id' => EfficientUuid::class,
    'employer_viewed_at' => 'datetime',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function ad()
  {
    return $this->belongsTo(Ad::class);
  }
}
