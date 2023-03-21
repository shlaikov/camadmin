<?php

declare(strict_types=1);

namespace App\Data\Camunda\VariableType;

class BooleanType
{
    public function __invoke(bool $value): array
    {
        return ['value' => $value, 'type' => 'Boolean'];
    }
}
