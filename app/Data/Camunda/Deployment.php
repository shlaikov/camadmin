<?php

namespace App\Data\Camunda;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class Deployment extends Data
{
    public function __construct(
        public string $id,
        public string|null $tenantId,
        public ?string $name,
        public ?string $source,
        public ?array $processDefinitions,
        public ?array $links,
        public Carbon $deploymentTime,
        public ?array $deployedProcessDefinitions,
        public ?array $deployedCaseDefinitions,
        public ?array $deployedDecisionDefinitions,
        public ?array $deployedDecisionRequirementsDefinitions
    ) {
    }
}
