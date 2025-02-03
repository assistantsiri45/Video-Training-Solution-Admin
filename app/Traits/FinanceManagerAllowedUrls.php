<?php

namespace App\Traits;

trait FinanceManagerAllowedUrls
{
    protected $financeManagerAllowedUrls = [
        'home',
        'order-revenue',
        'order-revenue/*',
        'salesrevenue',
        'salesrevenue/*',
        'package-reports',
        'package-reports/*',
        'course-levels/*',
        'gettypes/*',
        'export-salesrevenue-report'

    ];

    protected function financeManagerAllowedUrls()
    {
        return config('app.allowed_urls', $this->financeManagerAllowedUrls);
    }
}
