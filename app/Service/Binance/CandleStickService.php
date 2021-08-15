<?php

namespace App\Service\Binance;

use Binance\API;

class CandleStickService
{
    /**
     * @var API
     */
    private API $binanceApi;

    /**
     * @var array
     */
    private array $candleSticks = [];

    /**
     * BalanceSnapshotService constructor.
     */
    public function __construct(
    ) {
        $this->binanceApi = new API(config('bot.public_key'), config('bot.private_key'));
    }

    /**
     * @param string $pair
     * @param int $startTime
     * @param $candleStickTime
     * @return mixed
     */
    public function execute(string $pair, int $startTime, $candleStickTime)
    {
        if (!array_key_exists($pair, $this->candleSticks)) {
            $this->init($pair, $startTime);
        }

        if (array_key_exists($candleStickTime, $this->candleSticks[$pair])) {
            return $this->candleSticks[$pair][$candleStickTime]['close'];
        }
    }

    /**
     * @param string $pair
     * @param int $startTime
     * @throws \Exception
     */
    private function init(string $pair, int $startTime)
    {
        $this->candleSticks[$pair] = $this->binanceApi->candlesticks($pair, '1d', null, $startTime);
    }
}
