<?php

namespace App\Service\Binance;

use Binance\API;

class TradeHistoryAggregatorService
{
    /**
     * @var API
     */
    private API $binanceApi;

    /**
     * BalanceSnapshotService constructor.
     */
    public function __construct(
    ) {
        $this->binanceApi = new API(config('bot.public_key'), config('bot.private_key'));
    }

    /**
     * @throws \Exception
     */
    public function execute(array $coinList, string $bridge, int $startTime)
    {
        $aggregatedHistory = [];

        foreach ($coinList as $coin) {
            $pair = $coin . $bridge;
            $history = $this->binanceApi->history($pair, 500, -1, $startTime);

            foreach ($history as $trade) {
                if (array_key_exists($trade['orderId'], $aggregatedHistory)) {
                    $aggregatedHistory[$trade['orderId']]['qty'] += $trade['qty'];
                } else {
                    $aggregatedHistory[$trade['orderId']] = [
                        'time' => new \DateTime('@' . (int)($trade['time'] / 1000)),
                        'qty' => $trade['qty'],
                        'pair' => $pair,
                        'coin' => $coin
                    ];
                }
            }
        }

        uasort($aggregatedHistory, function($a, $b) {
            return $a['time'] <=> $b['time'];
        });

        $lastTrade = null;
        foreach ($aggregatedHistory as $orderId => $trade) {
            if (!is_null($lastTrade)
                && $this->checkSameDayDate($lastTrade['time'], $trade['time'])) {
                if ($lastTrade['time'] < $trade['time']) {
                    unset($aggregatedHistory[$lastTrade['orderId']]);
                } else {
                    continue;
                }
            }

            $lastTrade = $trade;
            $lastTrade['orderId'] = $orderId;
        }

        return $aggregatedHistory;
    }

    /**
     * Checks if two datetime obejcts have the same exact day independently from time
     *
     * @param \DateTime $a
     * @param \DateTime $b
     * @return bool
     */
    private function checkSameDayDate(\DateTime $a, \DateTime $b)
    {
        return $a->format('Y-m-d') == $b->format('Y-m-d');
    }
}
