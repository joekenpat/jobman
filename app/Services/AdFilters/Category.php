<?php

namespace App\Services\AdFilters;

use App\Services\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class Category implements Filter
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
      'title' => 'string|min:5',
    ];
    $valid_value = ['title' => $value];
    $validator = Validator::make($valid_value, $rules);
    if (!$validator->fails()) {
      $findable = preg_replace("/[^A-Za-z0-9_ -]/", '', $value);
      $searchValues = preg_split('/\s+/', $findable, -1, PREG_SPLIT_NO_EMPTY);
      return $builder
        ->where(function ($query) use ($searchValues) {
          foreach ($searchValues as $value) {
            $query->orWhere('title', 'LIKE', "%{$value}%");
          }
        });
    } else {
      return $builder;
    }
  }
}
