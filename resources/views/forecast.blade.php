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
        <div class="row mt-5">
            <div class="col-xl-12 mb-12 mb-xl-12">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Balances</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Bot</th>
                                <th scope="col">Start Balance</th>
                                <th scope="col">Total Increase</th>
                                <th scope="col">Current Balance</th>
                                <th scope="col">Total Gain</th>
                                <th scope="col">Average Daily Gain</th>
                                <th scope="col">ATH</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($botBalances as $data): ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $data['name'] ?>
                                </th>
                                <td>
                                    <?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($data['start_balance'])) ?> €
                                </td>
                                <td>
                                    <?php if ($data['total_increase'] >= 0): ?>
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> <?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($data['total_increase'])) ?> €</span>
                                    <?php else: ?>
                                        <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> <?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($data['total_increase'])) ?> €</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($data['current_balance'])) ?> €
                                </td>
                                <td>
                                    <?php if ($data['total_gain'] >= 0): ?>
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> <?php echo App\Service\LocaleService::reformat($data['total_gain']) ?> %</span>
                                    <?php else: ?>
                                        <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> <?php echo App\Service\LocaleService::reformat($data['total_gain']) ?> %</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($data['average_daily_gain'] >= 0): ?>
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> <?php echo App\Service\LocaleService::reformat($data['average_daily_gain']) ?> %</span>
                                    <?php else: ?>
                                        <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> <?php echo App\Service\LocaleService::reformat($data['average_daily_gain']) ?> %</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($data['ath'])) ?> €
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-xl-12 mb-12 mb-xl-12">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">To the moon 🚀</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Bot</th>
                                <?php foreach ($targets as $target): ?>
                                    <th scope="col"><?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($target)) ?> €</th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($forecastData as $name => $data): ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $name ?>
                                </th>
                                <?php foreach ($data as $days): ?>
                                <td>
                                    <?php echo $days ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
