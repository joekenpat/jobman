<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::statement('SET FOREIGN_KEY_CHECKS  = 0;');
    DB::disableQueryLog();
    $city_count = 1043237;
    $cityProgressBar = $this->command->getOutput()->createProgressBar($city_count);
    $cityProgressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% ');
    $csv_reader = new ReadPlaceCSV();
    foreach ($csv_reader->csvToArray() as $data) {
      Place::insertOrIgnore($data);
      $cityProgressBar->advance(count($data));
      $data = [];
    }
    $csv_reader = null;
    DB::enableQueryLog();
    DB::statement('SET FOREIGN_KEY_CHECKS  = 1;');
  }
}
