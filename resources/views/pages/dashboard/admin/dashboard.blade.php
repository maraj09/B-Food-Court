@extends('layouts.admin.app')
@section('contents')
  <!--begin::Toolbar-->
  @include('pages.dashboard.admin.toolbar.toolbar')
  <!--end::Toolbar-->
  <!--begin::Content-->
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
      <!--begin::Row-->
      <div class="row g-5 g-xl-10 mt-12 mt-md-0 d-flex flex-row-fluid draggable-zone">
        <!--begin::Col Main-->
        @can('dashboard-states')
          <div class="col-xl-8 mb-xl-10 d-flex flex-row flex-column-fluid draggable">
            <!--begin::Mixed Widget 2-->
            @include('pages.dashboard.admin.components.mixedWidget')
            <!--end::Mixed Widget 2-->
          </div>
        @endcan
        <!--end::Col Main-->
        <!--begin::Col Bookings-->
        @can('dashboard-information')
          @include('pages.dashboard.admin.components.bookings')
        @endcan
        <!--end::Col Bookings-->
        <!--begin::Col Coupon Status-->
        @can('coupons-management')
          @include('pages.dashboard.admin.components.couponStatus')
        @endcan
        <!--end::Col Coupon Status-->
        <!--begin::Col Support-->
        @can('dashboard-tickets-management')
          @include('pages.dashboard.admin.components.support')
        @endcan
        <!--end::Col Support-->
        <!--begin::Col Latest-->
        @can('dashboard-latest')
          @include('pages.dashboard.admin.components.latest')
        @endcan
        <!--end::Col Latest-->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Content container-->
  </div>
  <!--end::Content-->
@endsection
@section('modules')
  <!--begin::Modal - Add Admin-->
  @include('pages.dashboard.admin.modules.modals.addAdmin')
  @include('pages.dashboard.vendor.modules.toasts.status')
  <!--end::Modal - Add Admin -->
