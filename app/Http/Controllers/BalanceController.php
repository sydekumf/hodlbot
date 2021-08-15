<?php

namespace App\Http\Controllers;

use App\Service\GetBalanceChartDataService;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * @var GetBalanceChartDataService
     */
    private GetBalanceChartDataService $getBalanceChartDataService;

    /**
     * BalanceController constructor.
     * @param GetBalanceChartDataService $getBalanceChartDataService
     */
    public function __construct(
        GetBalanceChartDataService $getBalanceChartDataService
    ) {
        $this->middleware('auth');
        $this->getBalanceChartDataService = $getBalanceChartDataService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(Request $request)
    {
        $days = $request->input('days') ?? 0;
        $botId = $request->input('bot-id');
        $data = $this->getBalanceChartDataService->execute($days, $botId);

        return response()->json([
            'labels' => array_keys($data),
            'data' => array_values($data)
        ]);
    }
}
