<?php

namespace App\Service\Forecast;

use Illuminate\Support\Facades\DB;

class GetBotBalancesDataService
{
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
            'ath' => 0
        ];

        return $data;
    }
}
