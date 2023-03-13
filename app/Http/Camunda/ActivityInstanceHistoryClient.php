<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Exceptions\ObjectNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

class ActivityInstanceHistoryClient extends CamundaClient
{
    #[Route("/history/activity-instance", method: "GET")]
    public static function index(array $params = []): array
    {
        $response = self::make()->get("history/activity-instance", $params);

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->json();
    }
}
