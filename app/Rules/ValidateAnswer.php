<?php

namespace App\Rules;

use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class ValidateAnswer implements InvokableRule, DataAwareRule
{
    public $question_id;

    public function __construct($question_id = null)
    {
        $this->question_id = $question_id;
    }
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
        $question = ($this->question_id === null)
            ? Question::find($this->data['question_id'])
            : Question::find($this->question_id);

        if($question){
            switch ($question->type) {
                case QuestionType::TEXT->value:
                    if (is_array($value)) {
                        $fail('The :attribute must be text, array given');
                    }
                    break;

                case QuestionType::NUMBER->value:
                    if (!is_numeric($value)) {
                        $fail('The :attribute must be numeric.');
                    }
                    break;

                case QuestionType::SINGLE_CHOICE->value:
                    if(is_array($value))
                        $fail('The :attribute must be a string.');
                    if(!in_array($value, $question->options))
                        $fail('The :attribute is invalid. Required: one of <' . implode(', ', $question->options). '>');
                    break;

                case QuestionType::MULTIPLE_CHOICE->value:
                    if (!is_array($value)){
                        $fail('The :attribute must be an array.');
                    }else{
                        if (!empty(array_diff($value, $question->options)))
                            $fail('The :attribute contains invalid value. Required: at least one of <' . implode(', ', $question->options) . '>');
                    }
                    break;

                default:
                    $fail('The question being answered has invalid type. Required <' . implode(', ', QuestionType::values()) . '>');
                    break;
            }
        }else{
            $fail('The question_id provided is invalid');
        }
    }
}
