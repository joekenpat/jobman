<?php

namespace App\Services\AdFilters;

use App\Services\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class MaxWageAmount implements Filter
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
      'max_wage' => 'numeric|between:1,100000000',
    ];
    $valid_value = ['max_wage' => $value];
    $validator = Validator::make($valid_value, $rules);
    if (!$validator->fails()) {
      return $builder
        ->where('max_wage_amount', '<=',$value);
    } else {
      return $builder;
    }
  }
}
