/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (() => {



function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var PnlChart = /*#__PURE__*/function () {
  function PnlChart(botId, chartElement, days, switchers) {
    _classCallCheck(this, PnlChart);

    this.botId = botId;
    this.days = days;
    this.switchers = switchers;

    if ($(chartElement).length) {
      this.addSwitcherHandlers();
      this.initChart($(chartElement));
    }
  }

  _createClass(PnlChart, [{
    key: "addSwitcherHandlers",
    value: function addSwitcherHandlers() {
      var _this = this;

      this.switchers.forEach(function (v) {
        var button = $(v);
        var days = button.data('days');
        button.click(function () {
          _this.switchers.forEach(function (v) {
            $(v).find('.nav-link').removeClass('active');
          });

          button.find('.nav-link').addClass('active');

          _this.updateChartWithTimeframe(days);
        });
      });
    }
  }, {
    key: "initChart",
    value: function initChart(element) {
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
                callback: function callback(value) {
                  if (!(value % 10)) {
                    return value + ' €';
                  }
                }
              }
            }]
          },
          tooltips: {
            callbacks: {
              label: function label(item, data) {
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
  }, {
    key: "updateChartWithTimeframe",
    value: function updateChartWithTimeframe(days) {
      var _this2 = this;

      this.getChartData(days).then(function (data) {
        _this2.chart.data.labels = data.labels;
        _this2.chart.data.datasets[0].data = data.data;

        _this2.chart.update();
      });
    }
  }, {
    key: "getChartData",
    value: function getChartData(days) {
      return $.getJSON('balance?days=' + days + '&bot-id=' + this.botId);
    }
  }]);

  return PnlChart;
}();

var CoinGainChart = /*#__PURE__*/function () {
  function CoinGainChart(botId, tableElement, chartElement) {
    _classCallCheck(this, CoinGainChart);

    this.botId = botId;

    if ($(tableElement) && $(chartElement).length) {
      this.tableElement = $(tableElement);
      this.addTableRowHandlers($(tableElement));
      this.initChart($(chartElement));
    }
  }

  _createClass(CoinGainChart, [{
    key: "addTableRowHandlers",
    value: function addTableRowHandlers(element) {
      var _this3 = this;

      var rows = element.find('tbody>tr');
      var i;

      for (i = 0; i < rows.length; i++) {
        var currentRow = rows[i];

        var createClickHandler = function createClickHandler(row) {
          return function () {
            var cell = row.getElementsByClassName('coin')[0];
            var coin = $.trim(cell.innerHTML);

            _this3.updateChartWithCoin(coin);

            $(row).addClass('table-active').siblings().removeClass('table-active');
          };
        };

        currentRow.onclick = createClickHandler(currentRow);
        $(currentRow).hover(function () {
          $(this).addClass('rowHover');
        }, function () {
          $(this).removeClass('rowHover');
        });
      }
    }
  }, {
    key: "initChart",
    value: function initChart(element) {
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
                callback: function callback(value) {
                  if (!(value % 10)) {
                    return value;
                  }
                }
              }
            }]
          },
          tooltips: {
            callbacks: {
              label: function label(item, data) {
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
    }
  }, {
    key: "updateChartWithCoin",
    value: function updateChartWithCoin(coin) {
      var _this4 = this;

      this.getChartData(coin).then(function (data) {
        _this4.chart.data.labels = data.labels;
        _this4.chart.data.datasets[0].data = data.data;

        _this4.chart.update();
      });
    }
  }, {
    key: "getChartData",
    value: function getChartData(coin) {
      return $.getJSON('coingain?bot-id=' + this.botId + '&coin=' + coin);
    }
  }, {
    key: "getTopCoin",
    value: function getTopCoin() {
      var rows = this.tableElement.find('tbody>tr');
      var cell = rows[0].getElementsByClassName('coin')[0];
      return $.trim(cell.innerHTML);
    }
  }]);

  return CoinGainChart;
}();

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

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					result = fn();
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			for(moduleId in moreModules) {
/******/ 				if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 					__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 				}
/******/ 			}
/******/ 			if(runtime) var result = runtime(__webpack_require__);
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/sass/app.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;