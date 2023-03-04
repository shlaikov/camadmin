<?php

namespace App\Http\Controllers;

use App\Repository\Services\CamundaRepository;
use Illuminate\Http\JsonResponse;

class CamundaController extends Controller
{
    public function __construct(
        public CamundaRepository $camundaRepository
    ) {
    }

    public function __invoke($id, $requestString = '/'): JsonResponse
    {
        $execution = $this->camundaRepository->execute($id, $requestString);

        return response()->json($execution);
    }
}
