<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;

class InstanceData extends Data
{
    public function __construct(
        #[WithoutValidation, Unique('instances')]
        public readonly ?int $id,
        #[WithoutValidation, Date]
        public readonly ?Carbon $created_at,
        #[WithoutValidation, Date]
        public readonly ?Carbon $updated_at,
        #[Required, StringType]
        public string $name,
        #[StringType]
        public ?string $description,
        #[StringType]
        public readonly string $type,
        #[Required, StringType]
        public string $url,
        #[Nullable, StringType]
        public ?string $tenant_id,
        #[BooleanType]
        public bool $has_counter = false,
    ) {
    }
}
