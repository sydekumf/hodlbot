<?php

namespace App\Service\Balance;

class BalanceSnapshotService
{
    /**
     * @var UpdateService
     */
    private UpdateService $updateService;

    /**
     * @var DailyGainService
     */
    private DailyGainService $dailyGainService;

    /**
     * BalanceSnapshotService constructor.
     * @param UpdateService $updateService
     * @param DailyGainService $dailyGainService
     */
    public function __construct(
        UpdateService $updateService,
        DailyGainService $dailyGainService
    ) {
        $this->updateService = $updateService;
        $this->dailyGainService = $dailyGainService;
    }

    /**
     * Invoked by scheduler
     */
    public function __invoke()
    {
        $this->execute();
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $this->updateService->execute();
        $this->dailyGainService->execute();
    }
}
