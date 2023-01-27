<?php

declare(strict_types=1);

namespace App\Data\Camunda;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ProcessInstanceHistory extends Data
{
    public function __construct(
        public string $id,
        public ?string $rootProcessInstanceId,
        public ?string $superProcessInstanceId,
        public ?string $superCaseInstanceId,
        public ?string $caseInstanceId,
        public ?string $processDefinitionName,
        public string $processDefinitionKey,
        public string $processDefinitionVersion,
        public string $processDefinitionId,
        public ?string $businessKey,
        public Carbon $startTime,
        public ?Carbon $endTime,
        public ?Carbon $removalTime,
        public ?int $durationInMillis,
        public ?string $startUserId,
        public string $startActivityId,
        public ?string $deleteReason,
        public ?string $tenantId,
        public string $state
    ) {
    }
}
