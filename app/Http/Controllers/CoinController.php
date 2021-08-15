<?php

namespace App\Http\Controllers;

use App\Service\Coin\GetCoinGainTable;
use App\Service\Coin\GetCoinGainChartDataService;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    /**
     * @var GetCoinGainTable
     */
    private GetCoinGainTable $getCoinGainTable;

    /**
     * @var GetCoinGainChartDataService
     */
    private GetCoinGainChartDataService $getCoinGainChartDataService;

    /**
     * CoinController constructor.
     * @param GetCoinGainTable $getCoinGainTable
     * @param GetCoinGainChartDataService $getCoinGainChartDataService
     */
    public function __construct(
        GetCoinGainTable $getCoinGainTable,
        GetCoinGainChartDataService $getCoinGainChartDataService
    ) {
        $this->getCoinGainTable = $getCoinGainTable;
        $this->getCoinGainChartDataService = $getCoinGainChartDataService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $coinTableData = [];
        foreach (config('bot.bots') as $bot) {
            $coinTableData[$bot['id']]['data'] = $this->getCoinGainTable->execute($bot['id']);
            $coinTableData[$bot['id']]['name'] = $bot['name'];
        }
        return view('coin_overview', ['coinTableData' => $coinTableData]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getCoinGain(Request $request)
    {
        $botId = $request->input('bot-id');
        $coin = $request->input('coin');

        if (is_null($botId) || is_null($coin)) {
            throw new \Exception('Missing arguments: bot-id and coin');
        }

        $data = $this->getCoinGainChartDataService->execute($botId, $coin);

        return response()->json([
            'labels' => array_keys($data),
            'data' => array_values($data)
        ]);
    }
}
