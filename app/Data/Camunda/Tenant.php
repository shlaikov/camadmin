<?php

namespace App\Data\Camunda;

use Spatie\LaravelData\Data;

class Tenant extends Data
{
    public function __construct(
        public string $id,
        public string $name
    ) {
    }
}
