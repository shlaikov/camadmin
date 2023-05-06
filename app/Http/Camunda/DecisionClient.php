<?php

declare(strict_types=1);

namespace App\Http\Camunda;

use App\Data\Camunda\Decision;
use Symfony\Component\Routing\Annotation\Route;

class DecisionClient extends CamundaClient
{
    #[Route("/decision-definition", method: "GET")]
    public static function index(array $params = []): array
    {
        $decisions = [];
        $data = [...request()->all(), ...$params];

        foreach (self::make()->get('decision-definition', $data)->json() as $res) {
            $decisions[] = Decision::from($res);
        }

        return $decisions;
    }
}
