<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $teamId = request()->user()->currentTeam->id;
        $paginator = Process::where('team_id', $teamId)
            ->paginate(6);

        return Inertia::render('Projects', [
            'processes' => $paginator,
        ]);
    }

    public function create()
    {
        return Inertia::render('Process/Create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $process = Process::create([
            'name' => $request->name,
            'team_id' => $user->currentTeam->id,
            'uuid' => (string) Str::uuid(),
        ]);

        return redirect()->route('editor', [
            'uuid' => $process->uuid,
        ]);
    }

    public function import(Request $request)
    {
        $user = $request->user();

        $file = $request->file;
        $team = $user->currentTeam;

        if (! $file) {
            return response()->json(['status' => 'BAD_REQUEST'], 400);
        }

        $uuid = (string) Str::uuid();

        switch ($file->getClientOriginalExtension()) {
            case 'bpmn':
                DB::beginTransaction();

                $process = Process::create([
                    'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'team_id' => $user->currentTeam->id,
                    'uuid' => $uuid,
                ]);

                try {
                    Storage::cloud()->put("$team->id/$uuid.bpmn", $file->get());

                    $process->url = Storage::cloud()->url("$team->id/$uuid.bpmn");
                    $process->save();

                    DB::commit();

                    return response()->json(['status' => 'OK'], 200);
                } catch (\Throwable $e) {
                    DB::rollBack();

                    return response()->json([
                        'status' => 'ERROR',
                        'message' => $e->getMessage(),
                    ], 500);
                }
                break;
        }

        return response()->json(['status' => 'BAD_REQUEST'], 400);
    }
}
