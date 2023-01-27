<?php

namespace Tests\Integration\Camunda;

use App\Repository\Reader\BpmnReader;
use Tests\Integration\Camunda\TestCase;

class BpmnTest extends TestCase
{
    public function test_parse_form_definition(): void
    {
        $file = __DIR__ . '/../../../resources/diagrams/simple-interview.bpmn';
        $reader = new BpmnReader($file);
        $forms = $reader->getForms();

        $this->assertNotEmpty($forms);
    }

    public function test_parse_empty_form_definition(): void
    {
        $file = __DIR__ . '/../../../resources/diagrams/sample.bpmn';
        $reader = new BpmnReader($file);
        $forms = $reader->getForms();

        $this->assertEmpty($forms);
    }
}
