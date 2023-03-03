<?php

namespace App\Http\Camunda;

interface ClientInterface
{
    public static function index(array $params = []);

    public static function find(string $id);

    public static function create(array $params = []);

    public static function delete(string $id, bool $cascade = false);

    public static function update(string $id);
}
