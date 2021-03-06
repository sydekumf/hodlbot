<?php

namespace App\Service\Forecast;

use Illuminate\Support\Facades\DB;

class GetForecastDataService
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

        $data = [];
        if (count($balances) == 0) {
            foreach (config('bot.forecast') as $target) {
                $data[$target] = 'n.A.';
            }
            return $data;
        }

        $averageDailyGain = 0;
        foreach ($balances as $balance) {
            $averageDailyGain += $balance->daily_gain;
        }
        $averageDailyGain = $averageDailyGain / count($balances) / 100;
        $currentValue = $balance->usd_value;

        foreach (config('bot.forecast') as $target) {
            if ($averageDailyGain == 0) {
                $data[$target] = 'n.A.';
                continue;
            }
            $days = log($target / $currentValue) / log(1 + $averageDailyGain);
            if ($days < 0) {
                $data[$target] = 'n.A.';
                continue;
            }
            $now = new \DateTime();
            $interval = new \DateInterval('P' . ceil($days) . 'D');
            $now->add($interval);
            $data[$target] = $now->format('d.m.Y');
        }

        return $data;
    }
}
