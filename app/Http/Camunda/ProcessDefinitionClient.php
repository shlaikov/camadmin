<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Data\Camunda\ProcessDefinition;
use App\Data\Camunda\ProcessInstance;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\ObjectNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ProcessDefinitionClient extends CamundaClient
{
    #[Route("/process-definition", method: "GET")]
    public static function index(array $params = []): array
    {
        $processDefinition = [];
        $data = [...request()->all(), ...$params];

        foreach (self::make()->get('process-definition', $data)->json() as $res) {
            $processDefinition[] = ProcessDefinition::from($res);
        }

        return $processDefinition;
    }

    #[Route("/process-definition/count", method: "GET")]
    public static function count(): array
    {
        return self::make()->get('process-definition/count')->json();
    }

    #[Route("/process-definition/{identifier}", method: "GET")]
    public static function find(...$args): ProcessDefinition|string
    {
        if (request()->isXml()) {
            return (string) self::xml(id: $args['id']);
        }

        $response = self::make()->get(self::makeIdentifierPath('process-definition/{identifier}', $args));

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return ProcessDefinition::from($response->json());
    }

    #[Route("/process-definition", method: "POST")]
    public static function start(...$args): ProcessInstance
    {
        $variables = $args['variables'] ?? [];
        $businessKey = $args['businessKey'] ?? null;

        // At least one value must be set...
        if (empty($variables)) {
            throw new InvalidArgumentException('Cannot start process instance with empty variables');
        }

        $payload = [
            'variables' => $variables,
            'withVariablesInReturn' => true,
        ];

        if ($businessKey) {
            $payload['businessKey'] = $businessKey;
        }

        $path = self::makeIdentifierPath('process-definition/{identifier}/start', $args);
        $response = self::make()->post($path, $payload);

        if ($response->successful()) {
            return ProcessInstance::from($response->json());
        }

        throw new InvalidArgumentException($response->body());
    }

    #[Route("/process-definition/{identifier}/diagram", method: "GET")]
    public static function diagram(string $id): InertiaResponse
    {
        return Inertia::render('Diagram/Editor', [
            'diagram' => ProcessDefinitionClient::find($id),
        ]);
    }

    #[Route("/process-definition/{identifier}/xml", method: "GET")]
    public static function xml(...$args): string
    {
        $path = self::makeIdentifierPath(path: 'process-definition/{identifier}/xml', args: $args);

        return self::make()->get($path)->json('bpmn20Xml');
    }
}
