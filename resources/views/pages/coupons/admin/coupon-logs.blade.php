@extends('layouts.admin.app')
@section('contents')
  @include('pages.coupons.admin.toolbar.coupon-logs-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Coupon" />

            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <div class="card-title d-flex flex-wrap flex-center text-center">
              <!--begin::Stats-->
              <div class="border border-danger border-dashed rounded py-3 px-3 mx-2 mb-3">
                <div class="fs-4 fw-bold text-gray-900">
                  <span class="w-50px">{{ $orders->sum('coupon_discount') }}</span>
                </div>
                <div class="fw-semibold text-danger">Redeem</div>
              </div>
              <!--end::Stats-->
              <!--begin::Stats-->
              <div class="rounded py-3 px-2 mb-3">
                <div class="fs-4 fw-bold text-gray-900">
                  <span class="w-50px">{{ $orders->count() }}</span>
                </div>
                <div class="fw-semibold">Total Logs</div>
              </div>
              <!--end::Stats-->
            </div>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                  </div>
                </th>
                <th class="min-w-75px">ID</th>
                <th class="text-center min-w-100px">Date</th>
                <th class="min-w-175px">Coupon</th>
                <th class="min-w-175px">Used By</th>
                <th class="min-w-175px">Order ID</th>
                <th class="min-w-100px">Discount Given</th>
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
                  <td data-order="{{ $loop->index + 1 }}">
                    <a id="kt_drawer_order_toggle"
                      class="text-gray-800 text-hover-primary fw-bold">#{{ $loop->index + 1 }}</a>
                  </td>
                  <td class="text-center" data-order="{{ $order->created_at }}">
                    <span class="fw-bold">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span><span
                      class="text-muted d-block fw-bold">
                      {{ \Carbon\Carbon::parse($order->created_at)->format('g:iA') }}</span>
                  </td>
                  <td class="pe-0">
                    <div class="symbol-group symbol-hover">
                      <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                        title="Flat {{ $order->coupon->discount_type == 'fixed' ? '₹' . round($order->coupon->discount) : round($order->coupon->discount) . '%' }}
                        Off">
                        <span class="d-block badge badge-light-primary">{{ $order->coupon->code }}</span>
                      </div>
                    </div>
                  </td>
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
                  <td class="pe-0" data-order="{{ $order->custom_id }}">
                    <div class="symbol-group symbol-hover">
                      <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                        title="₹{{ $order->net_amount }} <span class='badge badge-primary'>{{ $order->orderItems->count() }}</span>">
                        <span class="text-primary d-block">#{{ $order->custom_id }}</span>
                        <span class="text-muted">Amount: ₹{{ $order->net_amount }}</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center" data-order="{{ $order->coupon_discount }}">
                      <div class="ms-2">
                        <span class="badge badge-light-success">+{{ round($order->coupon_discount) }}</span>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <!--end::Table-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Products-->
    </div>
    <!--end::Content container-->
  </div>
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
                l = new Date(moment($(t[2]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[2]).text(), "DD/MM/YYYY"));
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
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="customer"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(4).search(n).draw();
              });
            })(),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="coupon"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(3).search(n).draw();
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
@endsection
