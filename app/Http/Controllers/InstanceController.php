<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\InstanceData;
use App\Models\Instance;
use Illuminate\Http\JsonResponse;
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

    public function store(Instance $model, InstanceData $data): void
    {
        $instance = $model->create($data->all());

        $this->flashMessage(message: "Instance $instance->name successfully created!");
    }

    public function show(string $id): InertiaResponse
    {
        return Inertia::render('Instance/Show', ['instanceId' => $id]);
    }

    public function update(string $id, Instance $model): void
    {
        $model->find($id)->update(request()->only([
            'name',
            'url',
            'description',
            'version'
        ]));

        $this->flashMessage(message: 'Instance successfully updated!');
    }

    public function delete(string $id, Instance $model): void
    {
        $instance = $model->find($id);

        try {
            $instance->delete();

            $this->flashMessage(message: "Instance $instance->name successfully deleted!");
        } catch (\Throwable $e) {
            $this->flashMessage(message: $e->getMessage(), type: 'error');
        }
    }

    public function desicions(string $id): InertiaResponse
    {
        return Inertia::render('Instance/Desicions', ['instanceId' => $id]);
    }

    public function incidents(string $id): InertiaResponse
    {
        return Inertia::render('Instance/Incidents', ['instanceId' => $id]);
    }

    public function logs(string $id): InertiaResponse
    {
        return Inertia::render('Instance/Logs', ['instanceId' => $id]);
    }

    public function settings(string $id): InertiaResponse
    {
        return Inertia::render('Instance/Settings', ['instanceId' => $id]);
    }
}
