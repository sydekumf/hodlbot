<?php

namespace App\Service\Balance;

use Illuminate\Support\Facades\DB;

class DailyGainService
{
    /**
     * @throws \Exception
     */
    public function execute()
    {
        $allCoins = [];
        foreach (config('bot.bots') as $bot) {
            $allCoins = array_merge($bot['coin_list'], $allCoins);
            $allCoins[] = $bot['bridge'];

            $lastBalance = DB::table('balance_snapshot')
                ->select('usd_value')
                ->whereIn('coin', $bot['coin_list'])
                ->whereNotNull('daily_gain')
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->value('usd_value');

            $unCalculatedRows = DB::table('balance_snapshot')
                ->select()
                ->whereIn('coin', $bot['coin_list'])
                ->whereNull('daily_gain')
                ->orderBy('created_at')
                ->get();

            foreach ($unCalculatedRows as $row) {
                $row->daily_gain = $lastBalance ? ($row->usd_value - $lastBalance) / $lastBalance * 100 : 0;
                $lastBalance = $row->usd_value;
                DB::table('balance_snapshot')->where('id', '=', $row->id)->update(['daily_gain' => $row->daily_gain]);
            }
        }

        $lastBalanceDate = DB::table('balance_snapshot')
            ->selectRaw('DATE_SUB(created_at, INTERVAL 1 DAY) as created_at')
            ->whereNull('total_daily_gain')
            ->whereIn('coin', $allCoins)
            ->orderBy('created_at')
            ->limit(1)
            ->value('created_at');

        $totalLastBalance = DB::table('balance_snapshot')
            ->select(DB::raw('SUM(usd_value) as usd_value_sum'))
            ->whereIn('coin', $allCoins)
            ->whereDate('created_at', '=', $lastBalanceDate)
            ->whereNotNull('total_daily_gain')
            ->orderBy('created_at')
            ->value('usd_value_sum');

        $totalUnCalculatedRows = DB::table('balance_snapshot')
            ->select()
            ->whereIn('coin', $allCoins)
            ->whereNull('total_daily_gain')
            ->orderBy('created_at')
            ->get();

        $sortedRows = [];
        foreach ($totalUnCalculatedRows as $row) {
            if (array_key_exists($row->created_at, $sortedRows)) {
                $sortedRows[$row->created_at]['usd_value'] += $row->usd_value;
                $sortedRows[$row->created_at]['ids'][] = $row->id;
            } else {
                $sortedRows[$row->created_at] = [
                    'usd_value' => $row->usd_value,
                    'ids' => [$row->id]
                ];
            }
        }

        foreach ($sortedRows as $row) {
            $totalDailyGain = $totalLastBalance ? ($row['usd_value'] - $totalLastBalance) / $totalLastBalance * 100 : 0;
            $totalLastBalance = $row['usd_value'];
            DB::table('balance_snapshot')->whereIn('id', $row['ids'])->update(['total_daily_gain' => $totalDailyGain]);
        }
    }
}
