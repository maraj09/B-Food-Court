@extends('layouts.admin.app')
@section('contents')
  @include('pages.vendors.admin.toolbar.vendorToolbar')
  <style>
    .iti {
      width: 100%;
      display: block;
    }

    .iti__country-name {
      color: #000;
    }

    .iti__search-input {
      background: white;
      color: #000;

    }
  </style>
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Vendors">
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $vendors->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Vendors</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
              id="kt_ecommerce_sales_table">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" aria-label=""
                    style="width: 29.8906px;">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_ecommerce_sales_table .bulk-chkbox" value="1">
                    </div>
                  </th>
                  <th class="min-w-175px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Vendors: activate to sort column ascending" style="width: 236.359px;">
                    Vendors</th>
                  <th class="min-w-175px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Contact: activate to sort column ascending" style="width: 175px;">Contact
                  </th>
                  <th class="text-center min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Total Orders: activate to sort column ascending"
                    style="width: 86.8594px;">Total Orders</th>
                  <th class="min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Menu Items: activate to sort column ascending" style="width: 77.1094px;">
                    Menu Items</th>
                  <th class="text-center min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Joined On: activate to sort column ascending"
                    style="width: 100px;">Joined On</th>
                  <th class="text-center min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Reviews: activate to sort column ascending"
                    style="width: 100px;">Reviews</th>
                  <th class="text-center min-w-200px sorting_disabled" rowspan="1" colspan="1" aria-label="Earnings"
                    style="width: 200px;">Earnings</th>
                  <th class="text-end min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending"
                    style="width: 100.031px;">Actions</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">


                @foreach ($vendors as $vendor)
                  <tr class="odd">
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input bulk-chkbox" type="checkbox" value="1">
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id">
                      <div class="d-flex align-items-center">
                        <!--begin:: Avatar -->
                        <div class="symbol symbol-circle symbol-35px">
                          <a href="/dashboard/vendors/{{ $vendor->id }}">
                            <div class="symbol-label fs-4 bg-light-danger text-danger">
                              @if ($vendor->vendor->avatar)
                                <img src="{{ asset($vendor->vendor->avatar) }}" width="35px" height="35px"
                                  class="object-contain symbol symbol-circle" alt="User Avatar">
                              @else
                                <img src="{{ asset('assets/media/svg/avatars/blank-dark.svg') }}" width="35px"
                                  height="35px" alt="Default Avatar" class="symbol symbol-circle">
                              @endif
                            </div>
                          </a>
                        </div>
                        <!--end::Avatar-->
                        <div class="ms-2">
                          <!--begin::Title-->
                          <a href="/dashboard/vendors/{{ $vendor->id }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $vendor->vendor->brand_name }}</a>
                          <!--end::Title-->
                          @if ($vendor->vendor->stall_no)
                            <br>
                            <span class="badge badge badge-outline badge-warning">Stall -
                              {{ $vendor->vendor->stall_no }}</span>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <a href="tel:{{ $vendor->phone }}"
                            class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $vendor->phone }}</a>
                          <span class="text-muted d-block fw-bold"><a href="mailto:{{ $vendor->email }}"
                              class="text-gray-600 text-hover-primary fs-8">{{ $vendor->email }}</a></span>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0 text-center">
                      <span
                        class="fw-bold">{{ $vendor->vendor->items->flatMap->orderItems->filter(function ($orderItem) {
                                return $orderItem->status !== 'unpaid';
                            })->count() }}</span>
                    </td>
                    <td class="pe-0 text-center">
                      <!--begin::Badges-->
                      <div class="btn btn-light-info px-2 py-2 hover-elevate-up fs-7" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title="View Item">
                        {{ $vendor->vendor->items->count() }}</div>
                      <!--end::Badges-->
                    </td>
                    <td class="text-center" data-order="{{ $vendor->created_at }}">
                      <span class="fw-bold">{{ $vendor->created_at->format('Y-m-d') }}</span>
                    </td>
                    <td class="text-center">
                      @php
                        $totalReviews = 0;
                        foreach ($vendor->vendor->items as $item) {
                            $totalReviews += $item->ratings->count();
                        }
                      @endphp
                      <span class="text-muted d-block fw-bold">
                        {{ $totalReviews }}</span>
                      <span class="badge badge-warning" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                        data-bs-placement="top"
                        data-bs-original-title="Ratings">{{ $vendor->vendor->vendorRating->rating ?? 0 }}<i
                          class="fa-regular fa-star-half-stroke text-gray-900 ms-2"></i></span>
                    </td>
                    <td class="text-center" data-order="{{ $vendor->vendor->vendorBank->balance + $vendor->vendor->vendorBank->amount_paid }}">
                      <!--begin::Badges-->
                      <div class="badge badge-light-success" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title="Total Earn">
                        {{ $vendor->vendor->vendorBank->balance + $vendor->vendor->vendorBank->amount_paid }}</div>
                      <!--end::Badges-->
                      <!--begin::Badges-->
                      <div class="badge badge-light-danger" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title="Withdrawal">
                        {{ $vendor->vendor->vendorBank->amount_paid }}</div>
                      <!--end::Badges-->
                      <!--begin::Badges-->
                      <div class="badge badge-light-warning" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title="Balance">
                        {{ $vendor->vendor->vendorBank->balance }}</div>
                      <!--end::Badges-->
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
                          <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
                            <span class="ms-2 me-5">
                              Status
                            </span>
                            <input class="form-check-input vendor-status-checkbox h-15px w-30px" type="checkbox"
                              value="" id="flexSwitch20x30" {{ $vendor->vendor->approve == 1 ? 'checked' : '' }}
                              data-vendor-id="{{ $vendor->vendor->id }}">
                          </div>
                        </div>
                        <div class="menu-item px-3">
                          <a href="{{ url('/dashboard/vendors', $vendor->id) }}" class="menu-link px-3">View</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="/dashboard/vendors/{{ $vendor->id }}/edit" class="menu-link px-3">Edit</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <form action="/dashboard/vendors/{{ $vendor->id }}/delete" method="post">
                            @csrf
                            @method('delete')
                            <a href="javascript:void(0)" class="menu-link px-3"
                              onclick="submitParentForm(this)">Delete</a>
                          </form>
                        </div>
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
  @include('pages.vendors.admin.modules.drawers.addVendor')
  @include('pages.vendors.admin.modules.toasts.status')
