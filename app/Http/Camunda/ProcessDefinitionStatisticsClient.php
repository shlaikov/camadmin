<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use Symfony\Component\Routing\Annotation\Route;

class ProcessDefinitionStatisticsClient extends CamundaClient
{
    #[Route("/process-definition/statistics", method: "GET")]
    public static function index(array $params = []): array
    {
        return self::make()
            ->get('/process-definition/statistics', [
                ...request()->all(),
                ...$params
            ])->json();
    }
}
