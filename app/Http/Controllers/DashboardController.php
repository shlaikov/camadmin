<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DashboardController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Dashboard', [
            'recent_work' => Diagram::orderBy('updated_at', 'DESC')
                ->limit(4)->get()
        ]);
    }
}
