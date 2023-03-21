<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use App\Models\Instance;
use App\Repository\Services\CamundaRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class CamundaClient implements ClientInterface
{
    public static function make(): PendingRequest
    {
        $instance = Instance::find(request()->segment(3));

        return Http::baseUrl($instance ? $instance->url : env('CAMUNDA_URL'));
    }

    public static function index(array $params = []): mixed
    {
        $params = implode(',', $params);

        throw new BadRequestException("Method with params [$params] not found");
    }

    public static function find(string $id): mixed
    {
        throw new BadRequestException("Method not found by $id");
    }

    public static function create(array $params = []): mixed
    {
        $params = implode(',', $params);

        throw new BadRequestException("Method with params [$params] not found");
    }

    public static function update(string $id): mixed
    {
        throw new BadRequestException("Method not found by $id");
    }

    public static function delete(string $id, bool $cascade = false): mixed
    {
        throw new BadRequestException("Method not found by $id");
    }

    protected static function makeIdentifierPath(string $path, array $args): string
    {
        return CamundaRepository::makeIdentifierPath($path, $args);
    }
}
