<?php

namespace Tests\Unit;

use Mockery;
use App\Enums\DiagramEnum;
use Tests\TestCase;

class DiagramTest extends TestCase
{
    protected $diagramRepository;

    /**
     * Define test setup.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $diagram = Mockery::mock('App\Models\Diagram');
        $this->diagramRepository = new \App\Repository\Eloquent\DiagramRepository($diagram);
    }

    public function test_get_diagram_types(): void
    {
        $diagrams = DiagramEnum::getValues();
        $diagramTypes = $this->diagramRepository->getDiagramTypes();

        $this->assertEquals(count($diagrams), count($diagramTypes));
    }
}
