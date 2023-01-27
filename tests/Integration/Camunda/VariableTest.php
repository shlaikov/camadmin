<?php

namespace Tests\Integration\Camunda;

use App\Collections\VariableCollection;
use App\Http\Camunda\ProcessDefinitionClient;
use App\Http\Camunda\ProcessInstanceClient;
use Tests\Integration\Camunda\TestCase;

class VariableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->deploySampleBpmn();
    }

    public function test_automatically_format_variables()
    {
        $rawVariables = [
            'title' => 'Some String',
            'isActive' => true,
            'tags' => ['satu' => 'foo', 'dua', 'tiga'],
            'keys' => collect([1, 2]),
        ];
        $variables = new VariableCollection($rawVariables);
        $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables->toArray());
        $camundaVariables = ProcessInstanceClient::variables($processInstance->id);

        $this->assertEquals('String', $camundaVariables['title']->type);
        $this->assertEquals('Boolean', $camundaVariables['isActive']->type);
        $this->assertEquals('Json', $camundaVariables['tags']->type);
        $this->assertEquals('Object', $camundaVariables['keys']->type);
    }

    protected function tearDown(): void
    {
        $this->truncateDeployment();
    }
}
