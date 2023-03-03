<?php

declare(strict_types=1);

namespace App\Repository\Services;

use App\Exceptions\InvalidArgumentException;
use App\Http\Camunda\CamundaClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Http\Request as HttpRequest;
use ReflectionClass;
use ReflectionMethod;

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
            if (!$this->isInvokedByAttributes($class)) {
                continue;
            }

            $method = $this->getMethodOfClass($class);
            $args = $this->hasIndetifier() ? $this->getIndetifier() : request()->all();

            return $method->invoke($class, $args);
        }

        throw new BadRequestException("There is no method by $this->requestString path");
    }

    protected function getMethodOfClass($class): ReflectionMethod
    {
        switch (request()->method()) {
            case HttpRequest::METHOD_GET:
                return $class->getMethod($this->hasIndetifier() ? 'find' : 'index');
            case HttpRequest::METHOD_POST:
                return $class->getMethod('create');
            case HttpRequest::METHOD_PUT:
                return $class->getMethod('update');
            case HttpRequest::METHOD_DELETE:
                return  $class->getMethod('delete');
            default:
                throw new BadRequestException("This HTTP method doesn't supported");
        }
    }

    protected function isInvokedByAttributes($class): bool
    {
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() !== Route::class) {
                return false;
            }

            $args = $attribute->getArguments();

            if (!in_array(request()->method(), $args['methods'])) {
                continue;
            }

            if ($this->requestString === $args[0]) {
                return true;
            }

            if ($this->hasIndetifier()) {
                return '/' . $this->requestPaths[0] === $args[0];
            }
        }

        return false;
    }

    protected function hasIndetifier(): bool
    {
        return count($this->requestPaths) === 2;
    }

    protected function getIndetifier(): ?string
    {
        return $this->hasIndetifier() ? end($this->requestPaths) : null;
    }

    protected static function getCamundaClasses(): array
    {
        return array_filter(array_map(function ($file) {
            $class = self::CAMUNDA_NAMESPACE . '\\' . str_replace('.php', '', $file);

            if (class_exists($class) && is_subclass_of(new $class(), self::CLIENT_CLASS)) {
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
