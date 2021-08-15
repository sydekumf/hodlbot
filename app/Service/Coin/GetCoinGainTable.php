<?php

namespace App\Service\Coin;

use Illuminate\Support\Facades\DB;

class GetCoinGainTable
{
    /**
     * @param string $botTable
     * @return array
     */
    public function execute(string $botTable)
    {
        $query = DB::connection($botTable)->table('trade_history')
            ->select(['alt_coin_id', 'alt_trade_amount', 'datetime'])
            ->where('selling', '=', 0)
            ->where('state', '=', 'COMPLETE')
            ->orderBy('alt_coin_id')
            ->orderBy('datetime');

        $tradeData = $query->get();

        $result = [];
        foreach ($tradeData as $item) {
            if (!array_key_exists($item->alt_coin_id, $result)) {
                $result[$item->alt_coin_id] = [$item->datetime => $item];
            } else {
                $result[$item->alt_coin_id][$item->datetime] = $item;
            }
        }

        foreach ($result as $coin => $data) {
            if (count($data) == 1) {
                $entry = reset($data);
                $result[$coin] = [
                    'coin' => $coin,
                    'gain' => 0,
                    'last_trade' => date('d.m.y h:m:s', strtotime($entry->datetime)),
                    'hodl' => false
                ];
            }
            $firstEntry = reset($data);
            $lastEntry = end($data);

            $result[$coin] = [
                'coin' => $coin,
                'gain' => ($lastEntry->alt_trade_amount - $firstEntry->alt_trade_amount) / $firstEntry->alt_trade_amount * 100,
                'last_trade' => date('d.m.y h:m:s', strtotime($lastEntry->datetime)),
                'hodl' => false
            ];
        }

        // get current hold coin
        $hodlCoin = DB::connection($botTable)->table('trade_history')
            ->select('alt_coin_id')
            ->where('selling', '=', 0)
            ->where('state', '=', 'COMPLETE')
            ->orderBy('datetime', 'desc')
            ->value('alt_coin_id');

        if (!is_null($hodlCoin) && array_key_exists($hodlCoin, $result)) {
            $result[$hodlCoin]['hodl'] = true;
        }

        uasort($result, function($a, $b) {
            return $b['gain'] <=> $a['gain'];
        });

        return $result;
    }
}
