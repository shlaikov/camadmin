<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use App\Models\Instance;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'instances' => Instance::all(),
            'recent_work' => Diagram::orderBy('updated_at', 'DESC')
                ->limit(4)->get()
        ]);
    }
}
