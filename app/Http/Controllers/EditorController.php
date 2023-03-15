<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use App\Repository\Eloquent\ProjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Storage;

class EditorController extends Controller
{
    public function __construct(
        public ProjectRepository $projectRepository
    ) {
    }

    public function show($uuid): InertiaResponse
    {
        return Inertia::render('Diagram/Editor', [
            'diagram' => Diagram::where('uuid', $uuid)->first(),
        ]);
    }

    public function deploy($uuid, Request $request): JsonResponse
    {
        return $this->projectRepository->deploy($uuid, $request);
    }

    public function file($uuid, $extension): Response
    {
        return $this->getFile($uuid, $extension)
            ->header('Content-type', 'text/xml');
    }

    public function preview($uuid): Response
    {
        return $this->getFile($uuid, 'svg')
            ->header('Content-type', 'image/svg+xml');
    }

    private function getFile($uuid, $format)
    {
        $team = request()->user()->currentTeam;

        return response(Storage::cloud()->get("$team->id/$uuid.$format"));
    }
}
