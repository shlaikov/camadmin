<?php

namespace App\Http\Controllers;

use App\Repository\Services\CamundaRepository;

class CamundaController extends Controller
{
    public function __construct(
        public CamundaRepository $camundaRepository
    ) {
    }

    public function __invoke($id, $requestString = '/')
    {
        $execution = $this->camundaRepository->execute($id, $requestString);

        return response()->json($execution);
    }
}
