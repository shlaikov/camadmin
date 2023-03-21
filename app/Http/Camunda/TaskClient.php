<?php

namespace App\Http\Camunda;

use App\Data\Camunda\Task;
use App\Data\Camunda\Variable;
use App\Exceptions\CamundaException;
use App\Exceptions\ObjectNotFoundException;

class TaskClient extends CamundaClient
{
    public static function find(string $id): Task
    {
        $response = self::make()->get("task/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return Task::from($response->json());
    }

    /**
     * @param  string $id
     *
     * @return Task[]
     */
    public static function getByProcessInstanceId(string $id): array
    {
        $response = self::make()->get("task?processInstanceId=$id");

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $task) {
                $data[] = Task::from($task);
            }
        }

        return $data;
    }

    public static function submit(string $id, array $variables): bool
    {
        $response = self::make()->post(
            "task/$id/submit-form",
            compact('variables')
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function submitAndReturnVariables(string $id, array $variables): array
    {
        $response = self::make()->post(
            "task/$id/submit-form",
            ['variables' => $variables, 'withVariablesInReturn' => true]
        );


        if ($response->successful()) {
            $data = [];

            foreach ($response->json() as $key => $variable) {
                $data[$key] = Variable::from(['name' => $key, ...$variable]);
            }

            return $data;
        }

        throw new CamundaException($response->body(), $response->status());
    }
}
