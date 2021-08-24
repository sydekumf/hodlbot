<?php

namespace App\Console\Commands;

use App\Service\Ath\UpdateService;
use Illuminate\Console\Command;

class UpdateAthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ath';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates ath price from Binance';

    /**
     * @var UpdateService
     */
    private UpdateService $updateService;

    /**
     * UpdateAthCommand constructor.
     * @param UpdateService $updateService
     */
    public function __construct(
        UpdateService $updateService
    ) {
        parent::__construct();
        $this->updateService = $updateService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateService->execute();
    }
}
