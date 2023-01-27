<?php

namespace App\Repository\Eloquent;

use App\Enums\DiagramEnum;
use App\Models\Project;
use App\Repository\BaseRepository;
use App\Models\Diagram;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Storage;

class ProjectRepository extends BaseRepository
{
    /**
     * ProjectRepository constructor.
     *
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getDiagramTypes(): array
    {
        $diagrams = [];

        foreach (DiagramEnum::getValues() as $diagram) {
            $diagrams[] = [
                'value' => $diagram,
                'text' => __("diagrams.$diagram")
            ];
        }

        return $diagrams;
    }

    public function deploy($uuid, Request $request): JsonResponse
    {
        $request->validate([
            'xml' => 'required|string',
            'svg' => 'required|string',
        ]);

        $team = $request->user()->currentTeam;

        if (!$diagram = Diagram::where(['uuid' => $uuid, 'team_id' => $team->id])->first()) {
            return response()->json(['status' => 'error'], 401);
        }

        $path = "$team->id/$uuid";
        Storage::cloud()->put("$path.$diagram->type", $request->xml);
        Storage::cloud()->put("$path.svg", $request->svg);

        $diagram->url = Storage::cloud()->url("$path.$diagram->type");
        $diagram->preview = Storage::cloud()->url("$path.svg");
        $diagram->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function importFile(Request $request): JsonResponse
    {
        if (!$file = $request->file) {
            return response()->json(['status' => 'BAD_REQUEST'], 400);
        }

        $user = $request->user();
        $uuid = (string) Str::uuid();
        $team = $user->currentTeam;

        DB::beginTransaction();

        $diagram = Diagram::create([
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'team_id' => $user->currentTeam->id,
            'uuid' => $uuid,
        ]);

        try {
            $type = $file->getClientOriginalExtension();
            $fileName = "$team->id/$uuid.$type";
            Storage::cloud()->put($fileName, $file->get());

            $diagram->url = Storage::cloud()->url($fileName);
            $diagram->save();

            DB::commit();

            return response()->json(['status' => 'OK'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
