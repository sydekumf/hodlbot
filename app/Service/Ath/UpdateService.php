<?php

namespace App\Service\Ath;

use App\Service\Binance\AthPriceService;
use Illuminate\Support\Facades\DB;

class UpdateService
{
    /**
     * @var AthPriceService
     */
    private AthPriceService $athPriceService;

    /**
     * UpdateService constructor.
     * @param AthPriceService $athPriceService
     */
    public function __construct(
        AthPriceService $athPriceService
    ) {
        $this->athPriceService = $athPriceService;
    }

    /**
     * Invoked by scheduler
     */
    public function __invoke()
    {
        $this->execute();
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        foreach (config('bot.bots') as $bot) {
            foreach ($bot['coin_list'] as $coin) {
                $coinAth = DB::table('ath')
                    ->select()
                    ->where( 'coin', '=', $coin)
                    ->limit(1)
                    ->get();

                if (count($coinAth) == 0) {
                    $lastUpdate = $bot['start_date'];
                } else {
                    $coinAth = $coinAth->first();
                    $lastUpdate = new \DateTime($coinAth->updated_at);
                }

                $newAth = $this->athPriceService->execute(
                    $coin . $bot['bridge'],
                    $lastUpdate->getTimestamp() * 1000,
                );

                if (is_countable($coinAth) && count($coinAth) == 0) {
                    DB::table('ath')->insert([
                        'coin' => $coin,
                        'ath_price_usd' => $newAth,
                        'updated_at' => new \DateTime()
                    ]);
                } else {
                    $coinAth->ath_price_usd = max($coinAth->ath_price_usd, $newAth);
                    DB::table('ath')->where('id', $coinAth->id)
                        ->update([
                            'coin' => $coinAth->coin,
                            'ath_price_usd' => $coinAth->ath_price_usd,
                            'updated_at' => new \DateTime()
                        ]);
                }
            }
        }
    }
}
