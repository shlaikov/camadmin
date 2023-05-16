<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Dashboard', [
            'recent_work' => Diagram::orderBy('updated_at', 'DESC')
                ->limit(4)->get()
        ]);
    }

    public function teamMembers(Request $request): InertiaResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Team $members */
        $members = $user->currentTeam->allUsers();

        return Inertia::render('TeamMembers', [
            'team_members' => $members->toArray()
        ]);
    }
}
