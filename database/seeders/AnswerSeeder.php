<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\QuestionType;
use App\Enums\StatusEnum;

use Illuminate\Support\Facades\DB;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    DB::table('answers')->insert([
      [
        'answered_by' => 1,
        'question_id' => 1,
        'answer' => 'yes',
      ],
      [
        'answered_by' => 1,
        'question_id' => 2,
        'answer' => 'quite',
      ],
      [
        'answered_by' => 1,
        'question_id' => 3,
        'answer' => '25',
      ]
    ]);
    }
}
