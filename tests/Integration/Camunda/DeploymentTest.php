<?php

namespace App\Integration\Camunda;

use App\Data\Camunda\Deployment;
use App\Exceptions\ObjectNotFoundException;
use App\Exceptions\ParseException;
use App\Http\Camunda\DeploymentClient;
use Tests\Integration\Camunda\TestCase;

class DeploymentTest extends TestCase
{
    protected function tearDown(): void
    {
        DeploymentClient::truncate();
    }

    public function test_deploy_bpmn(): void
    {
        $files = __DIR__ . '/../../../resources/diagrams/sample.bpmn';
        $deployment = DeploymentClient::create([
            'name' => 'test',
            'bpmnFiles' => $files
        ]);

        $this->assertEquals('test', $deployment->name);
    }

    public function test_deploy_bpmn_with_tenant_id(): void
    {
        config()->set('services.camunda.tenant_id', 'sample-tenant');

        $files = __DIR__ . '/../../../resources/diagrams/sample.bpmn';
        $deployment = DeploymentClient::create([
            'name' => 'test',
            'bpmnFiles' => $files
        ]);

        $this->assertEquals('test', $deployment->name);
    }

    public function test_deploy_multiple_bpmn(): void
    {
        $files = [
            __DIR__ . '/../../../resources/diagrams/sample.bpmn',
            __DIR__ . '/../../../resources/diagrams/sample2.bpmn',
        ];
        $deployment = DeploymentClient::create([
            'name' => 'test',
            'bpmnFiles' => $files
        ]);

        $this->assertEquals('test', $deployment->name);
    }

    public function test_deploy_invalid_bpmn(): void
    {
        $this->expectException(ParseException::class);

        $files = __DIR__ . '/../../../resources/diagrams/invalid.bpmn';
        DeploymentClient::create([
            'name' => 'test',
            'bpmnFiles' => $files
        ]);
    }

    public function test_get_deployment_by_id(): void
    {
        $files = __DIR__ . '/../../../resources/diagrams/sample.bpmn';
        $deployment1 = DeploymentClient::create([
            'name' => 'test',
            'bpmnFiles' => $files
        ]);

        $deployment2 = DeploymentClient::find($deployment1->id);
        $this->assertEquals($deployment1->id, $deployment2->id);
    }

    public function test_get_deployment_by_invalid_id(): void
    {
        $this->expectException(ObjectNotFoundException::class);

        DeploymentClient::find('some-invalid-id');
    }

    public function test_get_list_deployment(): void
    {
        $this->truncateDeployment();

        $intitialCount = count(DeploymentClient::index());

        DeploymentClient::create([
            'name' => 'deployment1',
            'bpmnFiles' => __DIR__ . '/../../../resources/diagrams/sample.bpmn'
        ]);
        DeploymentClient::create([
            'name' => 'deployment2',
            'bpmnFiles' => __DIR__ . '/../../../resources/diagrams/sample2.bpmn'
        ]);

        $deployments = DeploymentClient::index();
        $this->assertCount($intitialCount + 2, $deployments);
    }

    public function test_delete_deployment(): void
    {
        $deployment = DeploymentClient::create([
            'name' => 'deployment1',
            'bpmnFiles' => __DIR__ . '/../../../resources/diagrams/sample.bpmn'
        ]);
        $deleted = DeploymentClient::delete($deployment->id);

        $this->assertTrue($deleted);
    }

    public function test_delete_invalid_deployment(): void
    {
        $this->expectException(ObjectNotFoundException::class);

        $deployment = Deployment::from([
            'id' => 'invalid-id',
            'name' => 'test',
            'deploymentTime' => now(),
            'processDefinitions' => []
        ]);

        DeploymentClient::delete($deployment->id);
    }
}
