<?php

namespace App\Http\Controllers;

use App\Data\InstanceData;
use App\Models\Instance;
use Inertia\Inertia;

class InstanceController extends Controller
{
    public function index(Instance $model)
    {
        return response()->json($model->all());
    }

    public function create()
    {
        return Inertia::render('Instance/Create');
    }

    public function store(Instance $model, InstanceData $data)
    {
        $model->create($data->all());

        return redirect()->route('dashboard');
    }

    public function show($id)
    {
        return Inertia::render('Instance/Partials/Camunda/Show', ['instanceId' => $id]);
    }

    public function update($id, Instance $model)
    {
        $model->find($id)->update(request()->only([
            'description',
            'version'
        ]));

        $this->flashMessage(message: 'Instance successfully updated!');
    }
}
