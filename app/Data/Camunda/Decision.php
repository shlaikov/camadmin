<?php

declare(strict_types=1);

namespace App\Data\Camunda;

use Spatie\LaravelData\Data;

class Decision extends Data
{
    public function __construct(
        public string $id,
        public string $key,
        public string $category,
        public string $name,
        public string $version,
        public string $resource,
        public string $deploymentId,
        public ?string $tenantId,
        public string $decisionRequirementsDefinitionId,
        public string $decisionRequirementsDefinitionKey,
        public ?string $historyTimeToLive,
        public ?string $versionTag
    ) {
    }
}
