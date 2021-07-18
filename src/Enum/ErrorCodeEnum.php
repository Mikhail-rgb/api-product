<?php
declare(strict_types=1);


namespace App\Enum;


class ErrorCodeEnum
{
    public const CREATION_FAILED = 1;
    public const PROPERTY_NOT_SPECIFIED = 2;
    public const NEGATIVE_AMOUNT = 3;
    public const UNKNOWN_PROPERTY = 4;
    public const SAME_SKU_DIFFERENT_TYPES = 5;
    public const PROPERTY_NOT_FOUND = 6;
}