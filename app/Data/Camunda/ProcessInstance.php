<?php

declare(strict_types=1);

namespace App\Data\Camunda;

use Spatie\LaravelData\Data;

class ProcessInstance extends Data
{
    public function __construct(
        public string $id,
        public string|null $tenantId,
        public string|null $businessKey,
        public array $links,
        public string $definitionId,
        public ?string $caseInstanceId,
        public bool $ended,
        public bool $suspended,
        public array|null $variables
    ) {
    }
}
