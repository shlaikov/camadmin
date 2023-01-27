<?php

declare(strict_types=1);

namespace App\Data\Camunda\VariableType;

class BooleanType
{
    public function __invoke($value): array
    {
        return ['value' => (bool) $value, 'type' => 'Boolean'];
    }
}
