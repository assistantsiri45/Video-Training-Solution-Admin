<?php

namespace App\Traits;

trait AllowedUrls
{
    protected $allowedUrls = [
        'orders',
        'orders/*',
        'sales',
        'sales/*',
        'home',
        'get-order-response',
        'reports',
        'reports/*',
        'package-reports',
        'package-reports/*',
        'students',
        'students/*',
        'export-sales-report',
        'export-sales-report/*',
        'order-revenue',
        'order-revenue/*',
        'salesrevenue',
        'salesrevenue/*',
    ];

    protected function allowedUrls()
    {
        return config('app.allowed_urls', $this->allowedUrls);
    }
}
