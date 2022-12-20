<?php
namespace App\Enums;
use App\Traits\{EnumOptions,EnumValues};
enum StatusEnum: string
{
    use EnumValues;
    use EnumOptions;

    case ACTIVE    = 'active';
    case INACTIVE   = 'inactive';
}
