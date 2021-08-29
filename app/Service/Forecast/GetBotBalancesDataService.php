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

        $startBalance = $botConfig['start_balance'];

        if (count($balances) == 0) {
            return [
                'name' => $botConfig['name'],
                'start_balance' => $startBalance,
                'total_increase' => 0,
                'current_balance' => $startBalance,
                'total_gain' => 0,
                'average_daily_gain' => 0,
                'ath' => $this->getAthService->execute($botConfig)
            ];
        }

        $currentBalanceData = end($balances);
        $currentBalance = $currentBalanceData->usd_value;

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
