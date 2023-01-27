<?php

namespace Tests\Unit;

use Mockery;
use App\Enums\DiagramEnum;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    protected $projectRepository;

    /**
     * Define test setup.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $project = Mockery::mock('App\Models\Project');
        $this->projectRepository = new \App\Repository\Eloquent\ProjectRepository($project);
    }

    public function test_get_diagram_types(): void
    {
        $diagrams = DiagramEnum::getValues();
        $projectDiagramTypes = $this->projectRepository->getDiagramTypes();

        $this->assertEquals(count($diagrams), count($projectDiagramTypes));
    }
}
