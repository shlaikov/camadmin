<?php

namespace App\Data\Camunda;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class Task extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string|null $assignee,
        public Carbon $created,
        public Carbon|null $lastUpdated,
        public string|null $due,
        public string|null $followUp,
        public string|null $delegationState,
        public string|null $description,
        public string $executionId,
        public string|null $owner,
        public string|null $parentTaskId,
        public string $priority,
        public string $processDefinitionId,
        public string $processInstanceId,
        public string $taskDefinitionKey,
        public string|null $caseExecutionId,
        public string|null $caseInstanceId,
        public string|null $caseDefinitionId,
        public bool $suspended,
        public string|null $formKey,
        public array|null $camundaFormRef,
        public string|null $tenantId,
    ) {
    }
}
