<?php

declare(strict_types=1);

namespace App\Collections;

use Illuminate\Support\Collection;
use App\Data\Camunda\VariableType\BooleanType;
use App\Data\Camunda\VariableType\JsonType;
use App\Data\Camunda\VariableType\ObjectType;
use App\Data\Camunda\VariableType\StringType;

class VariableCollection extends Collection
{
    public function toArray()
    {
        $variables = [];

        foreach ($this->items as $key => $value) {
            $valueType = gettype($value);
            $typeClass = match ($valueType) {
                'array' => JsonType::class,
                'boolean' => BooleanType::class,
                'object' => ObjectType::class,
                default => StringType::class,
            };

            $variables[$key] = (new $typeClass())($value);
        }

        return $variables;
    }
}
