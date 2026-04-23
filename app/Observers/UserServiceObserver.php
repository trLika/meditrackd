<?php

namespace App\Observers;

use App\Services\AdminStatsService;

class UserServiceObserver
{
    protected $statsService;

    public function __construct(AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Handle the pivot "attached" event.
     */
    public function pivotAttached($model, $relationName, $pivotIds, $pivotIdsAttributes): void
    {
        if ($relationName === 'services' || $relationName === 'users') {
            $this->statsService->refreshStats();
        }
    }

    /**
     * Handle the pivot "detached" event.
     */
    public function pivotDetached($model, $relationName, $pivotIds): void
    {
        if ($relationName === 'services' || $relationName === 'users') {
            $this->statsService->refreshStats();
        }
    }

    /**
     * Handle the pivot "sync" event.
     */
    public function pivotSync($model, $relationName, $pivotIds): void
    {
        if ($relationName === 'services' || $relationName === 'users') {
            $this->statsService->refreshStats();
        }
    }
}
