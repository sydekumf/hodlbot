<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Daily PNL</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php if ($cards['dailyPnl'] >= 0): ?>+<?php endif; ?><?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($cards['dailyPnl'])) ?> €</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <?php if ($cards['dailyGain'] >= 0): ?>
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> <?php echo App\Service\LocaleService::reformat($cards['dailyGain']) ?> %</span>
                                <?php else: ?>
                                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> <?php echo App\Service\LocaleService::reformat($cards['dailyGain']) ?> %</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Weekly PNL</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php if ($cards['weeklyPnl'] >= 0): ?>+<?php endif; ?><?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($cards['weeklyPnl'])) ?> €</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <?php if ($cards['weeklyGain'] >= 0): ?>
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> <?php echo App\Service\LocaleService::reformat($cards['weeklyGain']) ?> %</span>
                                <?php else: ?>
                                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> <?php echo App\Service\LocaleService::reformat($cards['weeklyGain']) ?> %</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Average Daily Gain</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php if ($cards['averageDailyGain'] >= 0): ?>+<?php endif; ?><?php echo App\Service\LocaleService::reformat($cards['averageDailyGain']) ?> %</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-warning mr-2"></span>
                                <span class="text-nowrap"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Overall Performance</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php if ($cards['overallPerformance'] >= 0): ?>+<?php endif; ?><?php echo App\Service\LocaleService::reformat(App\Service\LocaleService::currency($cards['overallPerformance'])) ?> €</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <?php if ($cards['overallPerformanceGain'] >= 0): ?>
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> <?php echo App\Service\LocaleService::reformat($cards['overallPerformanceGain']) ?> %</span>
                                <?php else: ?>
                                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> <?php echo App\Service\LocaleService::reformat($cards['overallPerformanceGain']) ?> %</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
