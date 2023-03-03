<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Collections\VariableCollection;
use App\Data\Camunda\ProcessInstance;
use App\Data\Camunda\Variable;
use App\Exceptions\ObjectNotFoundException;

class ProcessInstanceClient extends CamundaClient
{
    public static function index(array $params = []): array
    {
        $instances = [];
        $data = [...request()->all(), ...$params];

        foreach (self::make()->get('process-instance', $data)->json() as $res) {
            $instances[] = ProcessInstance::from($res);
        }

        return $instances;
    }

    protected static function getByVariables(array $variables = []): array
    {
        $instances = [];

        if (count($variables) > 0) {
            $reqstr = 'process-instance?variables=';

            foreach ($variables as $variable) {
                $reqstr .= $variable['name'] . $variable['operator'] . $variable['value'] . ',';
            }
        } else {
            $reqstr = 'process-instance';
        }

        foreach (self::make()->get($reqstr)->json() as $res) {
            $instances[] = ProcessInstance::from($res);
        }

        return $instances;
    }

    public static function find(string $id): ProcessInstance
    {
        $response = self::make()->get("process-instance/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return ProcessInstance::from($response->json());
    }

    public static function variables(string $id): VariableCollection
    {
        $options = ['deserializeValues' => false];
        $variables = self::make()->get("process-instance/$id/variables", $options)->json();

        return (new VariableCollection($variables))->mapWithKeys(
            fn ($data, $name) => [
                $name => Variable::from(
                    [
                        'name' => $name,
                        ...$data
                    ]
                ),
            ]
        );
    }

    public static function delete(string $id, bool $cascade = false): bool
    {
        return self::make()->delete("process-instance/$id")->status() === 204;
    }
}
