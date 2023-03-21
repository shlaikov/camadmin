<?php

declare(strict_types=1);

namespace App\Repository\Reader;

class BpmnReader
{
    private \SimpleXMLElement $xml;

    public function __construct(string $file)
    {
        if ($this->xml = new \SimpleXMLElement((string)file_get_contents($file))) {
            $this->xml->registerXPathNamespace('bpmn', 'http://www.omg.org/spec/BPMN/20100524/MODEL');
            $this->xml->registerXPathNamespace('camunda', 'http://camunda.org/schema/1.0/bpmn');
        }
    }

    public function getForms(): ?array
    {
        if (!$this->xml) {
            throw new \ErrorException("SimpleXMLElement on server doesn't work");
        }

        $nodes = $this->xml->xpath('//bpmn:startEvent') + $this->xml->xpath('//bpmn:userTask');

        $forms = [];
        foreach ($nodes as $node) {
            try {
                $fields = $node->xpath('bpmn:extensionElements/camunda:formData/camunda:formField');
                $formFields = [];

                foreach ($fields as $field) {
                    /** @phpstan-ignore-next-line */
                    $properties = collect($field->xpath('camunda:properties/camunda:property'))
                        ->transform(
                            fn ($node) => [(string) $node->attributes()->id => (string) $node->attributes()->value]
                        )
                        ->toArray();

                    $formFields[] = [
                        'id' => (string) $field->attributes()->id,
                        'label' => (string) $field->attributes()->label,
                        'type' => (string) $field->attributes()->type,
                        'properties' => $properties,
                    ];
                }

                $form = [
                    'id' => (string) $node->attributes()->id,
                    'label' => (string) $node->attributes()->name,
                    'fields' => $formFields,
                ];
                $forms[] = $form;
            } catch (\ErrorException $exception) {
            }
        }

        return $forms;
    }
}
