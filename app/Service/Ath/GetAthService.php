<?php

namespace App\Service\Ath;

use Illuminate\Support\Facades\DB;

class GetAthService
{
    /**
     * @param array $botConfig
     * @return float
     */
    public function execute(array $botConfig)
    {
        $coinBalance = DB::connection($botConfig['id'])->table('trade_history')
            ->select(['alt_coin_id', 'alt_trade_amount', 'datetime'])
            ->where('selling', '=', 0)
            ->where('state', '=', 'COMPLETE')
            ->orderBy('datetime', 'desc')
            ->limit(1)
            ->get()
            ->first();

        $athPrice = DB::table('ath')
            ->select('ath_price_usd')
            ->where('coin', '=', $coinBalance->alt_coin_id)
            ->value('ath_price_usd');

        return !is_null($athPrice) ? $coinBalance->alt_trade_amount * $athPrice : 0;
    }
}
