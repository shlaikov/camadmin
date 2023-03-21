<?php

declare(strict_types=1);

namespace App\Data\Camunda\VariableType;

class StringType
{
    public function __invoke(string $value): array
    {
        return ['value' => $value, 'type' => 'String'];
    }
}
