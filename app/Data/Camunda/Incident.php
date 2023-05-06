<?php

declare(strict_types=1);

namespace App\Data\Camunda;

use Spatie\LaravelData\Data;

class Incident extends Data
{
    public function __construct(
        public string $id
    ) {
    }
}
