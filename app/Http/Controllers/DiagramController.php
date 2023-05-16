<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\DiagramEnum;
use App\Models\Diagram;
use App\Repository\Eloquent\DiagramRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DiagramController extends Controller
{
    public function __construct(
        public DiagramRepository $diagramRepository
    ) {
    }

    public function index(): InertiaResponse
    {
        $teamId = request()->user()->currentTeam->id;
        $paginator = Diagram::where('team_id', $teamId)
            ->orderBy('updated_at', 'DESC')
            ->paginate(8);

        return Inertia::render('Diagrams', [
            'diagrams' => $paginator
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Diagram/Create', [
            'diagram_types' => $this->diagramRepository->getDiagramTypes()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $type = new DiagramEnum($request->type);

        $diagram = Diagram::create([
            'name' => $request->name,
            'team_id' => $user->currentTeam->id,
            'type' => $type->getValue(),
            'uuid' => (string) Str::uuid(),
        ]);

        return redirect()->route('editor', [
            'type' => $type->getValue(),
            'uuid' => $diagram->uuid,
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        return $this->diagramRepository->importFile($request);
    }
}
