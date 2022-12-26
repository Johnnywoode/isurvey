<?php
namespace App\Enums;
use App\Traits\{EnumOptions,EnumValues};

enum QuestionType: string
{
    use EnumValues;
    use EnumOptions;

    case TEXT    = 'text';
    case SINGLE_CHOICE   = 'single_choice';
    case MULTIPLE_CHOICE    = 'multiple_choice';
    case NUMBER   = 'number';
}
