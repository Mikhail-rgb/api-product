<?php
declare(strict_types=1);


namespace App\Validator;


use App\Enum\ErrorCodeEnum;
use RuntimeException;

class ProductValidator
{
    public function checkRequiredProperties(array $inputProperties): void
    {
        $propertiesCounter = 0;
        foreach ($inputProperties as $key => $value) {

            switch ($key) {
                case "sku":
                    $propertiesCounter++;
                    break;
                case "title":
                    $propertiesCounter++;
                    break;
                case "amount":
                    $propertiesCounter++;
                    break;
                case "currency":
                    $propertiesCounter++;
                    break;
                case "type":
                    $propertiesCounter++;
                    break;

                default:
                    throw new RuntimeException(
                        sprintf(
                            'Unknown property %s',
                            $key
                        ),
                        ErrorCodeEnum::CREATION_FAILED
                    );
            }
        }

        if ($propertiesCounter != 5) {
            throw new RuntimeException(
                'Not enough properties. Expected properties: `sku`, `title`, `amount`, `currency`, `type`.',
                ErrorCodeEnum::CREATION_FAILED
            );
        }
    }
}