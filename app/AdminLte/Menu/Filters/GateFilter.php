<?php


namespace App\AdminLte\Menu\Filters;


use App\Models\User;
use App\Traits\AllowedUrls;
use App\Traits\ContentManagerAllowedUrls;
use App\Traits\FinanceManagerAllowedUrls;
use App\Traits\SuperAdminNotAllowedUrls;
use App\Traits\BackOfficeManagerNotAllowedUrls;
use App\Traits\JuniorAdminNotAllowedUrls;
use App\Traits\ThirdPartyAgentAllowedUrls;
use App\Traits\AssistantAllowedUrls;
use App\Traits\ReportingAllowedUrls;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class GateFilter implements FilterInterface
{
    use AllowedUrls;
    use ContentManagerAllowedUrls;
    use FinanceManagerAllowedUrls;
    use ThirdPartyAgentAllowedUrls;
    use SuperAdminNotAllowedUrls;
    use AssistantAllowedUrls;
    use ReportingAllowedUrls;
    use BackOfficeManagerNotAllowedUrls;
    use JuniorAdminNotAllowedUrls;

    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function transform($item, Builder $builder)
    {
        if (! $this->isVisible($item)) {
            return false;
        }

        return $item;
    }

    protected function isVisible($item)
    {
        $submenu = $item['submenu'] ?? null;
        if ($submenu) {
            foreach ($item['submenu'] as $submenu) {
                $isVisible = $this->isVisible($submenu);

                if ($isVisible) return true;
            }
        }


        $url = $item['url'] ?? '';
        $routeAllowed = $this->isAllowedUrl($this->allowedUrls(), $url);
        $contentManagerAllowedUrls = $this->isAllowedUrl($this->contentManagerAllowedUrls(), $url);
        $reportingAllowedUrls=$this->isAllowedUrl($this->reportingAllowedUrls(), $url);
        $financeManagerAllowedUrls = $this->isAllowedUrl($this->financeManagerAllowedUrls(), $url);
        $thirdPartyAgentAllowedUrls = $this->isAllowedUrl($this->thirdPartyAgentAllowedUrls(), $url);
        $superAdminNotAllowedUrls = $this->isNotAllowedUrl($this->superAdminNotAllowedUrls(), $url);
        $assistantAllowedUrls = $this->isAllowedUrl($this->assistantAllowedUrls(), $url);
        $backOfficeManagerNotAllowedUrls=$this->isNotAllowedUrl($this->BackOfficeManagerNotAllowedUrls(), $url);
        $juniorAdminNotAllowedUrls=$this->isNotAllowedUrl($this->JuniorAdminNotAllowedUrls(), $url);
//dd($financeManagerAllowedUrls);
        if(Auth::user()->role == User::ROLE_REPORT_ADMIN && ! $routeAllowed){
            return false;
        }

        if(Auth::user()->role == User::ROLE_CONTENT_MANAGER && ! $contentManagerAllowedUrls) {
            return false;
        }

        if(Auth::user()->role == User::ROLE_FINANCE_MANAGER && ! $financeManagerAllowedUrls) {
            return false;
        }

        if(Auth::user()->role == User::ROLE_THIRD_PARTY_AGENT && ! $thirdPartyAgentAllowedUrls) {
            return false;
        }

        if (Auth::user()->role == User::ROLE_ADMIN && $superAdminNotAllowedUrls) {
            return false;
        }
        if(Auth::user()->role == User::ROLE_ASSISTANT && ! $assistantAllowedUrls) {
            return false;
        }

        if(Auth::user()->role == User::ROLE_REPORTING && ! $reportingAllowedUrls) {
            return false;
        }
        if(Auth::user()->role == User::ROLE_BACKOFFICE_MANAGER && $backOfficeManagerNotAllowedUrls) {
            return false;
        }
        if(Auth::user()->role == User::ROLE_JUNIOR_ADMIN && $juniorAdminNotAllowedUrls) {
            return false;
        }
        return true;
    }

    protected function isAllowedUrl($patterns, $path)
    {
        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    protected function isNotAllowedUrl($patterns, $path)
    {
        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }
}
