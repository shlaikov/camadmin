<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Inertia\Inertia;

class EditorController extends Controller
{
    public function show($uuid)
    {
        return Inertia::render('Process/Editor', [
            'process' => Process::where('uuid', $uuid)->first(),
        ]);
    }
}
