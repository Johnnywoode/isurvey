<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AnswerFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'answered_by' => fake()->randomDigitNotNull(),
      'question_id' => fake()->randomDigitNotNull(),
      'survey_id' => fake()->randomDigitNotNull(),
      'answer' => fake()->text(50)
    ];
  }
}
