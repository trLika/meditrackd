<?php

namespace App\Observers;

use App\Models\Service;
use App\Services\AdminStatsService;

class ServiceObserver
{
    protected $statsService;

    public function __construct(AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $this->statsService->refreshStats();
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        $this->statsService->refreshStats();
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        $this->statsService->refreshStats();
    }
}
