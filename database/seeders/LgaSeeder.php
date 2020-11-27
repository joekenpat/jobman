<?php

namespace Database\Seeders;

use App\Models\lga;
use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LgaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    try {
      $lga_count = 44796;
      $lgaProgressBar = $this->command->getOutput()->createProgressBar($lga_count);
      $lgaProgressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% ');
      $csv_reader = new ReadLgaCSV();
      DB::statement('SET FOREIGN_KEY_CHECKS  = 0;');
      DB::disableQueryLog();
      foreach ($csv_reader->csvToArray() as $data) {
        lga::insert($data);
        $lgaProgressBar->advance(count($data));
      }
      DB::statement('SET FOREIGN_KEY_CHECKS  = 1;');
      DB::enableQueryLog();
      $lgaProgressBar->finish();
    } catch (QueryException $qe) {
      if ($qe->errorInfo[0] == "23000" && $qe->errorInfo[1] == "1062") {
        Log::error(dd($qe->errorInfo));
      }
    }
  }
}
