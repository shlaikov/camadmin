<?php

declare(strict_types=1);

namespace App\Data\Camunda;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class Incident extends Data
{
    public function __construct(
        public string $id,
        public string $processDefinitionId,
        public string $processInstanceId,
        public string $executionId,
        public Carbon $incidentTimestamp,
        public string $incidentType,
        public string $activityId,
        public string $causeIncidentId,
        public string $rootCauseIncidentId,
        public string $configuration,
        public ?string $tenantId,
        public string $incidentMessage,
        public ?string $jobDefinitionId,
    ) {
    }
}
