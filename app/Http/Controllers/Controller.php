<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function flashMessage(string $message, $type = 'success'): void
    {
        if ($message) {
            session()->flash('flash.banner', $message);
            session()->flash('flash.bannerStyle', $type);
        }
    }
}
