<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Exceptions\ObjectNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/history", methods: ["GET"])]
class HistoryClient extends CamundaClient
{
    /**
     * @param  string  $id
     *
     * @return array
     */
    public static function find($id): array
    {
        $response = self::make()->get("history/$id", request()->except(['id']));

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->json();
    }
}
