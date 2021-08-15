<?php

namespace App\Service;

use Illuminate\Support\Facades\DB;

class GetBalanceChartDataService
{
    /**
     * @return array
     */
    public function execute(int $days, string $botId = null)
    {
        $pastBalanceRequest = DB::table('balance_snapshot')
            ->select(DB::raw('SUM(usd_value) as usd_value_sum, created_at'))
            ->groupBy('created_at')
            ->orderBy('created_at');

        if ($days !== 0) {
            $pastBalanceRequest->whereDate('created_at', '>=', now()->subDays($days + 1));
        }

        if (!is_null($botId)) {
            $pastBalanceRequest->whereIn('coin', $this->getCoinList($botId));
        }

        $pastBalance = $pastBalanceRequest->get();

        $result = [];
        foreach ($pastBalance as $balance) {
            $result[date('d.m.y', strtotime($balance->created_at))] = LocaleService::currency($balance->usd_value_sum);
        }

        return $result;
    }

    /**
     * @param string $botId
     * @return array|mixed
     */
    private function getCoinList(string $botId)
    {
        foreach (config('bot.bots') as $bot) {
            if ($bot['id'] == $botId) {
                return $bot['coin_list'];
            }
        }

        return [];
    }
}
