<?php

declare(strict_types=1);

namespace App\Repository\Services;

use App\Exceptions\InvalidArgumentException;
use App\Http\Camunda\CamundaClient;
use ReflectionClass;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CamundaService
{
    const CAMUNDA_NAMESPACE = "App\Http\Camunda";

    const CLIENT_CLASS = CamundaClient::class;
    const ROUTE_REPLACE_MASK = '{identifier}';

    const ROUTE_SIMPLE_TYPE = 'simple';
    const ROUTE_WITH_ID_TYPE = 'with_identifier';
    const ROUTE_TYPES = [
        self::ROUTE_SIMPLE_TYPE, self::ROUTE_WITH_ID_TYPE
    ];

    const RESERVED_WORDS = [
        'id', 'key', 'tenant-id', 'group', 'members',
        'count', 'xml', 'statistics', 'diagram'
    ];

    protected string $instanceId;

    protected string $requestString;
    protected array $requestPaths = [];
    protected array $requestCombitations;

    public function execute(string $id, string $requestString = '/'): mixed
    {
        $this->instanceId = $id;

        if (is_string($requestString = strtok($requestString, '?'))) {
            $this->requestPaths = explode('/', $requestString);
            $this->requestString = "/$requestString";
            $this->requestCombitations = $this->getRouteCombinations();

            return $this->invokeRequest();
        }

        throw new BadRequestException("There is no method by $requestString path");
    }

    public static function makeIdentifierPath(string $path, array $args): string
    {
        if (count($args) === 1 && isset($args[0])) {
            $args['id'] = $args[0];
        }

        $args += ['id' => false, 'key' => false, 'tenantId' => false];
        $identifier = $args['id'];

        if ($args['key']) {
            $identifier = 'key/' . $args['key'];
            if ($args['tenantId']) {
                $identifier .= '/tenant-id/' . $args['tenantId'];
            }
        }

        if (!$identifier) {
            throw new InvalidArgumentException('');
        }

        return str_replace(self::ROUTE_REPLACE_MASK, $identifier, $path);
    }

    protected function invokeRequest(): mixed
    {
        foreach ($this->getCamundaClasses() as $class) {
            if ($method = $this->getRequestByClass($class)) {
                $reflectMethod = $method['method'];

                if ($method['type'] === self::ROUTE_WITH_ID_TYPE) {
                    return $reflectMethod->invoke($class, id: $method['id']);
                }

                return $reflectMethod->invoke($class, request()->all());
            }
        }

        throw new BadRequestException("There is no method by $this->requestString path");
    }

    protected function getRequestByClass(ReflectionClass $class): ?array
    {
        foreach ($class->getMethods() as $method) {
            $attribute = $method->getAttributes()[0] ?? null;

            if (!isset($attribute) || $attribute->getName() !== Route::class) {
                continue;
            }

            $args = $attribute->getArguments();

            if (request()->method() !== $args['method']) {
                continue;
            }

            if ($this->requestString === $args[0]) {
                return ['type' => self::ROUTE_SIMPLE_TYPE, 'method' => $method];
            }

            if (in_array($args[0], $this->requestCombitations)) {
                $key = (int)array_search($args[0], $this->requestCombitations);

                return [
                    'type' => self::ROUTE_WITH_ID_TYPE,
                    'method' => $method,
                    'id' => $this->requestPaths[$key + 1]
                ];
            }
        }

        return null;
    }

    protected function getRouteCombinations(): array
    {
        $combinations = [];

        foreach ($this->requestPaths as $key => $path) {
            if ($key === 0 || in_array($path, self::RESERVED_WORDS)) {
                continue;
            }

            $paths = $this->requestPaths;
            $paths[$key] = self::ROUTE_REPLACE_MASK;

            $combinations[] = '/' . implode('/', $paths);
        }

        return $combinations;
    }

    /**
     * @return array<int, mixed>
     */
    protected static function getCamundaClasses(): array
    {
        $directory = (string)self::getNamespaceDirectory(self::CAMUNDA_NAMESPACE);

        if (!$files = scandir($directory)) {
            return [];
        }

        return array_filter(array_map(function ($file) {
            if (!$file) {
                return;
            }

            $class = self::CAMUNDA_NAMESPACE . '\\' . str_replace('.php', '', $file);

            if (class_exists($class) && is_subclass_of((string) $class, self::CLIENT_CLASS)) {
                return new ReflectionClass($class);
            }
        }, $files));
    }

    protected static function getDefinedNamespaces(): array
    {
        $composerJsonPath = base_path() . '/composer.json';
        $composerConfig = json_decode((string)file_get_contents($composerJsonPath));

        return (array) $composerConfig->autoload->{'psr-4'};
    }

    protected static function getNamespaceDirectory(string $namespace): string|false
    {
        $composerNamespaces = self::getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(base_path() . '/' . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }
}
