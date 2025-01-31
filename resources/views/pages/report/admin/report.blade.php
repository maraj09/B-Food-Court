@extends('layouts.admin.app')
@section('contents')
  @include('pages.report.admin.toolbar.reportToolbar')
  <style>
    .custom-legend-width-for-col-4 .apexcharts-legend-series {
      width: 80px;
      white-space: normal;
      overflow: hidden;
      margin-right: 25px
    }
  </style>
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="row gx-5 draggable-zone">
        <div class="col-xxl-8 mb-5 draggable-zone">
          <!--begin::Chart widget 8-->
          <div class="draggable mb-5">
            <div class="card card-bordered">
              <!--begin::Header-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold ">Hello, {{ auth()->user()->name }}</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Welcome Back</span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true" id="durationSelector"
                      aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadPdf">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <!--end::Header-->
              <div class="card-body">
                <div id="vendor_payout_report" style="height: 300px;"></div>
              </div>
            </div>
          </div>
          <div class="draggable mb-5">
            <div class="card card-bordered">
              <!--begin::Header-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold">Payment Mode</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Total Used</span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true"
                      id="durationSelectorPaymentMode" aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadPaymentModePdf">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <!--end::Header-->
              <div class="card-body">
                <div id="payment_mode_chart" style="height: 200px;"></div>
              </div>
            </div>
          </div>
          <div class="draggable">
            <div class="card card-bordered">
              <!--begin::Header-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold">Sales</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Item Category</span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true"
                      id="durationSelectorItemCategorySells" aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadPdfItemCategory">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <!--end::Header-->
              <div class="card-body">
                <div id="item_category_sells_chart" style="height: 300px;"></div>
              </div>
            </div>
          </div>
          <!--end::Chart widget 8-->
        </div>
        <div class="col-xxl-4 mb-5 draggable-zone">
          <!--begin::Chart widget 8-->
          <div class="mb-5 draggable">
            <div class="card card-bordered">
              <!--begin::Chart-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold">Items</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Status</span>
                </h3>
              </div>
              <div id="vendor_payout_report_pie" class="mx-auto mb-4"></div>
              <!--end::Chart-->
            </div>
            <!--end::Chart widget 8-->
          </div>
          <div class="mb-5 draggable">
            <div class="card card-bordered">
              <!--begin::Chart-->
              <div class="card card-bordered">
                <!--begin::Header-->
                <div class="card-header draggable-handle border-0 py-0">
                  <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold">Vendor</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Total Earnings</span>
                  </h3>
                  <div class="card-toolbar">
                    <!--begin::Input group-->
                    <div class="form-floating w-150px">
                      <select class="form-select" data-control="select2" data-hide-search="true"
                        id="durationSelectorVendorEarnings" aria-label="Floating label select example">
                        <option value="lifetime" selected>Lifetime</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                      </select>
                      <label for="floatingSelect"><span class="">Select Duration</span></label>
                      <div class="mt-1 text-end">
                        <a href="#" class="downloadVendorPdf">Generate Report</a>
                      </div>
                    </div>
                    <!--end::Input group-->
                  </div>
                </div>
                <!--end::Header-->
                <div class="card-body">
                  <div id="top_vendor_earnings" style="height: 250px;"></div>
                </div>
              </div>
              <!--end::Chart-->
            </div>
            <!--end::Chart widget 8-->
          </div>
          <div class="mb-5 draggable">
            <div class="card card-bordered">
              <!--end::Header-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold">Play Area</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Top Booked</span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true"
                      id="durationSelectorTopPlayAreaBooked" aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadTopPlayAreaBookingReport">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <div class="card-body h-250px">
                <div id="top_booked_play_area_pie" class="custom-legend-width-for-col-4"></div>
              </div>
            </div>
          </div>
          <div class="draggable">
            <div class="card card-bordered">
              <!--begin::Chart-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold">Items</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Top Ordered</span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true"
                      id="durationSelectorTopItemOrdered" aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadTopOrders">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <div class="card-body h-250px">
                <div id="top_order_item_pie" class="custom-legend-width-for-col-4"></div>
              </div>
              <!--end::Chart-->
            </div>
          </div>
        </div>
        <div class="col-xxl-6 mb-5 draggable-zone">
          <!--begin::Chart widget 8-->
          <div class="draggable">
            <div class="card card-bordered">
              <!--begin::Header-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold ">Earnings & Profit</span>
                  <span class="text-muted mt-1 fw-semibold fs-7"></span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true"
                      id="durationSelectorEarningProfit" aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadVendorEarningProfit">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <!--end::Header-->
              <div class="card-body">
                <div id="earning_profit_report" style="height: 400px;"></div>
              </div>
            </div>
          </div>
          <!--end::Chart widget 8-->
        </div>
        <div class="col-xxl-6 mb-5 draggable-zone">
          <!--begin::Chart widget 8-->
          <div class="draggable">
            <div class="card card-bordered draggable">
              <!--begin::Header-->
              <div class="card-header draggable-handle border-0 py-0">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold ">Profit & Expenses</span>
                  <span class="text-muted mt-1 fw-semibold fs-7"></span>
                </h3>
                <div class="card-toolbar">
                  <!--begin::Input group-->
                  <div class="form-floating w-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true"
                      id="durationSelectorProfitExpences" aria-label="Floating label select example">
                      <option value="lifetime" selected>Lifetime</option>
                      <option value="today">Today</option>
                      <option value="week">This Week</option>
                      <option value="month">This Month</option>
                    </select>
                    <label for="floatingSelect"><span class="">Select Duration</span></label>
                    <div class="mt-1 text-end">
                      <a href="#" class="downloadProfitExpense">Generate Report</a>
                    </div>
                  </div>
                  <!--end::Input group-->
                </div>
              </div>
              <!--end::Header-->
              <div class="card-body">
                <div id="profit_expences_report" style="height: 400px;"></div>
              </div>
            </div>
          </div>

          <!--end::Chart widget 8-->
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('assets/plugins/custom/draggable/draggable.bundle.js') }}"></script>
  <script src="{{ asset('custom/assets/js/adminReport.js') }}"></script>
  <script>
    $(document).ready(function() {
      // Define global variables for chart and initial data
      var adminDashboardWidget = null;
      var chart = null;
      var initialLabels = [];
      var initialEarnings = [];
      var initialRevenues = [];
      var initialExpenses = [];
      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderData(duration) {
        $.ajax({
          url: '/dashboard/get-data-by-duration-12-month/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialEarnings = data.earnings;
            initialRevenues = data.revenues;
            initialExpenses = data.expenses;
            // Render or update the chart
            renderChart(initialLabels, initialEarnings, initialRevenues, initialExpenses);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }
      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderData('lifetime');
      // Event listener for dropdown change
      $('#durationSelector').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderData(selectedDuration);
      });

      $('.downloadPdf').click(function(e) {
        var selectedDuration = $('#durationSelector').val();
        window.location.href = '/dashboard/download-pdf/' + selectedDuration;
      });
    })
  </script>
  <script>
    var KTChartsPie = (function() {
      var initDonutChart = function(containerId, seriesData, isFirstRender) {
        var container = document.querySelector(containerId);

        if (!container) {
          console.error('Chart container element not found:', containerId);
          return;
        }

        var options = {
          series: seriesData,
          chart: {
            fontFamily: "inherit",
            type: "donut",
            width: 350,
          },
          plotOptions: {
            pie: {
              donut: {
                size: "50%",
                labels: {
                  value: {
                    fontSize: "10px"
                  }
                },
              },
            },
          },
          colors: [
            KTUtil.getCssVariableValue("--bs-success"),
            KTUtil.getCssVariableValue("--bs-danger"),
            KTUtil.getCssVariableValue("--bs-info"),
            KTUtil.getCssVariableValue("--bs-primary"),
          ],
          stroke: {
            width: 0
          },
          labels: ["Delivered", "Rejected", "Accepted", "Completed"],
          legend: {
            show: true,
            labels: {
              colors: KTUtil.getCssVariableValue("--bs-gray-500")
            },
          },
          fill: {
            type: "false"
          },
        };

        var chart = new ApexCharts(container, options);
        var isChartRendered = false;

        if (isFirstRender) {
          chart.render();
          isChartRendered = true;
        }

        container.addEventListener("shown.bs.tab", function(event) {
          if (!isChartRendered) {
            chart.render();
            isChartRendered = true;
          }
        });
      };

      return {
        init: function() {
          initDonutChart(
            "#vendor_payout_report_pie",
            {{ json_encode($statusCounts) }},
            true
          );
        },
      };
    })();

    "undefined" != typeof module && (module.exports = KTChartsWidget22);

    KTUtil.onDOMContentLoaded(function() {
      KTChartsPie.init();
    });
  </script>
  <script>
    $(document).ready(function() {

      var chart = null;
      var initialLabels = [];
      var initialEarnings = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderVendorEarning(duration) {
        $.ajax({
          url: '/dashboard/vendor-earning-get-data-chart/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialEarnings = data.earnings;
            // Render or update the chart
            renderVendorEarningsChart(initialLabels, initialEarnings);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderVendorEarning('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorVendorEarnings').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderVendorEarning(selectedDuration);
      });

      $('.downloadVendorPdf').click(function() {
        var selectedDuration = $('#durationSelectorVendorEarnings').val();
        window.location.href = '/dashboard/vendor-earnings-pdf/' + selectedDuration;
      });

    })
  </script>
  <script>
    $(document).ready(function() {
      var chart = null;
      var initialLabels = [];
      var initialSeries = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderPaymentModeData(duration) {
        $.ajax({
          url: '/dashboard/get-payment-mode-by-duration/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialSeries = data.series;
            console.log(initialLabels);
            console.log(initialSeries);

            // Render or update the chart
            renderPaymentModeChart(initialLabels, initialSeries);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderPaymentModeData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorPaymentMode').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderPaymentModeData(selectedDuration);
      });

      $('.downloadPaymentModePdf').click(function() {
        var selectedDuration = $('#durationSelectorPaymentMode').val();
        window.location.href = '/dashboard/payment-mode-pdf/' + selectedDuration;
      });
    })
  </script>
  <script>
    $(document).ready(function() {
      var chart = null;
      var initialLabels = [];
      var initialSeries = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderItemCategorySellsData(duration) {
        $.ajax({
          url: '/dashboard/get-item-category-sells-by-duration/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialSeries = data.series;
            console.log(initialSeries);

            // Render or update the chart
            renderItemCategorySellsChart(initialLabels, initialSeries);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderItemCategorySellsData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorItemCategorySells').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderItemCategorySellsData(selectedDuration);
      });

      $('.downloadPdfItemCategory').click(function() {
        var selectedDuration = $('#durationSelectorItemCategorySells').val();
        window.location.href = '/dashboard/generate-category-sales-report-pdf/' + selectedDuration;
      });
    })
  </script>
  <script>
    $(document).ready(function() {
      // Define global variables for chart and initial data
      var chart = null;
      var initialLabels = [];
      var initialEarnings = [];
      var initialProfit = [];
      var initialVendorEarnigns = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderData(duration) {
        $.ajax({
          url: '/dashboard/get-data-for-earnings-profit/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialEarnings = data.earnings;
            initialProfit = data.profit;
            initialVendorEarnigns = data.vendorEarnings;
            // Render or update the chart
            renderChartEarningProfit(initialLabels, initialEarnings, initialProfit, initialVendorEarnigns);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', xhr);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorEarningProfit').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderData(selectedDuration);
      });
      $('.downloadVendorEarningProfit').click(function() {
        var selectedDuration = $('#durationSelectorEarningProfit').val();
        window.location.href = '/dashboard/generate-earning-profit-report-pdf/' + selectedDuration;
      });

    })
  </script>
  <script>
    $(document).ready(function() {
      var chart = null;
      var initialLabels = [];
      var initialProfit = [];
      var initialExpences = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderData(duration) {
        $.ajax({
          url: '/dashboard/get-data-by-duration-12-month/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialProfit = data.profit;
            initialExpences = data.expenses;
            // Render or update the chart
            renderChartProfitExpences(initialLabels, initialProfit, initialExpences);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorProfitExpences').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderData(selectedDuration);

      });

      $('.downloadProfitExpense').click(function() {
        var selectedDuration = $('#durationSelectorProfitExpences').val();
        window.location.href = '/dashboard/generate-profit-expense-report-pdf/' + selectedDuration;
      });

    })
  </script>
  <script>
    $(document).ready(function() {
      // Define global variables for chart and initial data
      var chart = null;
      var initialDatas = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderData(duration) {
        $.ajax({
          url: '/dashboard/get-top-ordered-item-data/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            initialDatas = data.data;
            // Render or update the chart
            renderChartTopOrderedItem(initialDatas);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorTopItemOrdered').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderData(selectedDuration);
      });

      $('.downloadTopOrders').click(function() {
        var selectedDuration = $('#durationSelectorTopItemOrdered').val();
        window.location.href = '/dashboard/generate-top-order-items-report-pdf/' + selectedDuration;
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Define global variables for chart and initial data
      var chart = null;
      var initialDatas = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderData(duration) {
        $.ajax({
          url: '/dashboard/get-top-booked-play-area-data/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            initialDatas = data.data;
            // Render or update the chart
            renderChartTopBookedPlayArea(initialDatas);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorTopPlayAreaBooked').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderData(selectedDuration);
      });

      $('.downloadTopPlayAreaBookingReport').click(function() {
        var selectedDuration = $('#durationSelectorTopItemOrdered').val();
        window.location.href = '/dashboard/generate-top-play-area-booked-report-pdf/' + selectedDuration;
      });
    });
  </script>
  <script src="{{ asset('assets/plugins/custom/draggable/draggable.bundle.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
  <script>
    // Ensure the DOM is fully loaded before running the script
    document.addEventListener('DOMContentLoaded', function() {
      // Select all containers with the class .draggable-zone
      var containers = document.querySelectorAll(".draggable-zone");

      if (containers.length === 0) {
        return false;
      }

      // Initialize Sortable.js for each container
      containers.forEach(function(container) {
        new Sortable(container, {
          draggable: ".draggable", // Specify which items inside the container are draggable
          handle: ".draggable-handle", // Specify the handle for dragging
          mirror: {
            appendTo: "body",
            constrainDimensions: true
          }
        });
      });
    });
  </script>
@endsection
