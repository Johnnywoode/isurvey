<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Enums\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('questions')->insert([
        [
          'survey_id' => 1,
          'question' => 'Are you entertained?',
          'type' => QuestionType::SINGLE_CHOICE,
          'options' => json_encode(['yes', 'no']),
          'status' => StatusEnum::ACTIVE
        ],[
          'survey_id' => 1,
          'question' => 'How happy are you?',
          'type' => QuestionType::SINGLE_CHOICE,
          'options' => json_encode(['not', 'quite', 'enough', 'very']),
          'status' => StatusEnum::ACTIVE
        ],[
          'survey_id' => 1,
          'question' => 'How old are you?',
          'type' => QuestionType::NUMBER,
          'options' => null,
          'status' => StatusEnum::ACTIVE
        ],
        [
          'survey_id' => 2,
          'question' => 'Do you like money?',
          'type' => QuestionType::SINGLE_CHOICE,
          'options' => json_encode(['yes', 'no']),
          'status' => StatusEnum::ACTIVE
        ],
        [
          'survey_id' => 2,
          'question' => 'How many of these do you want?',
          'type' => QuestionType::MULTIPLE_CHOICE,
          'options' => json_encode(['cedis', 'dollars', 'pounds', 'euros']),
          'status' => StatusEnum::ACTIVE
        ],
        [
          'survey_id' => 3,
          'question' => 'Are you religious?',
          'type' => QuestionType::SINGLE_CHOICE,
          'options' => json_encode(['yes', 'no']),
          'status' => StatusEnum::ACTIVE
        ],
        [
          'survey_id' => 3,
          'question' => 'Which of these religions do you belong to?',
          'type' => QuestionType::SINGLE_CHOICE,
          'options' => json_encode(['christianity', 'islam', 'african tradition']),
          'status' => StatusEnum::ACTIVE
        ],
        [
          'survey_id' => 3,
          'question' => 'Why do you like your religion?',
          'type' => QuestionType::TEXT,
          'options' => null,
          'status' => StatusEnum::ACTIVE
        ],
      ]);
    }
}
