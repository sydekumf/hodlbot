@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="text-white mb-0">Total Balance</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item" id="total-week-switch" data-days="7">
                                        <div class="nav-link py-2 px-3">
                                            <span class="d-none d-md-block">Week</span>
                                            <span class="d-md-none">W</span>
                                        </div>
                                    </li>
                                    <li class="nav-item mr-2 mr-md-0" id="total-month-switch" data-days="30">
                                        <div class="nav-link py-2 px-3">
                                            <span class="d-none d-md-block">Month</span>
                                            <span class="d-md-none">M</span>
                                        </div>
                                    </li>
                                    <li class="nav-item mr-2 mr-md-0" id="total-max-switch" data-days="0">
                                        <div class="nav-link py-2 px-3 active">
                                            <span class="d-none d-md-block">Max</span>
                                            <span class="d-md-none">Mx</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="total-pnl-chart" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php foreach ($botsData as $botId => $botData): ?>
        <div class="row mt-5">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow pnl-chart" data-bot-id="<?php echo $botId ?>">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="text-white mb-0"><?php echo $botData['name'] ?></h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item" id="week-switch-<?php echo $botId ?>" data-days="7">
                                        <div class="nav-link py-2 px-3">
                                            <span class="d-none d-md-block">Week</span>
                                            <span class="d-md-none">W</span>
                                        </div>
                                    </li>
                                    <li class="nav-item mr-2 mr-md-0" id="month-switch-<?php echo $botId ?>" data-days="30">
                                        <div class="nav-link py-2 px-3">
                                            <span class="d-none d-md-block">Month</span>
                                            <span class="d-md-none">M</span>
                                        </div>
                                    </li>
                                    <li class="nav-item mr-2 mr-md-0" id="max-switch-<?php echo $botId ?>" data-days="0">
                                        <div class="nav-link py-2 px-3 active">
                                            <span class="d-none d-md-block">Max</span>
                                            <span class="d-md-none">Mx</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="pnl-chart-<?php echo $botId ?>" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
