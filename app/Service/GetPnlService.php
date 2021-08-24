<?php

namespace App\Service;

use App\Service\Ath\GetAthService;
use Illuminate\Support\Facades\DB;

class GetPnlService
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
     * @return array
     */
    public function execute()
    {
        $balanceTwoDaysAgo = DB::table('balance_snapshot')
            ->select(DB::raw('SUM(usd_value) as usd_value_sum'))
            ->whereDate( 'created_at', '>=', now()->subDays(2))
            ->whereDate('created_at', '<', now()->subDays(1))
            ->value('usd_value_sum');

        $balanceOneDayAgo = DB::table('balance_snapshot')
            ->select(DB::raw('SUM(usd_value) as usd_value_sum'))
            ->whereDate( 'created_at', '=', now()->subDays(1))
            ->value('usd_value_sum');

        $balanceEightDaysAgo = DB::table('balance_snapshot')
            ->select(DB::raw('SUM(usd_value) as usd_value_sum'))
            ->whereDate( 'created_at', '>=', now()->subDays(8))
            ->whereDate('created_at', '<', now()->subDays(7))
            ->value('usd_value_sum');

        $subSelect = DB::table('balance_snapshot')
            ->select(DB::raw('MIN(created_at)'));

        $averageDailyGains = DB::table('balance_snapshot')
            ->select('daily_gain')
            ->where('created_at', '>', DB::raw("({$subSelect->toSql()})"))
            ->groupBy('created_at')
            ->pluck('daily_gain');

        $averageDailyGain = 0;
        if(isset($averageDailyGains) && count($averageDailyGains)) {
            $averageDailyGain = array_sum($averageDailyGains->toArray()) / count($averageDailyGains);
        }

        $balanceFirstDay = DB::table('balance_snapshot')
            ->select(DB::raw('SUM(usd_value) as usd_value_sum'))
            ->where('created_at', '=', DB::raw("({$subSelect->toSql()})"))
            ->value('usd_value_sum');

        $ath = 0;
        foreach (config('bot.bots') as $botConfig) {
            $ath += $this->getAthService->execute($botConfig);
        }

        return [
            'dailyPnl' => $balanceTwoDaysAgo ? $balanceOneDayAgo - $balanceTwoDaysAgo : 0,
            'dailyGain' => $balanceTwoDaysAgo ? ($balanceOneDayAgo - $balanceTwoDaysAgo) / $balanceTwoDaysAgo * 100 : 0,
            'weeklyPnl' => $balanceEightDaysAgo ? $balanceOneDayAgo - $balanceEightDaysAgo : 0,
            'weeklyGain' => $balanceEightDaysAgo ? ($balanceOneDayAgo - $balanceEightDaysAgo) / $balanceEightDaysAgo * 100 : 0,
            'averageDailyGain' => $averageDailyGain,
            'overallPerformance' => $balanceFirstDay ? $balanceOneDayAgo - $balanceFirstDay : 0,
            'overallPerformanceGain' => $balanceFirstDay ? ($balanceOneDayAgo - $balanceFirstDay) / $balanceFirstDay * 100 : 0,
            'ath' => $ath,
            'athGain' => $balanceFirstDay && $ath > 0 ? ($ath - $balanceFirstDay) / $balanceFirstDay * 100 : 0
        ];
    }
}
