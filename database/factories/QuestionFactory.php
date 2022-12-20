<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
  /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
      return [
        'survey_id' => $this->faker->randomDigitNotNull(),
        'question' => fake()->sentence(),
        'options' => fake()->randomElements(['a','b','c','d','e'], 3),
        'type' => fake()->randomElement(QuestionType::values()),
        'status' => 'active',
      ];
    }

  }
