<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Data\Camunda\Incident;
use Symfony\Component\Routing\Annotation\Route;

class IncedentClient extends CamundaClient
{
    #[Route("/incident", method: "GET")]
    public static function index(array $params = []): array
    {
        $incidents = [];
        $data = [...request()->all(), ...$params];

        foreach (self::make()->get('incident', $data)->json() as $res) {
            $incidents[] = Incident::from($res);
        }

        return $incidents;
    }
}
