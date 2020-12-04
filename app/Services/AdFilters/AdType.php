<?php

namespace App\Services\AdFilters;

use App\Services\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class AdType implements Filter
{

  /**
   * Apply a given search value to the builder instance.
   *
   * @param Builder $builder
   * @param mixed $value
   * @return Builder $builder
   */
  public static function apply(Builder $builder, $value)
  {
    $rules = [
      'ad_type' => 'in:full_time,part_time,once',
    ];
    $valid_value = ['ad_type' => $value];
    $validator = Validator::make($valid_value, $rules);
    if (!$validator->fails()) {
      return $builder
        ->where('ad_type', $value);
    } else {
      return $builder;
    }
  }
}
