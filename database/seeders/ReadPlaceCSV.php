<?php

namespace Database\Seeders;

use Illuminate\Support\Str;

class ReadPlaceCSV
{

  /**
   * City CSV Files Reader
   * Accepts File number as param
   * @param Int $city_number
   *
   */
  public function __construct()
  {

    /**
     * City CSV File Path
     */
    $this->file = fopen(resource_path('/db_data/us_places.csv'), 'r');
    /**
     * City CSV Delimiter
     */
    $this->delimiter = ",";
    $this->iterator = 0;
    /**
     * Header to combine to each CSV row
     * same as DB City columns
     */
    $this->header = ['id', 'country_code', 'state_id', 'lga_id', 'name', 'latitude', 'longitude', 'timezone', 'slug'];
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
        $row[2] = (int) $row[2];
        $row[3] = (int) $row[3];
        $row[5] = (float) $row[5];
        $row[6] = (float) $row[6];
        $row[] = Str::slug($row[4] . '-' . $row[0]);
        $data[] = array_combine(
          $this->header,
          $row,
        );
        if ($this->iterator != 0 && $this->iterator % 2000 == 0) {
          $is_mul_1000 = true;
          yield $data;
          $data = [];
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
