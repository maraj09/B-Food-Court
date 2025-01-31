@extends('layouts.admin.app')
@section('contents')
  @include('pages.pointLog.toolbar.pointLogToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <!--begin::Products-->
      <div class="card card-flush">
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Logs" />

            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <div class="border border-success border-dashed rounded py-3 px-2 mb-3">
              <div class="fs-4 fw-bold text-gray-900">
                <span class="w-50px">{{ $totalPointEarns }}</span>
              </div>
              <div class="fw-semibold text-success">Earn</div>
            </div>
            <div class="border border-danger border-dashed rounded py-3 px-3 mb-3">
              <div class="fs-4 fw-bold text-gray-900">
                <span class="w-50px">{{ $totalPointRedeems }}</span>
              </div>
              <div class="fw-semibold text-danger">Redeem</div>
            </div>
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $pointLogs->count() }}</span>
              <span class="text-muted fw-semibold fs-7">Total Logs</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <div class="table-responsive">
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
                  <th class="min-w-175px">Customer</th>
                  <th class="min-w-200px">Points Title</th>
                  <th class="min-w-175px">Points</th>
                  <th class="min-w-100px">Details</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($pointLogs as $log)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id">
                      <a id="kt_drawer_order_toggle"
                        class="text-gray-800 text-hover-primary fw-bold">#{{ $log->id }}</a>
                    </td>
                    <td class="text-center" data-order="{{ $log->created_at }}">
                      <span class="fw-bold">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</span><span
                        class="text-muted d-block fw-bold">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('g:iA') }}</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <a href="#"
                            class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $log->user->name ?? '-' }}</a>
                          <span class="text-muted d-block fw-bold"><a href="tel:{{ $log->user->phone ?? '-' }}"
                              class="text-gray-900 text-hover-primary fs-7">{{ $log->user->phone ?? '-' }}</a></span>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0">
                      <div class="symbol-group symbol-hover">
                        <div class="symbol symbol-circle symbol-35px">
                          @if ($log->action != 'Redeem')
                            <span class="text-muted d-block">Points For {{ $log->action }}</span>
                          @else
                            <span class="text-muted d-block">Points For Order</span>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td
                      data-order="{{ $log->action == 'Redeem' || $log->action == 'Penalty' ? $log->points * -1 : $log->points }}">
                      <div class="d-flex align-items-center">
                        <div class="ms-2">
                          <span
                            class="badge 
                          @if ($log->action == 'Redeem' || $log->action == 'Penalty') badge-light-danger
                          @else
                              badge-light-success @endif">
                            @if ($log->action == 'Redeem' || $log->action == 'Penalty')
                              -{{ $log->points }}
                            @else
                              +{{ $log->points }}
                            @endif
                          </span>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0">
                      @if ($log->action == 'Order')
                        <span class="fw-bold">Points added of Order Placed <a
                            href="/dashboard/orders/{{ $log->order_id ?? '-' }}" class="fw-bold text-success fs-6"
                            data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            data-bs-html="true" title="Order ID">#{{ $log->order->custom_id ?? '-' }}</a>
                        </span>
                      @elseif ($log->action == 'Redeem')
                        <span class="fw-bold">Points redeem on Order <a href="/dashboard/orders/{{ $log->order_id ?? '-' }}"
                            class="fw-bold text-danger fs-6" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                            title="Order ID">#{{ $log->order->custom_id ?? '-' }}</a></span>
                      @elseif ($log->action == 'Review')
                        <span class="fw-bold">Points added for Item reviewed <a
                            href="/dashboard/items/{{ $log->item_id ?? '-' }}" class="fw-bold text-warning fs-6"
                            data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            data-bs-html="true" title="Item ID">#{{ $log->item_id ?? '-' }}</a></span>
                      @elseif ($log->action == 'Rating')
                        <span class="fw-bold">Points added for Item rating <a
                            href="/dashboard/items/{{ $log->item_id ?? '-' }}" class="fw-bold text-warning fs-6"
                            data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            data-bs-html="true" title="Item ID">#{{ $log->item_id ?? '-' }}</a></span>
                      @else
                        <span class="fw-bold">{{ $log->details }}</span>
                      @endif
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
  @include('pages.pointLog.modules.modals.createPointLogModal')
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
                },
                {
                  orderable: !1,
                  targets: 6
                },
              ],
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
                '[data-kt-ecommerce-order-filter="customer"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(3).search(n).draw();
              });
            })(),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="action"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(4).search(n).draw();
              });
            })(),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear_all")
            .addEventListener("click", (e) => {
              // Clear date range filter
              n.clear();

              // Clear status filter
              const statusFilter = document.querySelector('[data-kt-ecommerce-order-filter="customer"]');
              statusFilter.value = 'all';
              $(statusFilter).trigger('change');
              t.column(3).search('').draw();
              // Clear status filter
              const actionFilter = document.querySelector('[data-kt-ecommerce-order-filter="action"]');
              actionFilter.value = 'all';
              $(actionFilter).trigger('change');
              t.column(4).search('').draw();

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
      KTAppEcommerceSalesListing.init();
    });
  </script>
  <script>
    var modal = new bootstrap.Modal(
      document.querySelector("#kt_modal_stacked_1")
    );
    @if ($errors->any())
      modal.show()
    @endif
  </script>
@endsection
