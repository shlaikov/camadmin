<?php

declare(strict_types=1);

namespace App\Data\Camunda\VariableType;

class JsonType
{
    /**
     * @throws \JsonException
     */
    public function __invoke($value): array
    {
        return [
            'value' => json_encode($value, JSON_THROW_ON_ERROR),
            'type' => 'Json'
        ];
    }
}
