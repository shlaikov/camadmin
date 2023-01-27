<?php

namespace Tests\Integration\Camunda;

use App\Data\Camunda\TaskHistory;
use App\Data\Camunda\Variable;
use App\Exceptions\CamundaException;
use App\Exceptions\ObjectNotFoundException;
use App\Http\Camunda\ProcessDefinitionClient;
use App\Http\Camunda\TaskClient;
use App\Http\Camunda\TaskHistoryClient;
use Tests\Integration\Camunda\TestCase;

class TaskTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->deploySampleBpmn();
    }

    public function test_find_by_id()
    {
        $variables = ['title' => ['value' => 'Foo', 'type' => 'string']];
        $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables);
        $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

        foreach ($tasks as $task) {
            $tastObject = TaskClient::find($task->id);
            $this->assertEquals($task->id, $tastObject->id);
        }
    }

    public function test_handle_invalid_id()
    {
        $this->expectException(ObjectNotFoundException::class);
        TaskClient::find('invalid-id');
    }

    public function test_get_completed_task()
    {
        $variables = ['title' => ['value' => 'Foo', 'type' => 'string']];
        $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables);
        $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

        foreach ($tasks as $task) {
            TaskClient::submit(
                $task->id,
                ['email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string']]
            );
            $completedTask = TaskHistoryClient::find($task->id);
            $this->assertInstanceOf(TaskHistory::class, $completedTask);
        }
    }

    public function test_get_completed_task_with_invalid_id()
    {
        $this->expectException(ObjectNotFoundException::class);
        TaskHistoryClient::find('invalid-task-id');
    }

    public function test_get_completed_tasks()
    {
        $variables = ['title' => ['value' => 'Foo', 'type' => 'string']];
        $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables);
        $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

        foreach ($tasks as $task) {
            TaskClient::submit(
                $task->id,
                ['email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string']]
            );
        }

        $completedTasks = TaskHistoryClient::getByProcessInstanceId($processInstance->id);
        $this->assertCount(1, $completedTasks);
    }

    public function test_submit_form()
    {
        $variables = ['title' => ['value' => 'Foo', 'type' => 'string']];
        $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables);
        $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

        foreach ($tasks as $task) {
            $submitted = TaskClient::submit($task->id, ['email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string']]);
            $this->assertTrue($submitted);
        }
    }

    public function test_submit_form_and_return_variables()
    {
        $variables = ['title' => ['value' => 'Foo', 'type' => 'string']];
        $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables);
        $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

        foreach ($tasks as $task) {
            $variables = TaskClient::submitAndReturnVariables($task->id, ['email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string']]);

            // 2 variables: 1 from start form (title), 1 from task form (email)
            $this->assertCount(2, $variables);
            $this->assertInstanceOf(Variable::class, $variables['title']);
        }
    }

    public function test_submit_form_with_invalid_id()
    {
        $this->expectException(CamundaException::class);
        TaskClient::submit('invalid-id', ['foo' => 'bar']);
    }

    public function test_submit_form_and_return_variables_with_invalid_id()
    {
        $this->expectException(CamundaException::class);
        TaskClient::submitAndReturnVariables('invalid-id', ['foo' => 'bar']);
    }

    protected function tearDown(): void
    {
        $this->truncateDeployment();
    }
}
