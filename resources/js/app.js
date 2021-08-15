'use strict';

class PnlChart {
    constructor(botId, chartElement, days, switchers) {
        this.botId = botId;
        this.days = days
        this.switchers = switchers;

        if ($(chartElement).length) {
            this.addSwitcherHandlers();
            this.initChart($(chartElement));
        }
    }

    addSwitcherHandlers() {
        this.switchers.forEach((v) => {
            var button = $(v)
            var days = button.data('days');

            button.click(() => {
                this.switchers.forEach((v) => {
                    $(v).find('.nav-link').removeClass('active');
                });
                button.find('.nav-link').addClass('active');
                this.updateChartWithTimeframe(days);
            });
        });
    }

    initChart(element) {
        this.chart = new Chart(element, {
            type: 'line',
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: Charts.colors.gray[900],
                            zeroLineColor: Charts.colors.gray[900]
                        },
                        ticks: {
                            callback: function(value) {
                                if (!(value % 10)) {
                                    return value + ' €';
                                }
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(item, data) {
                            var label = data.datasets[item.datasetIndex].label || '';
                            var yLabel = item.yLabel;
                            var content = '';

                            if (data.datasets.length > 1) {
                                content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                            }

                            content += '<span class="popover-body-value">' + yLabel + ' €</span>';
                            return content;
                        }
                    }
                }
            },
            data: {
                labels: [],
                datasets: [{
                    label: 'Balance',
                    data: []
                }]
            }
        });

        this.updateChartWithTimeframe(this.days);

        element.data('chart', this.chart);
    }

    updateChartWithTimeframe(days) {
        this.getChartData(days).then(data => {
            this.chart.data.labels = data.labels;
            this.chart.data.datasets[0].data = data.data;
            this.chart.update();
        });
    }

    getChartData(days) {
        return $.getJSON('balance?days=' + days + '&bot-id=' + this.botId);
    }
}

class CoinGainChart {
    constructor(botId, tableElement, chartElement) {
        this.botId = botId;

        if ($(tableElement) && $(chartElement).length) {
            this.tableElement = $(tableElement);
            this.addTableRowHandlers($(tableElement));
            this.initChart($(chartElement));
        }
    }

    addTableRowHandlers(element) {
        var rows = element.find('tbody>tr');
        var i;
        for (i = 0; i < rows.length; i++) {
            var currentRow = rows[i];

            var createClickHandler = (row) => {
                return () => {
                    var cell = row.getElementsByClassName('coin')[0];
                    var coin = $.trim(cell.innerHTML);
                    this.updateChartWithCoin(coin);
                    $(row).addClass('table-active').siblings().removeClass('table-active');
                };
            };

            currentRow.onclick = createClickHandler(currentRow);
            $(currentRow).hover(
                function () {
                    $(this).addClass('rowHover');
                },
                function () {
                    $(this).removeClass('rowHover');
                }
            );
        }
    }

    initChart(element) {
        this.chart = new Chart(element, {
            type: 'line',
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            // Luxon format string
                            tooltipFormat: 'DD T'
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    yAxes: [{
                        gridLines: {
                            color: Charts.colors.gray[900],
                            zeroLineColor: Charts.colors.gray[900]
                        },
                        ticks: {
                            callback: function(value) {
                                if (!(value % 10)) {
                                    return value;
                                }
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(item, data) {
                            var label = data.datasets[item.datasetIndex].label || '';
                            var yLabel = item.yLabel;
                            var content = '';

                            if (data.datasets.length > 1) {
                                content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                            }

                            content += '<span class="popover-body-value">' + yLabel + '</span>';
                            return content;
                        }
                    }
                }
            },
            data: {
                labels: [],
                datasets: [{
                    label: 'Coin gain',
                    data: []
                }]
            }
        });

        this.updateChartWithCoin(this.getTopCoin());

        element.data('chart', this.chart);
    };

    updateChartWithCoin(coin) {
        this.getChartData(coin).then(data => {
            this.chart.data.labels = data.labels;
            this.chart.data.datasets[0].data = data.data;
            this.chart.update();
        });
    }

    getChartData(coin) {
        return $.getJSON('coingain?bot-id=' + this.botId + '&coin=' + coin);
    }

    getTopCoin() {
        var rows = this.tableElement.find('tbody>tr');
        var cell = rows[0].getElementsByClassName('coin')[0];
        return $.trim(cell.innerHTML);
    }
}

var tables = document.getElementsByClassName('coin-gain-table');
for (var i = 0; i < tables.length; i++) {
    var table = tables[i];
    var botId = table.dataset.botId;
    new CoinGainChart(botId, '#coin-gain-' + botId, '#coin-gain-chart-' + botId);
}

new PnlChart('', '#total-pnl-chart', 0, ['#total-week-switch', '#total-month-switch', '#total-max-switch']);
var charts = document.getElementsByClassName('pnl-chart');
for (i = 0; i < charts.length; i++) {
    var chart = charts[i];
    var botId = chart.dataset.botId;
    new PnlChart(botId, '#pnl-chart-' + botId, 0, ['#week-switch-' + botId, '#month-switch-' + botId, '#max-switch-' + botId]);
}
