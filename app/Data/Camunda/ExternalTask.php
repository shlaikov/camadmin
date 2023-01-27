<?php

namespace App\Data\Camunda;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ExternalTask extends Data
{
    public function __construct(
        public string $id,
        public string $topicName,
        public int $priority,
        public string|null $workerId,
        public string|null $activityId,
        public string|null $activityInstanceId,
        public string|null $errorMessage,
        public string|null $errorDetails,
        public string|null $executionId,
        public string|null $businessKey,
        public Carbon|null $lockExpirationTime,
        public string $processDefinitionId,
        public string $processDefinitionKey,
        public string|null $processDefinitionVersionTag,
        public string $processInstanceId,
        public string|null $tenantId,
        public ?int $retries,
        public array|null $variables,
        public bool $suspended,
        public array|null $extensionProperties
    ) {
    }
}
