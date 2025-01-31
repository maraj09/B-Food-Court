@extends('layouts.vendor.app')
@section('contents')
  @include('pages.payout.vendor.toolbar.payoutsToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Payouts" />

            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $payouts->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Payouts</span>
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
                  <th class="text-center min-w-75px">Amount</th>
                  <th class="text-center min-w-175px">Transaction ID</th>
                  <th class="text-center min-w-75px">Date</th>
                  <th class="text-center min-w-100px">Status</th>
                  <th class="text-center min-w-100px">Payment Image</th>
                  <th class="text-center min-w-100px">Remark</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($payouts as $payout)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $payout->request_amount }}">
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <span class="badge badge-danger fw-bold fs-6">₹{{ $payout->request_amount }}</span>
                          <div class="fs-6 text-gray-800 d-block" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Request Date">
                            {{ \Carbon\Carbon::parse($payout->created_at)->format('d-m-Y') }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $payout->payment_mode }}">
                      <span class="fw-bold">{{ $payout->transaction_id ? $payout->transaction_id : '-' }}</span>
                      @if ($payout->payment_mode == 'upi')
                        <div class="badge badge-light-primary fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">UPI</div>
                      @elseif ($payout->payment_mode == 'cash')
                        <div class="badge badge-light-success fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Cash</div>
                      @elseif ($payout->payment_mode == 'cheque')
                        <div class="badge badge-light-warning fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Cheque
                        </div>
                      @elseif ($payout->payment_mode == 'bank_transfer')
                        <div class="badge badge-light-info fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Bank
                          Transfer
                        </div>
                      @else
                        <div>
                          -
                        </div>
                      @endif
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $payout->date }}">
                      <span class="fw-bold text-success">{{ $payout->date }}</span>
                    </td>
                    <td class="text-center">
                      @if ($payout->status == 'transferred')
                        <div class="badge badge-light-success fw-bold fs-6">Transferred</div>
                      @elseif ($payout->status == 'hold')
                        <div class="badge badge-light-danger fw-bold fs-6">On Hold</div>
                      @elseif ($payout->status == 'progress')
                        <div class="badge badge-light-primary fw-bold fs-6">In Progress</div>
                      @else
                        <div class="badge badge-light-warning fw-bold fs-6">Pending</div>
                      @endif
                    </td>
                    <td class="text-center">
                      @if ($payout->payment_image)
                        <div class="symbol symbol-50px symbol-2by3">
                          <img src="{{ asset($payout->payment_image) }}" alt="" />
                        </div>
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="fs-8">{{ $payout->remark ? $payout->remark : '-' }}</span>
                    </td>
                    {{-- <td class="text-center">
                    <button class="btn btn-active-info" data-payout-id="{{ $payout->id }}"
                      data-amount="{{ $payout->request_amount }}" data-remark="{{ $payout->remark }}"
                      data-image="{{ $payout->payment_image }}" data-payout-id="{{ $payout->id }}"
                      id="kt_add_payout_toggle">
                      Edit</i>
                    </button>
                  </td> --}}
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
  @include('pages.payout.vendor.modules.drawers.addPayoutDrawer')
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
                },
                {
                  orderable: !1,
                  targets: 5
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
                '[data-kt-ecommerce-order-filter="status"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(3).search(n).draw();
              });
            })(),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="vendor"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(1).search(n).draw();
              });
            })(),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="condition"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(5).search(n).draw();
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
    // Get a reference to the form
    var form = document.getElementById('payout_form');

    // Get a reference to the button
    var button = document.querySelector('#payout_form_submit_btn');

    // Add event listener to the button
    button.addEventListener('click', function(event) {
      // Prevent the default form submission behavior
      event.preventDefault();
      button.setAttribute("data-kt-indicator", "on");
      button.disabled = true;

      // Submit the form using JavaScript
      form.submit();
    });

    var KTAddItem = (function() {
      var modal, drawer;

      return {
        init: function() {
          modal = document.querySelector("#kt_add_payout");
          drawer = KTDrawer.getInstance(modal);
          @if ($errors->any())
            drawer.show()
          @endif
        },
      };
    })();
  </script>

  <script>
    KTUtil.onDOMContentLoaded(function() {
      KTAddItem.init();
    });
  </script>
@endsection
