<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Casts\EfficientUuid;

class AdPlan extends Model
{
  public function uuidColumns(): array
  {
    return ['ad_id'];
  }
  // use SoftDeletes;
  protected $fillable = [
    'ad_id',
    'downgrade_id',
    'name',
    'status',
    'expires_at',
  ];


  /**
   * The datetime format for this model.
   *
   * @var String
   */
  protected $dateFormat = 'Y-m-d H:i:s.u';


  protected $hidden = ['deleted_at'];
  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'expires_at' => 'datetime',
    'ad_id' => EfficientUuid::class,
  ];


  public function ad()
  {
    return $this->belongsTo(Product::class, 'ad_id');
  }
}
