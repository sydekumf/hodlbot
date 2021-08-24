<?php

namespace App\Service\Forecast;

use App\Service\Ath\GetAthService;
use Illuminate\Support\Facades\DB;

class GetBotBalancesDataService
{
    /**
     * @var GetAthService
     */
    private GetAthService $getAthService;

    /**
     * GetBotBalancesDataService constructor.
     * @param GetAthService $getAthService
     */
    public function __construct(
        GetAthService $getAthService
    ){
        $this->getAthService = $getAthService;
    }

    /**
     * @param array $botConfig
     * @return array
     */
    public function execute(array $botConfig)
    {
        $balances = DB::table('balance_snapshot')
            ->select()
            ->whereIn('coin', $botConfig['coin_list'])
            ->whereNotNull('daily_gain')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        $currentBalanceData = end($balances);
        $currentBalance = $currentBalanceData->usd_value;
        $startBalance = $botConfig['start_balance'];

        $averageDailyGain = 0;
        foreach ($balances as $balance) {
            $averageDailyGain += $balance->daily_gain;
        }
        $averageDailyGain = $averageDailyGain / count($balances);

        $data = [
            'name' => $botConfig['name'],
            'start_balance' => $startBalance,
            'total_increase' => $currentBalance - $startBalance,
            'current_balance' => $currentBalance,
            'total_gain' => ($currentBalance - $startBalance) / $startBalance,
            'average_daily_gain' => $averageDailyGain,
            'ath' => $this->getAthService->execute($botConfig)
        ];

        return $data;
    }
}
