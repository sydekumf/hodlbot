<?php

namespace App\Service\Coin;

use Illuminate\Support\Facades\DB;

class GetCoinGainChartDataService
{
    public function execute(string $botTable, string $coin)
    {
        $query = DB::connection($botTable)->table('trade_history')
            ->select(['alt_coin_id', 'alt_trade_amount', 'datetime'])
            ->where('selling', '=', 0)
            ->where('state', '=', 'COMPLETE')
            ->where('alt_coin_id', '=', $coin)
            ->orderBy('datetime');

        $tradeData = $query->get();

        $result = [];
        foreach ($tradeData as $item) {
            $result[date('d.m.y h:m:s', strtotime($item->datetime))] = $item->alt_trade_amount;
        }

        return $result;
    }
}
