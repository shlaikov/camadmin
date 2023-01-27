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
        $deployment = DeploymentClient::create('test', $files);

        $this->assertEquals('test', $deployment->name);
    }

    public function test_deploy_bpmn_with_tenant_id(): void
    {
        config()->set('services.camunda.tenant_id', 'sample-tenant');

        $files = __DIR__ . '/../../../resources/diagrams/sample.bpmn';
        $deployment = DeploymentClient::create('test', $files);

        $this->assertEquals('test', $deployment->name);
    }

    public function test_deploy_multiple_bpmn(): void
    {
        $files = [
            __DIR__ . '/../../../resources/diagrams/sample.bpmn',
            __DIR__ . '/../../../resources/diagrams/sample2.bpmn',
        ];
        $deployment = DeploymentClient::create('test', $files);

        $this->assertEquals('test', $deployment->name);
    }

    public function test_deploy_invalid_bpmn(): void
    {
        $this->expectException(ParseException::class);

        $files = __DIR__ . '/../../../resources/diagrams/invalid.bpmn';
        DeploymentClient::create('test', $files);
    }

    public function test_get_deployment_by_id(): void
    {
        $files = __DIR__ . '/../../../resources/diagrams/sample.bpmn';
        $deployment1 = DeploymentClient::create('test', $files);

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
        DeploymentClient::create('deployment1', __DIR__ . '/../../../resources/diagrams/sample.bpmn');
        DeploymentClient::create('deployment2', __DIR__ . '/../../../resources/diagrams/sample2.bpmn');

        $deployments = DeploymentClient::get();
        $this->assertCount(2, $deployments);
    }

    public function test_delete_deployment(): void
    {
        $deployment = DeploymentClient::create('deployment1', __DIR__ . '/../../../resources/diagrams/sample.bpmn');
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

    public function test_truncate_deployment(): void
    {
        DeploymentClient::create('deployment1', __DIR__ . '/../../../resources/diagrams/sample.bpmn');
        DeploymentClient::create('deployment2', __DIR__ . '/../../../resources/diagrams/sample2.bpmn');

        DeploymentClient::truncate();

        $deployments = DeploymentClient::get();
        $this->assertCount(0, $deployments);
    }
}
