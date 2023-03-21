<?php

namespace App\Http\Camunda;

interface ClientInterface
{
    public static function index(array $params = []): mixed;

    public static function find(string $id): mixed;

    public static function create(array $params = []): mixed;

    public static function delete(string $id, bool $cascade = false): mixed;

    public static function update(string $id): mixed;
}
