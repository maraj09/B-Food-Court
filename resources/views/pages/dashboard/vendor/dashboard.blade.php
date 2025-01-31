@extends('layouts.vendor.app')
@section('contents')
  @include('pages.dashboard.vendor.toolbar.toolbar')
  @php
    use App\Models\OrderItem;
    use App\Models\Item;
    use App\Models\VendorBank;
    $vendorId = auth()->user()->vendor->id;

    $totalItems = Item::where('vendor_id', $vendorId)->count();
    $totalOrders = OrderItem::whereHas('item', function ($query) use ($vendorId) {
        $query->where('vendor_id', $vendorId);
    })
        ->where('status', '!=', 'unpaid')
        ->count();
    $totalEarning = VendorBank::where('vendor_id', $vendorId)->first()->total_earning;

    $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
        $query->where('vendor_id', $vendorId);
    })->get();

    // Calculate total sales for the vendor
    $totalSales = $orderItems->sum(function ($item) {
        return $item->quantity * $item->price;
    });

    $balance = VendorBank::where('vendor_id', $vendorId)->first()->balance;
  @endphp
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
      <!--begin::Row-->
      <div class="row g-5 g-xl-10 d-flex flex-row-fluid draggable-zone">
        <!--begin::Col Main-->
        <div class="col-xl-8 mb-xl-5 d-flex flex-row flex-column-fluid draggable">
          <!--begin::Mixed Widget 2-->
          @include('pages.dashboard.vendor.components.dashbord-charts')
          <!--end::Mixed Widget 2-->
        </div>
        <!--end::Col Main-->
        <!--begin::Col Updates-->
        <div class="col-xl-4 mb-5 mb-xl-10 d-flex flex-row flex-column-fluid draggable">
          <!--begin::Timeline widget 3-->
          <div class="card card-flush h-md-100 d-flex flex-row-fluid">
            <!--begin::Header-->
            <div class="card-header ribbon ribbon-top border-0 pt-5 px-0">
              <div class="ribbon-label bg-primary draggable-handle">Updates</div>
              <!--begin::Nav-->
              <div class="d-flex w-100 overflow-auto">
                <ul
                  class="nav nav-stretch  nav nav-custom nav-active-custom d-flex flex-nowrap justify-content-between my-4 px-2">
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 mx-2">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger active"
                      data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_1">
                      <span class="fs-7 fw-semibold">Recent</span>
                      <span class="fs-6 fw-bold">Orders</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 mx-2">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger"
                      data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_2">
                      <span class="fs-7 fw-semibold">Recent</span>
                      <span class="fs-6 fw-bold">Review</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 mx-2">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger"
                      data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_3">
                      <span class="fs-7 fw-semibold">Most Ordered</span>
                      <span class="fs-6 fw-bold">Item</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                </ul>
              </div>
              <!--end::Nav-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-2 px-2 card-scroll h-350px">
              <!--begin::Tab Content-->
              <div class="tab-content mb-2 px-4">
                <!--begin::Tap pane-->
                <div class="tab-pane fade  show active" id="kt_timeline_widget_3_tab_content_1">
                  <!--begin::Wrapper-->
                  @foreach ($topOrderItems as $item)
                    <div class="d-flex align-items-center pb-2 rounded border-gray-300 border-bottom-dashed my-5">
                      <!--begin::Item-->
                      <!--begin::Avatar-->
                      <div class="symbol symbol-50px me-2">
                        <img src="{{ asset($item->item->item_image) }}" class="" alt="" />
                        <span
                          class="position-absolute top-0 start-0 translate-middle badge badge-circle badge-primary badge-sm"
                          data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="Quantity">{{ $item->quantity }}</span>
                      </div>
                      <!--end::Avatar-->
                      <!--begin::Text-->
                      <div class="flex-grow-1">
                        <a href="/vendor/items/{{ $item->item->id }}"
                          class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $item->item->item_name }}<span
                            class="badge badge badge-outline badge-danger badge-sm ms-2"data-bs-custom-class="tooltip-inverse"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Order ID">#{{ $item->order->custom_id }}</span> <span
                            class="spinner-border spinner-border-sm d-none spinner_custom_{{ $item->id }}"
                            role="status" aria-hidden="true"></span>
                        </a>

                        <a href="#" class="text-muted d-block me-2 text-hover-primary"
                          data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="Order Time">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y, g:iA') }}</a>

                        <select
                          class="form-select form-select-sm rounded-start-0 fs-6 kt_select2_status order_item_{{ $item->id }}"
                          {{ $item->status == 'rejected' ? 'disabled' : '' }} name="status"
                          data-placeholder="Select Status"
                          onchange="changeStatus('{{ $item->id }}', this.value, '{{ $item->status }}')">
                          <option value="pending" data-kt-status-class="badge-warning"
                            {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                          <option value="accepted" data-kt-status-class="badge-info"
                            {{ $item->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                          <option value="completed" data-kt-status-class="badge-primary"
                            {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                          <option value="delivered" data-kt-status-class="badge-success"
                            {{ $item->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                          <option value="rejected" data-kt-status-class="badge-danger"
                            {{ $item->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                      </div>

                      <!--end::Text-->
                      <!--end::Item-->
                    </div>
                  @endforeach
                  <!--end::Wrapper-->
                </div>
                <!--end::Tap pane-->
                <!--begin::Tap pane-->
                <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_2">
                  <!--begin::Accordion-->
                  <div class="accordion" id="kt_accordion_1">
                    <!--begin::Accordion Item-->
                    @foreach ($latestRatings as $rating)
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="kt_accordion_1_header_1">
                          <button class="accordion-button fs-4 fw-semibold collapsed" type="button"
                            data-bs-toggle="collapse" aria-expanded="true"
                            data-bs-target="#kt_accordion_1_body_{{ $rating->id }}"
                            aria-controls="kt_accordion_1_body_{{ $rating->id }}">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-35px me-2">
                              <img src="{{ asset($rating->item->item_image) }}" class="" alt="" />
                            </div>
                            <!--begin::Info-->
                            <div class="flex-grow-1 me-5">
                              <!--begin::Time-->
                              <div class="text-gray-800 fw-semibold fs-5">{{ $rating->item->item_name }}<span
                                  class="badge badge badge-outline badge-danger badge-sm ms-2"data-bs-custom-class="tooltip-inverse"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  title="Order ID">#{{ $rating->order->custom_id ?? '-' }}</span></div>
                              <!--end::Time-->
                              <!--begin::Description-->
                              <div class="text-gray-700 fw-semibold fs-6">
                                <i class="fa fa-star-half-alt text-warning "></i>
                                <span class="text-gray-800 fw-bold">{{ $rating->rating }}</span>
                              </div>
                              <!--end::Description-->
                            </div>
                            <!--end::Info-->
                            <!--end::Avatar-->
                          </button>
                        </h2>
                        <div id="kt_accordion_1_body_{{ $rating->id }}" class="accordion-collapse collapse"
                          aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                          <div class="accordion-body">
                            {{ $rating->review }}
                          </div>
                        </div>
                      </div>
                    @endforeach
                    <!--end::Accordion Item-->

                  </div>
                  <!--end::Accordion-->
                </div>
                <!--end::Tap pane-->
                <!--begin::Tap pane-->
                <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_3">
                  <!--begin::Item-->
                  @foreach ($topItems as $item)
                    <div class="d-flex mb-7">
                      <!--begin::Symbol-->
                      <div class="symbol symbol-50px flex-shrink-0 me-4">
                        <img src="{{ asset($item->item->item_image) }}" class="mw-100" alt="">
                      </div>
                      <!--end::Symbol-->
                      <!--begin::Section-->
                      <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                        <!--begin::Title-->
                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                          <a href="/vendor/items/{{ $item->item->id }}"
                            class="fs-5 text-gray-800 text-hover-primary fw-bold">{{ $item->item->item_name }}</a>
                          <span class="text-gray-500 fw-semibold fs-7 my-1">
                            <i class="fa fa-star-half-alt text-warning "></i>
                            <span class="text-gray-800 fw-bold">{{ $item->item->itemRating->rating ?? 0 }}</span>
                            <span class="badge badge badge-outline badge-warning ms-2"
                              data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                              title="Price">₹{{ $item->item->price }}</span>
                          </span>
                        </div>
                        <!--end::Title-->
                        <!--begin::Info-->
                        <div class="text-end py-lg-0 py-2">
                          <span class="badge badge-success fs-8 fw-bold my-2 d-block pt-1"
                            data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Total Earnings">₹{{ $item->total_earnings }}</span>
                          <span class="badge badge-light-info fs-8 fw-bold my-2 d-block pt-1"
                            data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Total Orders">{{ $item->total_quantity }} Times</span>
                        </div>
                        <!--end::Info-->
                      </div>
                      <!--end::Section-->
                    </div>
                  @endforeach

                  <!--end::Item-->
                  <!--begin::Item-->
                </div>
                <!--end::Tap pane-->
              </div>
              <!--end::Tab Content-->
            </div>
            <!--end: Card Body-->
          </div>
          <!--end::Timeline widget 3-->
        </div>
        <!--end::Col Updates-->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.dashboard.vendor.modules.toasts.status')
@endsection
@section('scripts')
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
            url: '{{ route('orderItems.update-status') }}',
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

    // Function to fetch and render chart data based on selected duration
    function fetchAndRenderData(duration) {
      $.ajax({
        url: '/vendor/get-data-by-duration/' + duration,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          // Store initial data for re-rendering
          initialLabels = data.labels;
          initialEarnings = data.earnings;
          initialOrders = data.orders;
          initialReviews = data.reviews;
          // Render or update the chart
          renderChart(initialLabels, initialEarnings, initialOrders, initialReviews);
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

    // Function to render or update the chart using ApexCharts
    function renderChart(labels, earnings, orders, reviews) {
      var chartElement = document.querySelector(".vendor-dashboard-chart");
      var chartColor = chartElement.getAttribute("data-kt-color");
      var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
      var primaryColor = KTUtil.getCssVariableValue("--bs-" + chartColor);
      var warningColor = KTUtil.getCssVariableValue("--bs-warning");

      // Destroy previous chart instance if exists
      if (chart) {
        chart.destroy();
      }

      // Initialize new ApexCharts instance
      chart = new ApexCharts(chartElement, {
        series: [{
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
        ],
        chart: {
          fontFamily: "inherit",
          type: "bar",
          height: 200,
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
        colors: [primaryColor, KTUtil.getCssVariableValue("--bs-gray-300"), warningColor],
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
          };;
        });
      Echo.private('dashboard-states.{{ auth()->id() }}')
        .listen('DashboardStatesUpdateEvent', (e) => {
          if (e.type == "orderPlaced") {
            var selectedDuration = $("#durationSelector").val();
            fetchAndRenderData(selectedDuration);
            $.ajax({
              url: "{{ route('vendor.dashboard.stats') }}",
              method: 'GET',
              success: function(data) {
                $('#total-ordered').attr('data-kt-countup-value', data.totalOrders).text("+" +
                  data.totalOrders);
                $('#vendor-earnings').attr('data-kt-countup-value', data.totalEarning)
                  .text(
                    "₹" +
                    data.totalEarning);
              },
              error: function(xhr, status, error) {
                console.error('AJAX error:', error);
              }
            });
          }
        })
    });
  </script>
@endsection
