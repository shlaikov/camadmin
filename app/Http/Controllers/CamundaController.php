<?php

namespace App\Http\Controllers;

use App\Repository\Services\CamundaRepository;
use Illuminate\Http\JsonResponse;
use Inertia\Response as InertiaResponse;

class CamundaController extends Controller
{
    public function __construct(
        public CamundaRepository $camundaRepository
    ) {
    }

    public function __invoke(string $id, string $requestString = '/'): JsonResponse|InertiaResponse
    {
        $execution = $this->camundaRepository->execute($id, $requestString);

        if ($execution instanceof InertiaResponse) {
            return $execution;
        }

        return response()->json($execution);
    }
}
