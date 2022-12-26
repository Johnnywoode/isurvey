<?php

namespace App\Rules;

use App\Models\Question;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class SameSurveyAsQuestion implements InvokableRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $question = Question::find($this->data['question_id']);

        if(!$value == $question->survey_id){
            $fail('Survey_id and question_id fields do not match. Required: Question being answered must have the same survey_id as provided');
        }

    }
}
