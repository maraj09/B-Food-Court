@extends('layouts.vendor.app')
@section('contents')
  @include('pages.orders.vendor.toolbar.ordersToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      <div class="card card-flush mt-sm-5 mt-20">
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
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table"
              data-kt-ecommerce-order-filter="order_id" data-kt-ecommerce-order-filter="item_name">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                    </div>
                  </th>
                  <th class="min-w-75px">Order ID</th>
                  <th class="min-w-200px">Items</th>
                  <th class="min-w-100px">Total</th>
                  <th class="text-center min-w-100px">Date</th>
                  <th class="text-center min-w-100px">Status</th>
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
                      <a id="kt_drawer_order_toggle" data-order-id="{{ $order->first()->order_id }}"
                        class="text-gray-800 text-hover-primary fw-bold order-details-drawer">#{{ $order->first()->order->custom_id }}</a>
                    </td>
                    <td class="pe-0">
                      <div class="symbol-group symbol-hover">
                        @foreach ($order as $orderItem)
                          @if ($loop->index < 6)
                            <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                              data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                              title="{{ $orderItem->item->item_name }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">

                              @if ($orderItem->item->item_image)
                                <img src="{{ asset($orderItem->item->item_image) }}" alt="" />
                              @else
                                <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                              @endif
                            </div>
                          @endif
                        @endforeach
                        @if (count($order) >= 6)
                          <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                            title="View Items <span class='badge badge-primary'>{{ count($order) - 6 }}</span>">
                            <span class="symbol-label bg-dark text-gray-900 fs-8 fw-bold">+{{ count($order) - 6 }}</span>
                          </a>
                        @endif
                      </div>
                    </td>
                    <td class="pe-0"
                      data-order="{{ $order->sum(function ($item) {
                          return $item->price * $item->quantity;
                      }) }}">
                      <span
                        class="fw-bold">₹{{ $order->sum(function ($item) {
                            return $item->price * $item->quantity;
                        }) }}
                      </span>
                    </td>
                    <td class="text-center" data-order="{{ $order->first()->order->created_at }}">
                      <span
                        class="fw-bold">{{ \Carbon\Carbon::parse($order->first()->order->created_at)->format('d/m/Y') }}</span><span
                        class="text-muted d-block fw-bold">
                        {{ \Carbon\Carbon::parse($order->first()->order->created_at)->format('h:i A') }}</span>
                    </td>
                    <td class="text-center order_{{ $order->first()->order->id }}_status">
                      @if ($order->first()->order->status === 'paid')
                        <span class="badge badge-warning">Paid</span>
                      @elseif ($order->first()->order->status === 'pending')
                        <span class="badge badge-secondary">Partial Completed</span>
                      @elseif ($order->first()->order->status === 'delivered')
                        <span class="badge badge-success">Delivered</span>
                      @elseif ($order->first()->order->status === 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                      @endif
                      {{-- <a href="/vendor/orders/{{ $order->first()->order->id }}" class="btn btn-info btn-sm">Details</a> --}}
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
  @include('pages.orders.vendor.modules.toasts.status')
  @include('pages.orders.vendor.modules.drawers.showOrderDrawer')
@endsection
@section('scripts')
  <script>
    "use strict";
    var KTAppEcommerceSalesListing = (function() {
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
                l = new Date(moment($(t[4]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[4]).text(), "DD/MM/YYYY"));
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
          (e = document.querySelector("#kt_ecommerce_sales_table")) &&
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
                "all" === n && (n = ""), t.column(8).search(n).draw();
              });
            })(),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear")
            .addEventListener("click", (e) => {
              n.clear();
            }));
        },
      };
    })();
    KTUtil.onDOMContentLoaded(function() {
      KTAppEcommerceSalesListing.init();
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.order-details-drawer', function(e) {
        e.preventDefault();

        var orderId = $(this).data('order-id');
        $.ajax({
          url: '/vendor/get-order-data',
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
