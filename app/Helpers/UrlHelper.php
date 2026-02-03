<?php

namespace App\Helpers;

class UrlHelper
{
    public static function getAppUrl()
    {
        if (app()->environment('local') && env('FORCE_HTTPS')) {
            return env('APP_URL', 'http://localhost');
        }

        return config('app.url');
    }
}
