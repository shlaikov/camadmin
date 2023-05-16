<?php

use App\Http\Controllers\EditorController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\CamundaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstanceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', fn () => redirect('/dashboard'))->name('main');

// Route::get('/language/{locale}', function ($locale) {
//     app()->setLocale($locale);
//     session()->put('locale', $locale);

//     return redirect()->back();
// })->name('language');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/team', [DashboardController::class, 'teamMembers'])->name('current-team.members');

    Route::prefix('diagrams')->group(function () {
        Route::get('/', [DiagramController::class, 'index'])->name('diagrams');
        Route::get('/create', [DiagramController::class, 'create'])->name('diagrams.create');
        Route::post('/create', [DiagramController::class, 'store'])->name('diagrams.store');
        Route::post('/import', [DiagramController::class, 'import'])->name('diagrams.import');
        Route::get('/{uuid}', [EditorController::class, 'show'])->name('diagrams.editor');
        Route::post('/{uuid}', [EditorController::class, 'deploy'])->name('diagrams.deploy');
        Route::get('/{uuid}/preview.svg', [EditorController::class, 'preview'])->name('diagrams.preview');
        Route::get('/{uuid}/file.{extension}', [EditorController::class, 'file'])->name('diagrams.file');
    });

    Route::prefix('instances')->group(function () {
        Route::get('/', [InstanceController::class, 'index'])->name('instances');
        Route::get('/add', [InstanceController::class, 'create'])->name('instances.create');
        Route::post('/add', [InstanceController::class, 'store'])->name('instances.store');
        Route::get('/camunda/{id}', [InstanceController::class, 'show'])
            ->where('id', '[0-9]+')->name('instances.show');
        Route::put('/camunda/{id}', [InstanceController::class, 'update'])
            ->where('id', '[0-9]+')->name('instances.update');
        Route::delete('/camunda/{id}', [InstanceController::class, 'delete'])
            ->where('id', '[0-9]+')->name('instances.delete');
        Route::get('/camunda/{id}/desicions', [InstanceController::class, 'desicions'])
            ->name('instances.desicions');
        Route::get('/camunda/{id}/incidents', [InstanceController::class, 'incidents'])
            ->name('instances.incidents');
        Route::get('/camunda/{id}/logs', [InstanceController::class, 'logs'])
            ->name('instances.logs');
        Route::get('/camunda/{id}/settings', [InstanceController::class, 'settings'])
            ->name('instances.settings');
        Route::any('/camunda/{id}/{request}', CamundaController::class)
            ->where('id', '[0-9]+')
            ->where('request', '.*')->name('instances.camunda');
    });
});

Route::get('/management/health', fn () => response()->json([
    'status' => 'healthy',
    'app_version' => config('app.version'),
    'framework_version' => app()->version(),
    'app_enviroment' => App::environment()
]));