@endsection
@section('scripts')
  <script>
    // JavaScript to toggle the form visibility
    $(document).ready(function() {
      $('#toggleFormBtn').click(function() {
        $('#kt_docs_card_collapsible').collapse('toggle');
      });
      $('#kt_modal_new_ticket_cancel').click(function() {
        $('#kt_docs_card_collapsible').collapse('toggle');
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Handle status filter change
      $('#statusFilter').change(function() {
        const selectedStatus = $(this).val();

        // Show/hide tickets based on selected status
        $('.accordion').each(function() {
          const ticketStatus = $(this).data('status');

          if (selectedStatus === 'all' || ticketStatus === selectedStatus) {
            $(this).show(); // Show the ticket
          } else {
            $(this).hide(); // Hide the ticket
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $('.coupon-checkbox').change(function() {
        var status = $(this).prop('checked') ? 1 : 0;
        var couponId = $(this).data('coupon-id');
        $.ajax({
          url: '{{ route('admin.coupons.toggleStatus') }}',
          type: 'POST',
          data: {
            coupon_id: couponId,
            status: status
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              showToast('success');
            } else {
              showToast('error');

            }
          },
          error: function(xhr, status, error) {
            // Handle error response
            showToast('error');

            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>
  <script>
    function changeStatus(itemId, newStatus, oldStatus) {
      // Store the previous value
      var selectElement = $(`.order_item_${itemId}`);
      var previousStatus = oldStatus;

      // Function to revert to the previous status without triggering onchange
      function revertStatus() {
        selectElement.attr('disabled', false);
        $(`.spinner_custom_${itemId}`).addClass('d-none');

        var oldOnChange = selectElement[0].onchange;
        selectElement[0].onchange = null;
        selectElement.val(previousStatus).change();
        selectElement[0].onchange = oldOnChange;
      }

      selectElement.attr('disabled', true);
      $(`.spinner_custom_${itemId}`).removeClass('d-none');

      Swal.fire({
        text: "Are you sure you would like to change the status?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, change it!",
        cancelButtonText: "No, cancel",
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-active-light",
        },
      }).then(function(result) {
        if (result.isConfirmed) {
          $.ajax({
            url: '{{ route('admin.orderItems.update-status') }}',
            method: 'PUT',
            data: {
              id: itemId,
              status: newStatus,
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
              if (response.success) {
                selectElement[0].onchange = function() {
                  changeStatus(itemId, this.value, newStatus);
                };
                showToast('success', response.message);
              } else {
                showToast('error', response.message);
                revertStatus(); // Revert to the previous status
              }
              selectElement.attr('disabled', false);
              $(`.spinner_custom_${itemId}`).addClass('d-none');
            },
            error: function(xhr, status, error) {
              console.error('Error updating status:', xhr);
              revertStatus(); // Revert to the previous status
            }
          });
        } else {
          revertStatus(); // Revert to the previous status
        }
      });
    }
  </script>
  <script>
    // Define global variables for chart and initial data
    var adminDashboardWidget = null;
    var chart = null;
    var initialLabels = [];
    var initialEarnings = [];
    var initialOrders = [];
    var initialReviews = [];
    var initialCustomers = [];

    // Function to fetch and render chart data based on selected duration
    function fetchAndRenderData(duration) {
      $.ajax({
        url: '/dashboard/get-data-by-duration/' + duration,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          // Store initial data for re-rendering
          initialLabels = data.labels;
          initialEarnings = data.earnings;
          initialOrders = data.orders;
          initialReviews = data.reviews;
          initialCustomers = data.customers;
          // Render or update the chart
          renderChart(initialLabels, initialEarnings, initialOrders, initialReviews, initialCustomers);

        },
        error: function(xhr, status, error) {
          console.error('Error fetching data:', error);
        }
      });
    }

    // Initial fetch and render for "Lifetime" on page load
    fetchAndRenderData('lifetime');

    // Event listener for dropdown change
    $('#durationSelectorDashboard').change(function() {
      var selectedDuration = $(this).val();
      fetchAndRenderData(selectedDuration);
    });

    // Function to render or update the chart using ApexCharts
    function renderChart(labels, earnings, orders, reviews, customer) {
      var chartElement = document.querySelector(".admin-dashboard-chart");
      var chartColor = chartElement.getAttribute("data-kt-color");
      var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
      var primaryColor = KTUtil.getCssVariableValue("--bs-" + chartColor);
      var warningColor = KTUtil.getCssVariableValue("--bs-warning");
      var successColor = KTUtil.getCssVariableValue("--bs-success");
      let seriesData = [];
      // Destroy previous chart instance if exists
      if (chart) {
        chart.destroy();
      }
      if (earnings) {
        seriesData = [{
            name: "Total Earning",
            data: earnings,
          },
          {
            name: "Total Orders",
            data: orders,
          },
          {
            name: "Reviews",
            data: reviews,
          },
          {
            name: "Total Customer",
            data: customer,
          },
        ];
      } else {
        seriesData = [{
            name: "Total Orders",
            data: orders,
          },
          {
            name: "Reviews",
            data: reviews,
          },
          {
            name: "Total Customer",
            data: customer,
          },
        ];
      }
      // Initialize new ApexCharts instance
      chart = new ApexCharts(chartElement, {
        series: seriesData,
        chart: {
          fontFamily: "inherit",
          type: "bar",
          height: 225,
          toolbar: {
            show: false,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: ["50%"],
            borderRadius: 4,
          },
        },
        legend: {
          show: true,
          labels: {
            colors: KTUtil.getCssVariableValue("--bs-gray-500")
          },
          position: 'bottom',
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["transparent"],
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
              colors: KTUtil.getCssVariableValue("--bs-gray-500"),
              fontSize: "12px",
            },
          },
        },
        yaxis: {
          y: 0,
          offsetX: 0,
          offsetY: 0,
          labels: {
            style: {
              colors: KTUtil.getCssVariableValue("--bs-gray-500"),
              fontSize: "12px",
            },
          },
        },
        fill: {
          type: "solid",
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
            formatter: function(val, {
              series,
              seriesIndex,
              dataPointIndex,
              w
            }) {
              if (seriesIndex > 0) {
                return val.toString();
              }
              return "₹" + val;
            },
          },
        },
        colors: [primaryColor, KTUtil.getCssVariableValue("--bs-gray-300"), warningColor, successColor],
        grid: {
          padding: {
            top: 10,
          },
          borderColor: KTUtil.getCssVariableValue("--bs-gray-200"),
          strokeDashArray: 4,
          yaxis: {
            lines: {
              show: true,
            },
          },
        },
      });

      // Render the chart
      chart.render();
    }
  </script>
  <script>
    $(document).ready(function() {
      // Add click event listener to all notify again buttons
      $('.notify-again-btn').click(function() {
        var customerId = $(this).data('customer-id');

        // Make an AJAX POST request to trigger notification for the specific customer
        $.ajax({
          url: '/dashboard/trigger-birthday-reminder',
          type: 'POST',
          data: {
            customer_id: customerId // Send customer ID with the request
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            showToast('success', response.message)
            // Optionally, you can show a success message or update UI
          },
          error: function(err) {
            console.error('Error triggering notification:', err);
            // Handle error or show error message
          }
        });
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Echo.channel('orders')
        .listen('OrderStatusChangeEvent', (e) => {
          var selectElement = document.querySelector('.order_item_' + e.orderItem.id);
          selectElement.onchange = null;
          selectElement.value = e.orderItem.status;

          var event = new Event('change');
          selectElement.dispatchEvent(event);
          selectElement.onchange = function() {
            changeStatus(e.orderItem.id, this.value, e.orderItem.status);
          };
        });
      Echo.channel('dashboard-states')
        .listen('DashboardStatesUpdateEvent', (e) => {
          if (e.type == "orderPlaced") {
            var selectedDuration = $("#durationSelectorDashboard").val();
            fetchAndRenderData(selectedDuration);
            $.ajax({
              url: "{{ route('admin.dashboard.stats') }}",
              method: 'GET',
              success: function(data) {
                $('#total-items-ordered').attr('data-kt-countup-value', data.totalItemsOrdered).text("+" +
                  data.totalItemsOrdered);
                $('#total-play-areas-booked').attr('data-kt-countup-value', data.totalPlayAreasBooked).text(
                  "+" + data.totalPlayAreasBooked);
                $('#total-events-booked').attr('data-kt-countup-value', data.totalEventsBooked).text("+" +
                  data.totalEventsBooked);
                $('#total-earnings-for-items').attr('data-kt-countup-value', data.totalEarningsForItems).text(
                  "+" +
                  data.totalEarningsForItems);
                $('#total-earnings-for-play-areas').attr('data-kt-countup-value', data
                    .totalEarningsForPlayAreas)
                  .text(
                    "+" + data.totalEarningsForPlayAreas);
                $('#total-earnings-for-events').attr('data-kt-countup-value', data.totalEarningsForEvents)
                  .text(
                    "+" +
                    data.totalEarningsForEvents);
                $('#vendor-earnings').attr('data-kt-countup-value', data.vendorEarnings)
                  .text(
                    "₹" +
                    data.vendorEarnings);
              },
              error: function(xhr, status, error) {
                console.error('AJAX error:', error);
              }
            });
          }
        });
    });
  </script>
@endsection
