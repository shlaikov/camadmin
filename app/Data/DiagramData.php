<?php

namespace App\Data;

use App\Enums\DiagramEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Data;

class DiagramData extends Data
{
    public function __construct(
        #[Required]
        public readonly ?int $id,
        #[Date]
        public readonly ?Carbon $created_at,
        #[Date]
        public readonly ?Carbon $updated_at,
        #[Required, Uuid]
        public string $uuid,
        #[Nullable, StringType]
        public ?string $name,
        #[Required, StringType, In(DiagramEnum::class)]
        public string $type,
        #[Nullable, StringType]
        public ?string $url,
        #[BooleanType]
        public bool $is_public,
        #[Nullable, StringType]
        public ?string $preview
    ) {
    }
}
