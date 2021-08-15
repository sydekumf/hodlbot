<?php

namespace App\Http\Controllers;

use App\Service\GetPnlService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * @var GetPnlService
     */
    private GetPnlService $getPnlService;

    /**
     * HomeController constructor.
     * @param GetPnlService $getPnlService
     */
    public function __construct(
        GetPnlService $getPnlService
    ) {
        $this->middleware('auth');
        $this->getPnlService = $getPnlService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $botsData = [];
        foreach (config('bot.bots') as $bot) {
            $botsData[$bot['id']] = ['name' => $bot['name']];
        }

        return view('dashboard', [
            'cards' => $this->getPnlService->execute(),
            'botsData' => $botsData
        ]);
    }
}
