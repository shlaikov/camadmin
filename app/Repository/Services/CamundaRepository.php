<?php

declare(strict_types=1);

namespace App\Repository\Services;

use App\Exceptions\InvalidArgumentException;
use App\Http\Camunda\CamundaClient;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CamundaRepository
{
    const CAMUNDA_NAMESPACE = "App\Http\Camunda";

    const CLIENT_CLASS = CamundaClient::class;

    protected string $instanceId;

    protected string $requestString;
    protected array $requestPaths;

    public function execute($id, $requestString = '/'): array
    {
        $this->instanceId = $id;

        $requestString = strtok($requestString, '?');
        $this->requestPaths = explode('/', $requestString);
        $this->requestString = "/$requestString";

        if (count($this->requestPaths) > 2) {
            throw new BadRequestException("There is no method by $this->requestString path");
        }

        return $this->invokeRequest();
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

        return str_replace('{identifier}', $identifier, $path);
    }

    protected function invokeRequest(): array
    {
        foreach ($this->getCamundaClasses() as $class) {
            if ($method = $this->getRequestByClass($class)) {
                return $method->invoke($class, request()->all());
            }
        }

        throw new BadRequestException("There is no method by $this->requestString path");
    }

    protected function getRequestByClass($class): ?ReflectionMethod
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
                return $method;
            }
        }

        return null;
    }

    protected function getIndetifier(): ?string
    {
        return end($this->requestPaths);
    }

    protected static function getCamundaClasses(): array
    {
        return array_filter(array_map(function ($file) {
            $class = self::CAMUNDA_NAMESPACE . '\\' . str_replace('.php', '', $file);

            if (class_exists($class) && is_subclass_of((string) $class, self::CLIENT_CLASS)) {
                return new ReflectionClass($class);
            }
        }, scandir(self::getNamespaceDirectory(self::CAMUNDA_NAMESPACE))));
    }

    protected static function getDefinedNamespaces(): array
    {
        $composerJsonPath = base_path() . '/composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        return (array) $composerConfig->autoload->{'psr-4'};
    }

    protected static function getNamespaceDirectory($namespace): string|false
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
