<?php

declare(strict_types=1);

namespace App\Data\Camunda\VariableType;

class StringType
{
    public function __invoke($value): array
    {
        return ['value' => (string) $value, 'type' => 'String'];
    }
}
