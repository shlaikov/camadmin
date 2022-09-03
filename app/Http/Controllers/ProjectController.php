<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $teamId = request()->user()->currentTeam->id;

        return Inertia::render('Projects', [
            'processes' => Process::where('team_id', $teamId)->paginate(),
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

        return redirect()->route('editor', ['uuid' => $process->uuid]);
    }
}
