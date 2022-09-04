<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Storage;

class EditorController extends Controller
{
    public function show($uuid)
    {
        return Inertia::render('Process/Editor', [
            'process' => Process::where('uuid', $uuid)->first(),
        ]);
    }

    public function deploy($uuid, Request $request)
    {
        $team = $request->user()->currentTeam;

        if (! $process = Process::where(['uuid' => $uuid, 'team_id' => $team->id])->first()) {
            return response()->json(['status' => 'error'], 401);
        }

        $path = "$team->id/$uuid";
        Storage::cloud()->put("$path.bpmn", $request->xml);
        Storage::cloud()->put("$path.svg", $request->svg);

        $process->url = Storage::cloud()->url("$path.bpmn");
        $process->preview = Storage::cloud()->url("$path.svg");
        $process->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function file($uuid): Response
    {
        return $this->getFile($uuid, 'bpmn')
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
