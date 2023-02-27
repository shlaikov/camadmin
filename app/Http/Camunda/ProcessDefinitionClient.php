<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Data\Camunda\ProcessDefinition;
use App\Data\Camunda\ProcessInstance;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\ObjectNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/process-definition", methods: ["GET", "POST"])]
class ProcessDefinitionClient extends CamundaClient
{
    public static function index(Request $request): array
    {
        $processDefinition = [];

        foreach (self::make()->get('process-definition', $request->all())->json() as $res) {
            $processDefinition[] = ProcessDefinition::from($res);
        }

        return $processDefinition;
    }

    public static function find(Request $request): ProcessDefinition
    {
        if ($request->isXml()) {
            return self::xml($request);
        }

        $response = self::make()->get("process-definition/$request->id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return ProcessDefinition::from($response->json());
    }

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

    public static function xml($request): string
    {
        return self::make()->get("process-definition/$request->id/xml")->json('bpmn20Xml');
    }
}
