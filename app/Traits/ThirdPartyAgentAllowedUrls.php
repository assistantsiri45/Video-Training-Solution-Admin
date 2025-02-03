<?php

namespace App\Traits;

trait ThirdPartyAgentAllowedUrls
{
    protected $thirdPartyAgentAllowedUrls = [
        'home',
        'third-party-orders',
        'third-party-orders/*',
        'get-student',
        'third-party/*',
        'agent-orders',
    ];

    protected function thirdPartyAgentAllowedUrls()
    {
        return config('app.allowed_urls', $this->thirdPartyAgentAllowedUrls);
    }
}
