<?php

namespace App\Traits;

trait ReportingAllowedUrls
{
    protected $reportingAllowedUrls = [
        'home',
        'sales',
        'sales/*',
        'fetch-sales-details',
        'orders',
        'orders/*',
        'fetch-order-details',
        'purchases',
        'purchases/*',
        'export-sales-report',
        'order-revenue',
        'order-revenue/*',
        'salesrevenue',
        'salesrevenue/*',
        'courier',
        'invoice_generate',
        'invoice_generate/*'
    ];

    protected function reportingAllowedUrls()
    {
        return config('app.allowed_urls', $this->reportingAllowedUrls);
    }
}
