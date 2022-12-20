<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('surveys')->insert([
      [
        'created_by' => 1,
        'title' => 'entertainment survey',
        'status' => StatusEnum::ACTIVE
      ],
      [
        'created_by' => 1,
        'title' => 'financial survey',
        'status' => StatusEnum::ACTIVE
      ],
      [
        'created_by' => 1,
        'title' => 'religion survey',
        'status' => StatusEnum::ACTIVE
      ],
    ]);
  }
}
