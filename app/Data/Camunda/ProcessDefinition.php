<?php

declare(strict_types=1);

namespace App\Data\Camunda;

use Spatie\LaravelData\Data;

class ProcessDefinition extends Data
{
    public function __construct(
        public string $id,
        public string $key,
        public string $category,
        public ?string $name,
        public ?string $description,
        public int $version,
        public string|null $tenantId,
        public string $resource,
        public string $deploymentId,
        public ?string $diagram,
        public bool $suspended,
        public ?string $versionTag,
        public ?string $historyTimeToLive,
        public bool $startableInTasklist
    ) {
    }
}
