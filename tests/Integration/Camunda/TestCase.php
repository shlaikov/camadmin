<?php

namespace Tests\Integration\Camunda;

use App\Data\Camunda\Deployment;
use App\Http\Camunda\DeploymentClient;
use Tests\TestCase as MainTestCase;

class TestCase extends MainTestCase
{
    protected $deployId;

    /**
     * Define test setup.
     *
     * @return void
     */
    protected function setUp(): void
    {
        if (!env('CAMUNDA_URL')) {
            $this->markTestSkipped('tests in this file are invactive for this server configuration!');
        }

        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.name', env('APP_NAME'));
        $app['config']->set('app.env', env('APP_ENV'));
    }

    protected function deploySampleBpmn(): Deployment
    {
        $files = [__DIR__ . '/../../../resources/diagrams/sample.bpmn'];

        $deploy = DeploymentClient::create([
            'name' => 'test',
            'bpmnFiles' => $files
        ]);

        $this->deployId = $deploy->id;

        return $deploy;
    }

    protected function truncateDeployment(): void
    {
        if (!$this->deployId) {
            return;
        }

        if (DeploymentClient::delete($this->deployId, true)) {
            $this->deployId = null;
        }
    }
}
