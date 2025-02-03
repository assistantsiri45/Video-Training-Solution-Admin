<?php

namespace App\Traits;

trait AssistantAllowedUrls
{
    protected $assistantAllowedUrls = [
        'home',
        'couriers',
        'couriers/*',
        'purchases',
        'purchases/*'
    ];

    protected function assistantAllowedUrls()
    {
        return config('app.allowed_urls', $this->assistantAllowedUrls);
    }
}
