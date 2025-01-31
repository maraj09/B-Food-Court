@extends('layouts.vendor.app')
@section('contents')
  @include('pages.coupons.vendor.toolbar.couponsToolbar')
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Coupons" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $coupons->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Coupons</span>
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
                  <th class="min-w-175px">Coupons Code</th>
                  <th class="text-center min-w-75px">Discount Price</th>
                  <th class="min-w-75px">Minimum Amount</th>
                  <th class="min-w-75px">Created On</th>
                  <th class="min-w-100px">Coupon Used</th>
                  <th class="text-center min-w-100px">Total Discount</th>
                  <th class="text-center min-w-200px">Approved</th>
                  <th class="text-center min-w-100px">Actions</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($coupons as $coupon)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id">
                      <a id="coupon-code-{{ $coupon->id }}"
                        class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary px-2 py-2"
                        onclick="copyCouponCode(event, '{{ $coupon->code }}')">
                        {{ $coupon->code }}
                      </a>
                      @if ($coupon->limit_type == 'global')
                        <span
                          class="text-muted d-block fs-8">{{ $coupon->limit > 0 ? 'Total' . $coupon->limit . 'can be used' : 'Infinity' }}</span>
                      @elseif ($coupon->limit_type == 'per_user')
                        <span class="text-muted d-block fs-8">{{ $coupon->limit > 0 ? $coupon->limit : 'Unlimited' }} time
                          use for customer</span>
                      @elseif ($coupon->limit_type == 'on_order')
                        <span class="text-muted d-block fs-8">For Order no: {{ $coupon->limit }} By User</span>
                      @endif
                    </td>
                    <td class="text-center" data-order="{{ $coupon->discount }}">
                      <div class="d-flex align-items-center">
                        <span class="text-danger d-block fs-8">
                          {{ $coupon->discount_type == 'fixed' ? '₹' . $coupon->discount : $coupon->discount . '%' }}
                          Flat</span>
                      </div>
                    </td>
                    <td class="pe-0 text-center">
                      <span class="fw-bold">{{ $coupon->minimum_amount }}</span>
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $coupon->created_at }}">
                      <span class="fw-bold">{{ date('d/m/Y', strtotime($coupon->created_at)) }}</span>
                    </td>
                    <td class="pe-0 text-center">
                      <!--begin::Badges-->
                      <div class="btn btn-light-info px-2 py-2 hover-elevate-up fs-7" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Coupon Used">
                        {{ $coupon->orders->filter(function ($order) {
                                return $order->status !== 'unpaid';
                            })->count() }}
                        Times</div>
                      <!--end::Badges-->
                    </td>
                    <td class="text-center"
                      data-order="{{ $coupon->orders->filter(function ($order) {
                              return $order->status !== 'unpaid';
                          })->sum(function ($order) {
                              return $order->coupon_discount;
                          }) }}">
                      <span
                        class="fw-bold text-success">₹{{ $coupon->orders->filter(function ($order) {
                                return $order->status !== 'unpaid';
                            })->sum(function ($order) {
                                return $order->coupon_discount;
                            }) }}</span>
                    </td>
                    <td class="text-center">
                      <span
                        class="{{ $coupon->approved ? 'text-success' : 'text-danger' }}">{{ $coupon->approved ? 'Live' : 'Not Live' }}</span>
                    </td>
                    <td class="text-end">
                      <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
                        <input class="form-check-input coupon-checkbox" type="checkbox" value=""
                          {{ $coupon->status == 1 ? 'checked' : '' }} data-coupon-id="{{ $coupon->id }}" />
                        <a href="/vendor/coupons/{{ $coupon->id }}/edit" class="mx-3 ms-5">Edit</a>
                        <form action="/vendor/coupons/{{ $coupon->id }}/delete" method="post" id="coupon_delete_form">
                          @csrf
                          @method('delete')
                        </form>
                        <a href="javascript:void(0)" class="mx-3 text-danger"
                          onclick="submitParentForm(this, '#coupon_delete_form')">Delete</a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <!--end::Table-->
          </div>
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Products-->
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
@endsection
@section('modules')
  @include('pages.coupons.admin.modules.toasts.status')
  <!--end::Toast-->
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
                  targets: 8
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
      $('.coupon-checkbox').change(function() {
        var status = $(this).prop('checked') ? 1 : 0;
        var couponId = $(this).data('coupon-id');
        $.ajax({
          url: '{{ route('vendor.coupons.toggleStatus') }}',
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
    function copyCouponCode(event, code) {
      event.preventDefault();

      // Create a temporary input element
      var tempInput = document.createElement('input');
      tempInput.value = code;
      document.body.appendChild(tempInput);

      // Select the input element
      tempInput.select();
      tempInput.setSelectionRange(0, 99999); // For mobile devices

      // Copy the text to the clipboard
      document.execCommand('copy');

      // Remove the temporary input element
      document.body.removeChild(tempInput);

      // Optionally, you can display a message to the user
      showToast('success', 'Coupon code copied to clipboard: ' + code);
    }
  </script>
@endsection
