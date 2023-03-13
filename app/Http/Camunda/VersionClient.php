<?php

namespace App\Http\Camunda;

use App\Exceptions\ObjectNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

class VersionClient extends CamundaClient
{
    #[Route("/version", method: "GET")]
    public static function index(array $params = []): array
    {
        $response = self::make()->get('version');

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->json();
    }
}
