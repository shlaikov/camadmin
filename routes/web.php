<?php

use App\Http\Controllers\EditorController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('process.create');
    Route::post('/projects/create', [ProjectController::class, 'store'])->name('process.store');
    Route::post('/projects/import', [ProjectController::class, 'import'])->name('process.import');

    Route::get('/process/{uuid}', [EditorController::class, 'show'])->name('editor');
    Route::post('/process/{uuid}', [EditorController::class, 'deploy'])->name('deploy');
    Route::get('/process/{uuid}/preview.svg', [EditorController::class, 'preview'])->name('process.preview');
    Route::get('/process/{uuid}/file.bpmn', [EditorController::class, 'file'])->name('process.file');
});
