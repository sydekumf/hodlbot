<?php

namespace App\Service\Balance;

use App\Service\Binance\CandleStickService;
use App\Service\Binance\TradeHistoryAggregatorService;
use Illuminate\Support\Facades\DB;

class UpdateService
{
    /**
     * @var TradeHistoryAggregatorService
     */
    private TradeHistoryAggregatorService $tradeHistoryAggregatorService;

    /**
     * @var CandleStickService
     */
    private CandleStickService $candleStickService;

    /**
     * UpdateService constructor.
     * @param TradeHistoryAggregatorService $tradeHistoryAggregatorService
     * @param CandleStickService $candleStickService
     */
    public function __construct(
        TradeHistoryAggregatorService $tradeHistoryAggregatorService,
        CandleStickService $candleStickService
    ) {
        $this->tradeHistoryAggregatorService = $tradeHistoryAggregatorService;
        $this->candleStickService = $candleStickService;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        foreach (config('bot.bots') as $bot) {
            $lastUpdate = DB::table('balance_snapshot')
                ->select(['created_at'])
                ->whereIn( 'coin', $bot['coin_list'])
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->value('created_at');
            if (is_null($lastUpdate)) {
                $lastUpdate = $bot['start_date'];
                // subtract one day, as the start date should also be considered
                $lastUpdate->modify('-1 day');
            } else {
                $lastUpdate = new \DateTime($lastUpdate);
            }

            $now = new \DateTime();
            // subtract one day as cron job execution is scheduled at midnight,
            // so it is actually the next day already
            $now->modify('-1 day');
            $nowTmp = clone $now;
            $lastUpdateTmp = clone $lastUpdate;
            if ($nowTmp->setTime(0, 0, 0) == $lastUpdateTmp->setTime(0, 0, 0)) {
                // last update was today, so nothing to do
                continue;
            }

            $tradeHistory = $this->tradeHistoryAggregatorService->execute(
                $bot['coin_list'],
                $bot['bridge'],
                $lastUpdate->getTimestamp() * 1000
            );

            // add one day to not calculate the current day as it is already in database
            $lastUpdate->modify('+1 day');
            $period = new \DatePeriod(
                $lastUpdate,
                new \DateInterval('P1D'),
                $now
            );

            $data = [];
            foreach ($period as $date) {
                if (count($tradeHistory) == 0) {
                    // no trades available
                    $this->fillNonTradeDay($data, $date, $bot, $lastUpdate);
                    continue;
                }

                // get next trade
                $trade = reset($tradeHistory);
                $trade['time'] = $trade['time']->setTime(0, 0, 0);

                // throw away all past trades if there are any
                while ($trade['time'] < $date) {
                    array_shift($tradeHistory);
                    if (count($tradeHistory) == 0) {
                        // no trades available
                        $this->fillNonTradeDay($data, $date, $bot, $lastUpdate);
                        continue 2;
                    }
                    $trade = reset($tradeHistory);
                    $trade['time'] = $trade['time']->setTime(0, 0, 0);
                }

                $price = $this->candleStickService->execute(
                    $trade['coin'] . $bot['bridge'],
                    $lastUpdate->getTimestamp() * 1000,
                    $date->getTimestamp() * 1000
                );

                if ($trade['time'] == $date) {
                    // current trade is today
                    array_shift($tradeHistory);

                    $data[] = [
                        'coin' => $trade['coin'],
                        'amount' => $trade['qty'],
                        'usd_value' => $price * $trade['qty'],
                        'created_at' => $date
                    ];
                } elseif ($trade['time'] > $date) {
                    // current trade is in future, so no trade for this day available
                    $this->fillNonTradeDay($data, $date, $bot, $lastUpdate);
                }
            }

            DB::table('balance_snapshot')->insert($data);
        }
    }

    /**
     * @param array $data
     * @param \DateTimeInterface $date
     * @param array $bot
     * @param \DateTime $lastUpdate
     */
    private function fillNonTradeDay(array &$data, \DateTimeInterface $date, array $bot, \DateTime $lastUpdate)
    {
        if (count($data)) {
            // no trades for this day, so take the last entry and update the date
            $lastEntry = end($data);

            $price = $this->candleStickService->execute(
                $lastEntry['coin'] . $bot['bridge'],
                $lastUpdate->getTimestamp() * 1000,
                $date->getTimestamp() * 1000
            );

            $lastEntry['created_at'] = $date;
            $lastEntry['usd_value'] = $price * $lastEntry['amount'];
            $data[] = $lastEntry;
        } else {
            $lastEntry = DB::table('balance_snapshot')
                ->select(['coin', 'amount', 'usd_value'])
                ->whereIn( 'coin', $bot['coin_list'])
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->get();

            if (count($lastEntry) == 0) {
                // no trades yet, so take start budget from config
                $data[] = [
                    'coin' => $bot['bridge'],
                    'amount' => $bot['start_balance'],
                    'usd_value' => $bot['start_balance'],
                    'created_at' => $date
                ];
            } else {
                // copy last trade
                $lastEntry = $lastEntry->first();

                $price = $this->candleStickService->execute(
                    $lastEntry->coin . $bot['bridge'],
                    $lastUpdate->getTimestamp() * 1000,
                    $date->getTimestamp() * 1000
                );

                $data[] = [
                    'coin' => $lastEntry->coin,
                    'amount' => $lastEntry->amount,
                    'usd_value' => $price * $lastEntry->amount,
                    'created_at' => $date
                ];
            }
        }
    }
}
