@extends('layouts.admin.app')
@section('contents')
  @include('pages.orders.admin.toolbar.ordersToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      <!--begin::Products-->
      <div class="card card-flush ">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-ecommerce-order-filter="search"
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Order" name="search"
                id="searchInput" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $orders->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Orders</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_orders_table">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_orders_table .form-check-input" value="1" />
                    </div>
                  </th>
                  <th class="min-w-75px">Order ID</th>
                  @can('orders-management')
                    <th class="min-w-175px">Customer</th>
                  @endcan
                  <th class="min-w-200px">Products</th>
                  <th class="min-w-175px">Status</th>
                  <th class="min-w-100px">Total</th>
                  <th class="text-center min-w-100px">Date</th>
                  <th class="text-center min-w-100px">Payment Mode</th>
                  <th class="text-end min-w-100px">Actions</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($orders as $order)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id">
                      <a id="kt_drawer_order_toggle" data-order-id="{{ $order->id }}"
                        class="text-gray-800 text-hover-primary fw-bold order-details-drawer">#{{ $order->custom_id }}</a>
                    </td>
                    @can('orders-management')
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                            <a href="#"
                              class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $order->user->name }}</a>
                            <span class="text-muted d-block fw-bold"><a href="tel:{{ $order->user->phone }}"
                                class="text-gray-900 text-hover-primary fs-7">{{ $order->user->phone }}</a></span>
                          </div>
                        </div>
                      </td>
                    @endcan
                    <td class="pe-0">
                      <div class="symbol-group symbol-hover">
                        @foreach ($order->orderItems as $orderItem)
                          @if ($loop->index < 3)
                            @if ($orderItem->item_id)
                              <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                                title="{{ $orderItem->item->item_name }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">
                                @if ($orderItem->item->item_image)
                                  <img src="{{ asset($orderItem->item->item_image) }}" alt="" />
                                @else
                                  <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                                @endif
                              </div>
                            @elseif ($orderItem->event_id)
                              <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                                title="{{ $orderItem->event->title }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">
                                @if ($orderItem->event->image)
                                  <img src="{{ asset($orderItem->event->image) }}" alt="" />
                                @else
                                  <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                                @endif
                              </div>
                            @elseif ($orderItem->play_area_id)
                              <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                                title="{{ $orderItem->playArea->title }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">
                                @if ($orderItem->playArea->image)
                                  <img src="{{ asset($orderItem->playArea->image) }}" alt="" />
                                @else
                                  <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                                @endif
                              </div>
                            @endif
                          @endif
                        @endforeach
                        @if (count($order->orderItems) > 3)
                          <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                            title="View Items <span class='badge badge-primary'>{{ count($order->orderItems) - 3 }}</span>">
                            <span
                              class="symbol-label bg-dark text-gray-900 fs-8 fw-bold">+{{ count($order->orderItems) - 3 }}</span>
                          </a>
                        @endif
                      </div>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="ms-2 order_{{ $order->id }}_status">
                          <!--begin::Title-->
                          @if ($order->status === 'paid')
                            <span class="badge badge-warning">Paid</span>
                          @elseif ($order->status === 'pending')
                            <span class="badge badge-secondary">Partial Completed</span>
                          @elseif ($order->status === 'delivered')
                            <span class="badge badge-success">Delivered</span>
                          @elseif ($order->status === 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                          @endif
                          <!--end::Title-->
                        </div>
                      </div>
                    </td>
                    <td class="pe-0" data-order="{{ $order->net_amount }}">
                      <span class="fw-bold">₹{{ $order->net_amount }}</span>
                    </td>
                    <td class="text-center" data-order="{{ $order->created_at }}">
                      <span class="fw-bold">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span><span
                        class="text-muted d-block fw-bold">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</span>
                    </td>
                    <td class="text-center" data-order="{{ $order->payment_method }}">
                      @if ($order->payment_method === 'upi')
                        <span class="badge badge-primary">UPI</span>
                      @elseif ($order->payment_method === 'cash')
                        <span class="badge badge-success">Cash</span>
                      @elseif ($order->payment_method === 'card')
                        <span class="badge badge-info">Card</span>
                      @else
                        <span class="badge badge-warning">{{ strtoupper($order->payment_method) }}</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                      <!--begin::Menu-->
                      <div
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="/dashboard/orders/{{ $order->id }}" id="kt_drawer_order_toggle"
                            data-order-id="{{ $order->id }}" class="menu-link px-3 order-details-drawer">View</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        @can('orders-management')
                          <div class="menu-item px-3">
                            <form action="/dashboard/orders/{{ $order->id }}/delete" method="post">
                              @csrf
                              @method('delete')
                              <a href="javascript:void(0)" class="menu-link px-3"
                                onclick="submitParentForm(this)">Delete</a>
                            </form>
                          </div>
                        @endcan
                        <!--end::Menu item-->
                      </div>
                      <!--end::Menu-->
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!--end::Table-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Products-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  <div id="kt_drawer_order" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat"
    data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_drawer_order_toggle" data-kt-drawer-close="#kt_drawer_order_close">
    <!--begin::Messenger-->

    <!--end::Messenger-->
  </div>
  @include('pages.items.admin.modules.toasts.status')
@endsection
@section('scripts')
  <script>
    "use strict";
    var KTAppOrdersListing = (function() {
      var e,
        t,
        n,
        r,
        o,
        a = (e, n, a) => {
          (r = e[0] ? new Date(e[0]) : null),
          (o = e[1] ? new Date(e[1]) : null),
          $.fn.dataTable.ext.search.push(function(e, t, n) {
              var a = r,
                c = o,
                l = new Date(moment($(t[6]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[6]).text(), "DD/MM/YYYY"));
              return (
                (null === a && null === c) ||
                (null === a && c >= u) ||
                (a <= l && null === c) ||
                (a <= l && c >= u)
              );
            }),
            t.draw();
        };
      return {
        init: function() {
          (e = document.querySelector("#kt_orders_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [{
                orderable: !1,
                targets: 0
              }, ],
            })).on("draw", function() {

            }),
            (() => {
              const e = document.querySelector("#kt_ecommerce_sales_flatpickr");
              n = $(e).flatpickr({
                altInput: !0,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                onChange: function(e, t, n) {
                  a(e, t, n);
                },
              });
            })(),
            document
            .querySelector('[data-kt-ecommerce-order-filter="search"]')
            .addEventListener("keyup", function(e) {
              t.search(e.target.value).draw();
            }),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="status"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(7).search(n).draw();
              });
            })(),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear_all")
            .addEventListener("click", (e) => {
              // Clear date range filter
              n.clear();

              // Clear status filter
              const statusFilter = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
              statusFilter.value = 'all';
              $(statusFilter).trigger('change');
              t.column(7).search('').draw();

              // Remove any custom search function
              $.fn.dataTable.ext.search = [];
              t.draw();
            }),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear")
            .addEventListener("click", (e) => {
              n.clear();
            }));
        },
      };
    })();
    KTUtil.onDOMContentLoaded(function() {
      KTAppOrdersListing.init();
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.order-details-drawer', function(e) {
        e.preventDefault();

        var orderId = $(this).data('order-id');
        $.ajax({
          url: '/dashboard/get-order-data',
          type: 'GET',
          data: {
            id: orderId
          },
          success: function(response) {
            $('#kt_drawer_order').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching Order data:', xhr);
          }
        });
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Echo.channel('orders')
        .listen('OrderStatusChangeEvent', (e) => {
          var statusBadge = '';
          switch (e.order.status) {
            case 'delivered':
              statusBadge = '<span class="badge badge-success">Delivered</span>';
              break;
            case 'rejected':
              statusBadge = '<span class="badge badge-danger">Rejected</span>';
              break;
            default:
              statusBadge = '<span class="badge badge-secondary">Partial Completed</span>';
          }
          $(`.order_${e.order.id}_status`).html(statusBadge);
        });
    });
  </script>
@endsection
