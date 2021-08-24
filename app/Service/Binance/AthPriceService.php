<?php

namespace App\Service\Binance;

use Binance\API;

class AthPriceService
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
     * @return mixed
     */
    public function execute(string $pair, int $startTime)
    {
        if (!array_key_exists($pair, $this->candleSticks)) {
            $this->init($pair, $startTime);
        }

        $ath = 0;
        foreach ($this->candleSticks[$pair] as $stick) {
            $ath = max($ath, $stick['high']);
        }

        return $ath;
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
