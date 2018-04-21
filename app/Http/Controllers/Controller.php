<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Generates a URL to a controller action.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    public function action($name, $parameters = [], $absolute = true)
    {
        if (strpos($name, '@') == 0) {
            $name = '\\'.static::class.'@'.ltrim($name, '@');
        }

        return action($name, $parameters, $absolute);
    }
}
