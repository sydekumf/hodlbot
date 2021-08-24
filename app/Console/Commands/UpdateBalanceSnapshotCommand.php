<?php

namespace App\Console\Commands;

use App\Service\Balance\BalanceSnapshotService;
use Illuminate\Console\Command;

class UpdateBalanceSnapshotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates balance snapshot from Binance';

    /**
     * @var BalanceSnapshotService
     */
    private BalanceSnapshotService $balanceSnapshotService;

    /**
     * UpdateBalanceSnapshotCommand constructor.
     * @param BalanceSnapshotService $balanceSnapshotService
     */
    public function __construct(
        BalanceSnapshotService $balanceSnapshotService
    ) {
        parent::__construct();
        $this->balanceSnapshotService = $balanceSnapshotService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->balanceSnapshotService->execute();
    }
}
