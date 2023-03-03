<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Data\Camunda\ProcessInstanceHistory;
use App\Data\Camunda\Variable;
use App\Exceptions\ObjectNotFoundException;

class ProcessInstanceHistoryClient extends CamundaClient
{
    /**
     * @param  array  $params
     *
     * @return array|ProcessInstanceHistory[]
     */
    public static function get(array $params = []): array
    {
        $instances = [];
        foreach (self::make()->get('history/process-instance', $params)->json() as $res) {
            $instances[] = ProcessInstanceHistory::from($res);
        }

        return $instances;
    }

    /**
     * @param  string  $id
     *
     * @return \App\Data\Camunda\ProcessInstanceHistory
     */
    public static function find(string $id): ProcessInstanceHistory
    {
        $response = self::make()->get("history/process-instance/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return ProcessInstanceHistory::from($response->json());
    }

    public static function variables(string $id): array
    {
        $variables = self::make()->get("history/variable-instance", ['processInstanceId' => $id])->json();

        return collect($variables)->mapWithKeys(
            fn ($data) => [$data['name'] => Variable::from([...$data])]
        )->toArray();
    }
}
