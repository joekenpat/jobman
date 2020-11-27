<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $row = 1;
    if (($handle = fopen(resource_path("db_data/countries.csv"), "r")) !== false) {
      $countryProgressBar = $this->command->getOutput()->createProgressBar(249);
      $countryProgressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% ');
      while (($data = fgetcsv($handle, 0, ",")) !== false) {
        if ($row === 1) {
          $row++;
          continue;
        }
        $row++;
        $country = [
          'name' => $data[1],
          'slug' => Str::slug($data[1]),
          'iso2' => $data[3],
          'iso3' => $data[2],
          'lang_code' => $data[10],
          'phone_code' => $data[4],
          'emoji' => $data[8],
          'emojiU' => $data[9],
        ];
        Country::create($country);
        $countryProgressBar->advance();
      }
      fclose($handle);
      $countryProgressBar->finish();
    }
  }
}
