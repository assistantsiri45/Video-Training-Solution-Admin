<?php

namespace App\Traits;

trait SuperAdminNotAllowedUrls
{
    protected $superAdminNotAllowedUrls = [
        'third-party-orders',
        'third-party-orders/*',
    ];

    protected function superAdminNotAllowedUrls()
    {
        return config('app.not_allowed_urls', $this->superAdminNotAllowedUrls);
    }
}
