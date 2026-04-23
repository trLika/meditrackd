<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AdminStatsService;

class UserObserver
{
    protected $statsService;

    public function __construct(AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->statsService->refreshStats();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->statsService->refreshStats();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->statsService->refreshStats();
    }
}
