<?php

namespace App\Http\Camunda;

use App\Data\Camunda\Deployment;
use App\Exceptions\ObjectNotFoundException;
use App\Exceptions\ParseException;
use Symfony\Component\Routing\Annotation\Route;

class DeploymentClient extends CamundaClient
{
    #[Route("/deployment", method: "GET")]
    public static function index(array $params = []): array
    {
        $params = [...request()->all(), ...$params];

        $response = self::make()->get('deployment', $params);
        $result = [];

        foreach ($response->json() as $data) {
            $result[] = Deployment::from($data);
        }

        return $result;
    }

    #[Route("/deployment", method: "POST")]
    public static function create(array $params = []): Deployment
    {
        $bpmnFiles = (array) $params['bpmnFiles'];
        $multipart = [
            ['name' => 'deployment-name', 'contents' => request()->name ?? $params['name']],
            ['name' => 'deployment-source', 'contents' => sprintf('%s (%s)', config('app.name'), config('app.env'))],
            ['name' => 'enable-duplicate-filtering', 'contents' => 'true'],
        ];

        if (config('services.camunda.tenant_id')) {
            $multipart[] = [
                'name' => 'tenant-id',
                'contents' => config('services.camunda.tenant_id'),
            ];
        }

        $request = self::make()->asMultipart();
        foreach ($bpmnFiles as $bpmn) {
            $filename = pathinfo($bpmn)['basename'];
            $request->attach($filename, (string)file_get_contents($bpmn), $filename);
        }

        $response = $request->post('deployment/create', $multipart);

        if ($response->status() === 400) {
            throw new ParseException($response->json('message'));
        }

        return Deployment::from($response->json());
    }

    #[Route("/deployment/{identifier}", method: "GET")]
    public static function find(string $id): Deployment
    {
        $response = self::make()->get("deployment/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return Deployment::from($response->json());
    }

    #[Route("/deployment/{identifier}", method: "DELETE")]
    public static function delete(string $id, bool $cascade = false): bool
    {
        $cascadeFlag = $cascade ? 'cascade=true' : '';
        $response = self::make()->delete("deployment/{$id}?" . $cascadeFlag);

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->status() === 204;
    }

    public static function truncate(bool $cascade = false): void
    {
        $deployments = self::index();

        foreach ($deployments as $deployment) {
            self::delete($deployment->id, $cascade);
        }
    }
}
