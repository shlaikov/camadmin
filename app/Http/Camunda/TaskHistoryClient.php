<?php

namespace App\Http\Camunda;

use Illuminate\Support\Arr;
use App\Data\Camunda\TaskHistory;
use App\Exceptions\CamundaException;
use App\Exceptions\ObjectNotFoundException;

class TaskHistoryClient extends CamundaClient
{
    public static function find(string $id): TaskHistory
    {
        $response = self::make()->get("history/task?taskId=$id");

        if ($response->status() === 200) {
            if (empty($response->json())) {
                throw new ObjectNotFoundException(sprintf('Cannot find task history with ID = %s', $id));
            }

            return TaskHistory::from(Arr::first($response->json()));
        }

        throw new CamundaException($response->json('message'));
    }

    /**
     * @param  string  $processInstanceId
     *
     * @return TaskHistory[]
     */
    public static function getByProcessInstanceId(string $processInstanceId): array
    {
        $response = self::make()
            ->get(
                'history/task',
                [
                    'processInstanceId' => $processInstanceId,
                    'finished' => true,
                ]
            );

        if ($response->successful()) {
            $data = collect();

            foreach ($response->json() as $task) {
                $data->push(TaskHistory::from($task));
            }

            return $data->sortBy('endTime')->toArray();
        }

        return [];
    }
}
