<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array<int, mixed> $attributes
     * 
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param string $id
     * 
     * @return Model|null
     */
    public function find(string $id): ?Model;
}
