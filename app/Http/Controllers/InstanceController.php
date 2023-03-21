<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\InstanceData;
use App\Models\Instance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class InstanceController extends Controller
{
    public function index(Instance $model): JsonResponse
    {
        return response()->json($model->all());
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Instance/Create');
    }

    public function store(Instance $model, InstanceData $data): RedirectResponse
    {
        $model->create($data->all());

        return redirect()->route('dashboard');
    }

    public function show(string $id): InertiaResponse
    {
        return Inertia::render('Instance/Partials/Camunda/Show', ['instanceId' => $id]);
    }

    public function update(string $id, Instance $model): void
    {
        $model->find($id)->update(request()->only([
            'description',
            'version'
        ]));

        $this->flashMessage(message: 'Instance successfully updated!');
    }
}