@endsection
@section('scripts')
  <script>
    const input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
      utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
      separateDialCode: true,
      initialCountry: "auto",
      onlyCountries: ["bd", "in"],
      initialCountry: "bd",
    });
  </script>
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
                l = new Date(moment($(t[5]).text(), "YYYY-MM-DD")),
                u = new Date(moment($(t[5]).text(), "YYYY-MM-DD"));
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
    $(".vendor-status-checkbox").change(function() {
      // Get the item ID from the data attribute
      var vendor = $(this).data("vendor-id");

      // Save the reference to $(this) in a variable for later use
      var $checkbox = $(this);

      // Make an AJAX request to update the status
      $.ajax({
        url: "/dashboard/update-vendor-status/" + vendor, // Replace with your actual route
        type: "POST", // You can use 'PUT' or 'PATCH' depending on your setup
        data: {
          // Additional data to send, if any
        },
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function(response) {
          showToast(response);
        },
        error: function(error) {
          console.error(error);
          showToast("error");
        },
      });
    });
  </script>
  <script>
    var quill = new Quill('#kt_docs_quill_basic', {
      modules: {
        toolbar: true
      },
      placeholder: 'Type your text here...',
      theme: 'snow' // or 'bubble'
    });
    quill.on('text-change', function(delta, oldDelta, source) {
      document.getElementById("quill_html").value = quill.root.innerHTML;
    });
  </script>
@endsection
