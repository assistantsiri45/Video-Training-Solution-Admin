<?php

namespace App\Traits;

trait JuniorAdminNotAllowedUrls
{
    protected $JuniorAdminNotAllowedUrls = [
     
        'settings',
        'settings/*',
        'admins',
        'admins/*',
        'admin-activity'        
        
    ];

    protected function JuniorAdminNotAllowedUrls()
    {
        return config('app.not_allowed_urls', $this->JuniorAdminNotAllowedUrls);
    }
}
