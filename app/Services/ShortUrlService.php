<?php

namespace App\Services;

use AshAllenDesign\ShortURL\Classes\Builder;

class ShortUrlService
{
    /**
     * Generate a short URL for the given destination URL.
     */
    public function generate(string $url): string
    {
        $builder = new Builder();
        $shortURLObject = $builder->destinationUrl($url)->secure()->make();

        return $shortURLObject->default_short_url;
    }
}
