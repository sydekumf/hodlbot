@extends('layouts.app')

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
        <?php foreach ($coinTableData as $botId => $coinTable): ?>
        <div class="row mt-5">
            <div class="col-xl-4 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0"><?php echo $coinTable['name'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table data-bot-id="<?php echo $botId ?>" class="table align-items-center table-flush table-hover coin-gain-table" id="coin-gain-<?php echo $botId ?>">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Coin</th>
                                <th scope="col">Gain</th>
                                <th scope="col">Last trade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($coinTable['data'] as $coinData): ?>
                            <tr <?php if ($coinData == reset($coinTable)): ?>class="table-active" <?php endif;  ?>>
                                <th scope="row">
                                    <?php if ($coinData['hodl'] == true): ?><i class="fas fa-solid fa-star hodl-coin"></i> <?php endif; ?><span class="coin"><?php echo $coinData['coin'] ?></span>
                                </th>
                                <td>
                                    <?php if ($coinData['gain'] > 0): ?>
                                    <i class="fas fa-arrow-up text-success mr-3"></i> <?php echo \App\Service\LocaleService::reformat($coinData['gain']) ?> %
                                    <?php endif; ?>
                                    <?php if ($coinData['gain'] < 0): ?>
                                    <i class="fas fa-arrow-down text-warning mr-3"></i> <?php echo \App\Service\LocaleService::reformat($coinData['gain']) ?> %
                                    <?php endif; ?>
                                    <?php if ($coinData['gain'] == 0): ?>
                                    <i class="fas mr-3"></i> <?php echo \App\Service\LocaleService::reformat($coinData['gain']) ?> %
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $coinData['last_trade'] ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="text-white mb-0">Coin gain</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="coin-gain-chart-<?php echo $botId ?>" class="chart-canvas coin-gain-chart"></canvas>
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
