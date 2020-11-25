<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;

class Reference extends Model
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
    'user_id', 'name',
    'position', 'institution_name',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'user_id' => EfficientUuid::class,
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
