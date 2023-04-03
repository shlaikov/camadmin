<?php

namespace App\Http\Middleware;

use App;
use App\Models\Instance;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param Request $request
     *
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param Request $request
     *
     * @return array
     */
    public function share(Request $request)
    {
        $locale = App::currentLocale();

        return array_merge(parent::share($request), [
            'name' => config('app.name'),
            'app_version' => config('app.version'),
            'latest_hash' => config('app.latest_hash'),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'instances' => fn () => $request->user() ? Instance::all() : null,
            'available_locales' => config('app.available_locales'),
            'locale' => fn () => $locale,
            'language' => function () use ($locale) {
                $path = base_path('lang/' . $locale . '.json');

                if (!file_exists($path)) {
                    return [];
                }

                return json_decode((string)file_get_contents($path), true);
            },
        ]);
    }
}
