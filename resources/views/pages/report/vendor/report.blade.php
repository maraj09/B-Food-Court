@extends('layouts.vendor.app')
@section('contents')
  @include('pages.report.vendor.toolbar.reportToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="row gx-5 gx-xl-10 mt-20 mt-md-0">
        <div class="col-xxl-8 mb-5 mb-xl-10">
          <!--begin::Chart widget 8-->
          <div class="card card-bordered">
            <!--begin::Header-->
            <div class="card-header draggable-handle border-0 py-0">
              <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold ">Hello, {{ auth()->user()->name }}</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Welcome Back</span>
              </h3>
              <div class="card-toolbar">
                <!--begin::Input group-->
                <div class="form-floating">
                  <select class="form-select" id="durationSelector" aria-label="Floating label select example">
                    <option value="lifetime" selected>Lifetime</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                  </select>
                  <label for="floatingSelect"><span class="">Select Duration</span></label>
                </div>
                <!--end::Input group-->
              </div>
            </div>
            <!--end::Header-->
            <div class="card-body">
              <div id="vendor_payout_report" style="height: 250px;"></div>
            </div>
          </div>
          <!--end::Chart widget 8-->
        </div>
        <div class="col-xxl-4 mb-5 mb-xl-10">
          <!--begin::Chart widget 8-->
          <div class="card card-bordered">
            <div class="card-header draggable-handle border-0 py-0">
              <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold ">Items</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Status</span>
              </h3>
            </div>
            <!--begin::Chart-->
            <div class="card-body">
              <div id="vendor_payout_report_pie" class="mx-auto mb-4" style="height: 250px"></div>
            </div>
            <!--end::Chart-->
          </div>
          <!--end::Chart widget 8-->
        </div>
        <div class="col-xxl-12 mb-5 mb-xl-10">
          <!--begin::Chart widget 8-->
          <div class="card card-bordered">
            <!--begin::Header-->
            <div class="card-header draggable-handle border-0 py-0">
              <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold ">Items</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Total Orders</span>
              </h3>
              <div class="card-toolbar">
                <!--begin::Input group-->
                <div class="form-floating">
                  <select class="form-select" id="durationSelectorItemsTotalOrder"
                    aria-label="Floating label select example">
                    <option value="lifetime" selected>Lifetime</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                  </select>
                  <label for="floatingSelect"><span class="">Select Duration</span></label>
                </div>
                <!--end::Input group-->
              </div>
            </div>
            <!--end::Header-->
            <div class="card-body">
              <div id="vendor_item_total_order_report" style="height: 350px;"></div>
            </div>
          </div>
          <!--end::Chart widget 8-->
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      // Define global variables for chart and initial data
      var adminDashboardWidget = null;
      var chart = null;
      var initialLabels = [];
      var initialEarnings = [];
      var initialRevenues = [];
      var initialPayouts = [];

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderData(duration) {
        $.ajax({
          url: '/vendor/get-data-by-duration-12-month/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialEarnings = data.earnings;
            initialRevenues = data.revenues;
            initialPayouts = data.payouts;

            // Render or update the chart
            renderChart(initialLabels, initialEarnings, initialRevenues, initialPayouts);
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

      function renderChart(labels, earnings, revenues, payouts) {
        var chartElement = document.querySelector("#vendor_payout_report");
        var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
        var primaryColor = KTUtil.getCssVariableValue("--bs-primary");
        var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
        var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
        var gray300 = KTUtil.getCssVariableValue("--bs-gray-300");
        var dangerColor = KTUtil.getCssVariableValue("--bs-danger");

        if (chart) {
          chart.destroy();
        }

        chart = new ApexCharts(chartElement, {
          series: [{
              name: "Net Profit",
              data: earnings
            },
            {
              name: "Payout",
              data: payouts
            },
            {
              name: "Revenue",
              data: revenues
            }
          ],
          chart: {
            fontFamily: "inherit",
            type: "bar",
            height: 250,
            toolbar: {
              show: false
            }
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: "40%",
              borderRadius: 6
            }
          },
          legend: {
            show: true,
            labels: {
              colors: KTUtil.getCssVariableValue("--bs-gray-500")
            },
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ["transparent"]
          },
          xaxis: {
            categories: labels,
            axisBorder: {
              show: false
            },
            axisTicks: {
              show: false
            },
            labels: {
              style: {
                colors: gray500,
                fontSize: "12px"
              }
            }
          },
          yaxis: {
            labels: {
              style: {
                colors: gray500,
                fontSize: "12px"
              }
            }
          },
          fill: {
            opacity: 1
          },
          states: {
            normal: {
              filter: {
                type: "none",
                value: 0
              }
            },
            hover: {
              filter: {
                type: "none",
                value: 0
              }
            },
            active: {
              allowMultipleDataPointsSelection: false,
              filter: {
                type: "none",
                value: 0
              }
            }
          },
          tooltip: {
            style: {
              fontSize: "12px"
            },
            y: {
              formatter: function(val) {
                return "₹" + val;
              }
            }
          },
          colors: [primaryColor, dangerColor, gray300],
          grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
              lines: {
                show: true
              }
            }
          }
        });


        console.log('asd');
        chart.render();
      }

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
            width: 350
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
      var renderItemTotalOrderChartNew = null;
      var initialLabels = [];
      var initialSeries = [];

      function renderItemsTotalOrderChart(labels, series) {
        var chartElement = document.querySelector("#vendor_item_total_order_report");
        var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
        var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
        var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
        var primaryColor = KTUtil.getCssVariableValue("--bs-primary");
        var secondaryColor = KTUtil.getCssVariableValue("--bs-secondary");
        var infoColor = KTUtil.getCssVariableValue("--bs-info");
        var warningColor = KTUtil.getCssVariableValue("--bs-warning");
        var dangerColor = KTUtil.getCssVariableValue("--bs-danger");
        var successColor = KTUtil.getCssVariableValue("--bs-success");

        if (renderItemTotalOrderChartNew) {
          renderItemTotalOrderChartNew.destroy();
        }

        renderItemTotalOrderChartNew = new ApexCharts(chartElement, {
          series: series,
          chart: {
            fontFamily: "inherit",
            type: "area",
            height: 350,
            toolbar: {
              show: false,
            },
          },
          colors: [
            primaryColor,
            secondaryColor,
            dangerColor,
            infoColor,
            warningColor,
            successColor,
          ],
          legend: {
            show: true,
            labels: {
              colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
          },
          dataLabels: {
            enabled: false,
          },
          stroke: {
            show: true,
            width: 3,
            curve: "smooth",
          },
          xaxis: {
            categories: labels,
            axisBorder: {
              show: false,
            },
            axisTicks: {
              show: false,
            },
            labels: {
              style: {
                colors: gray500,
                fontSize: "12px",
              },
            },
          },
          yaxis: {
            labels: {
              style: {
                colors: gray500,
                fontSize: "12px",
              },
            },
          },
          fill: {
            type: "solid",
            opacity: 0.35,
          },
          states: {
            normal: {
              filter: {
                type: "none",
                value: 0,
              },
            },
            hover: {
              filter: {
                type: "none",
                value: 0,
              },
            },
            active: {
              allowMultipleDataPointsSelection: false,
              filter: {
                type: "none",
                value: 0,
              },
            },
          },
          tooltip: {
            style: {
              fontSize: "12px",
            },
            y: {
              formatter: function(val) {
                return "₹" + val;
              },
            },
          },
          colors: [
            primaryColor,
            dangerColor,
            secondaryColor,
            infoColor,
            warningColor,
            successColor,
          ],
          grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
              lines: {
                show: true,
              },
            },
          },
        });
        renderItemTotalOrderChartNew.render();
      }

      // Function to fetch and render chart data based on selected duration
      function fetchAndRenderItemsTotalOrderData(duration) {
        $.ajax({
          url: '/vendor/get-items-total-order-by-duration/' + duration,
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            // Store initial data for re-rendering
            initialLabels = data.labels;
            initialSeries = data.series;

            // Render or update the chart
            renderItemsTotalOrderChart(initialLabels, initialSeries);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching data:', xhr);
          }
        });
      }

      // Initial fetch and render for "Lifetime" on page load
      fetchAndRenderItemsTotalOrderData('lifetime');

      // Event listener for dropdown change
      $('#durationSelectorItemsTotalOrder').change(function() {
        var selectedDuration = $(this).val();
        fetchAndRenderItemsTotalOrderData(selectedDuration);
      });



    })
  </script>
@endsection
