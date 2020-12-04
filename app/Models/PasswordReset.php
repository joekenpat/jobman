<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;

class PasswordReset extends Model
{

  public function uuidColumn(): array
  {
    return ['resetable_id'];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'resetable_id', 'resetable_type', 'code', 'used', 'expires_at'
  ];

  /**
   * Get the owning password resetable model.
   */
  public function resetable()
  {
    return $this->morphTo();
  }

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'used' => 'boolean',
    'resetable_id' => EfficientUuid::class,
    'expires_at' => 'datetime',
  ];
}
