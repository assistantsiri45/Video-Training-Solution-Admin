<?php

namespace App\Traits;

trait BackOfficeManagerNotAllowedUrls
{
    protected $BackOfficeManagerNotAllowedUrls = [
        'reports',
        'reports/*',
        'orders',
        'orders/*',
        'sales',
        'sales/*',
        'package-reports',
        'package-reports/*',
        'students',
        'students/*',
        'call-requests',
        'call-requests/*',
        'professor-revenues',
        'professor-revenues/*',
        'student-analytics',
        'admin-activity',
        'order-revenue',
        'order-revenue/*',
        'salesrevenue',
        'salesrevenue/*',
        'user-list',
        'techsupport/*'
        
    ];

    protected function BackOfficeManagerNotAllowedUrls()
    {
        return config('app.not_allowed_urls', $this->BackOfficeManagerNotAllowedUrls);
    }
}
