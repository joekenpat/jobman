<?php

namespace Database\Seeders;

use Illuminate\Support\Str;

class ReadStateCSV
{
  public function __construct()
  {
    $this->file = fopen(resource_path('/db_data/states.csv'), 'r');
    $this->delimiter = ",";
    $this->iterator = 0;
    $this->header = ['id', 'country_code', 'name'];
  }

  public function csvToArray()
  {
    $data = [];
    while (($row = fgetcsv($this->file, 2000, $this->delimiter)) !== false) {
      $is_mul_1000 = false;
      if (!$this->header) {
        $this->header = $row;
      } else {
        $this->iterator++;
        $row[0] = (int) $row[0];
        $data[] = array_combine($this->header, $row);
        if ($this->iterator != 0 && $this->iterator % 2000 == 0) {
          $is_mul_1000 = true;
          $chunk = $data;
          $data = [];
          yield $chunk;
        }
      }
    }
    fclose($this->file);
    if (!$is_mul_1000) {
      yield $data;
    }
    return;
  }
}
