<?php

namespace App\Http\Controllers;

use App\Service\Forecast\GetBotBalancesDataService;
use App\Service\Forecast\GetForecastDataService;

class ForecastController extends Controller
{
    /**
     * @var GetBotBalancesDataService
     */
    private GetBotBalancesDataService $getBotBalancesDataService;

    /**
     * @var GetForecastDataService
     */
    private GetForecastDataService $getForecastDataService;

    /**
     * ForecastController constructor.
     * @param GetBotBalancesDataService $getBotBalancesDataService
     * @param GetForecastDataService $getForecastDataService
     */
    public function __construct(
        GetBotBalancesDataService $getBotBalancesDataService,
        GetForecastDataService $getForecastDataService
    ) {
        $this->getBotBalancesDataService = $getBotBalancesDataService;
        $this->getForecastDataService = $getForecastDataService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $botBalances = [];
        $forecastData = [];
        foreach (config('bot.bots') as $bot) {
            $botBalances[] = $this->getBotBalancesDataService->execute($bot);
            $forecastData[$bot['name']] = $this->getForecastDataService->execute($bot);
        }
        $targets = config('bot.forecast');
        return view('forecast', ['botBalances' => $botBalances, 'targets' => $targets, 'forecastData' => $forecastData]);
    }
}
