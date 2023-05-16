<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use App\Repository\Eloquent\DiagramRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Storage;

class EditorController extends Controller
{
    public function __construct(
        public DiagramRepository $diagramRepository
    ) {
    }

    public function show(string $uuid): InertiaResponse
    {
        return Inertia::render('Diagram/Editor', [
            'diagram' => Diagram::where('uuid', $uuid)->first(),
        ]);
    }

    public function deploy(string $uuid, Request $request): JsonResponse
    {
        return $this->diagramRepository->deploy($uuid, $request);
    }

    public function file(string $uuid, string $extension): Response
    {
        return $this->getFile($uuid, $extension)
            ->header('Content-type', 'text/xml');
    }

    public function preview(string $uuid): Response
    {
        return $this->getFile($uuid, 'svg')
            ->header('Content-type', 'image/svg+xml');
    }

    private function getFile(string $uuid, string $format): Response
    {
        $team = request()->user()->currentTeam;

        return response(Storage::cloud()->get("$team->id/$uuid.$format"));
    }
}
