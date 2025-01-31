@extends('layouts.admin.app')
@section('contents')
  @include('pages.customers.admin.toolbar.customerToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Customers">
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $customers->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Customers</span>
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
                        data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1">
                    </div>
                  </th>
                  <th class="min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Customer: activate to sort column ascending" style="width: 100px;">
                    Customer</th>
                  <th class="min-w-200px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Contact: activate to sort column ascending" style="width: 204.672px;">
                    Contact</th>
                  <th class="min-w-175px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Total Orders: activate to sort column ascending" style="width: 179.109px;">
                    Total Orders</th>
                  <th class="min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                    colspan="1" aria-label="Total Spend: activate to sort column ascending" style="width: 102.438px;">
                    Total Spend</th>
                  <th class="text-center min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Last Order: activate to sort column ascending"
                    style="width: 102.438px;">Last Order</th>
                  <th class="text-center min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Reviews: activate to sort column ascending"
                    style="width: 102.438px;">Reviews</th>
                  <th class="text-center min-w-75px sorting_disabled" rowspan="1" colspan="1" aria-label="Point"
                    style="width: 50px;">Point</th>
                  <th class="text-center min-w-75px sorting_disabled" rowspan="1" colspan="1" aria-label="Point"
                    style="width: 52px;">Status</th>
                  <th class="text-end min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                    rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending"
                    style="width: 102.5px;">Actions</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($customers as $customer)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1">
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id">
                      <a href="#" id="kt_drawer_customer_toggle" data-customer-id="{{ $customer->id }}"
                        class="text-gray-800 text-hover-primary fw-bold customer-details-drawer">{{ $customer->name }}</a>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <a href="tel:+919876543210"
                            class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $customer->phone }}</a>
                          <span class="text-muted d-block fw-bold"><a href="mailto:{{ $customer->email }}"
                              class="text-gray-900 text-hover-primary fs-7">{{ $customer->email }}</a></span>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0">
                      <span
                        class="fw-bold">{{ $customer->orders->filter(function ($order) {
                                return $order->status !== 'unpaid';
                            })->count() }}</span>
                    </td>
                    @php
                      $totalSpend = $customer->orders
                          ->filter(function ($order) {
                              return $order->status !== 'unpaid';
                          })
                          ->sum('net_amount');
                    @endphp
                    <td class="pe-0" data-order="{{ $totalSpend }}">
                      <span class="fw-bold">₹{{ $totalSpend }}</span>
                    </td>
                    <td class="text-center" data-order="{{ $customer->orders->last()->created_at ?? 0 }}">
                      <span
                        class="fw-bold">{{ $customer->orders->last()?->created_at ? \Carbon\Carbon::parse($customer->orders->last()->created_at ?? 0)->format('d/m/Y') : '-' }}
                      </span><span class="text-muted d-block fw-bold">
                        {{ $customer->orders->last()?->created_at ? \Carbon\Carbon::parse($customer->orders->last()->created_at ?? 0)->format('g:iA') : '-' }}</span>
                    </td>
                    <td class="text-center">
                      <span class="text-muted d-block fw-bold"> {{ $customer->ratings->count() }} Reviews </span>
                    </td>
                    @php
                      $RedeemedPoints = App\Models\CustomerPointLog::where('user_id', $customer->id)
                          ->where('action', 'Redeem') // Apply the scope to filter by 'Redeem' action
                          ->sum('points');

                      $PointsEarned = App\Models\CustomerPointLog::where('user_id', $customer->id)
                          ->where('action', '!=', 'Redeem')
                          ->where('action', '!=', 'Penalty') // Apply the scope to filter by 'Redeem' action
                          ->sum('points');
                    @endphp
                    <td class="text-center">
                      <!--begin::Badges-->
                      <div class="badge badge-light-warning" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title=" Total Earn">{{ $PointsEarned }}</div>
                      <!--end::Badges-->
                      <!--begin::Badges-->
                      <div class="badge badge-light-danger" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title="Redeemed">{{ $RedeemedPoints }}</div>
                      <!--end::Badges-->
                      <!--begin::Badges-->
                      <div class="badge badge-light-success" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-original-title="Balance">{{ $customer->point->points ?? 0 }}</div>
                      <!--end::Badges-->
                    </td>
                    <td class="text-center">
                      @if ($customer->phone_verified_at)
                        <span class="badge badge-success">Verified</span>
                      @else
                        <span class="badge badge-warning">Not Verified</span>
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
                          <a href="/dashboard/customers/{{ $customer->id }}" class="menu-link px-3">View</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="/dashboard/customers/{{ $customer->id }}/edit" class="menu-link px-3">Edit</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <form action="/dashboard/customers/{{ $customer->id }}/delete" method="post">
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
  @include('pages.customers.admin.modules.drawers.customerViewDrawer')
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
                l = new Date(moment($(t[5]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[5]).text(), "DD/MM/YYYY"));
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
                  targets: 7
                },
                {
                  orderable: !1,
                  targets: 9
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
            .querySelector("#kt_ecommerce_sales_flatpickr_clear_all")
            .addEventListener("click", (e) => {
              // Clear date range filter
              n.clear();

              // Clear status filter
              const statusFilter = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
              statusFilter.value = 'all';
              $(statusFilter).trigger('change');
              t.column(8).search('').draw();

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
    $(document).ready(function() {
      $(document).on('click', '.customer-details-drawer', function(e) {
        e.preventDefault();

        var customerId = $(this).data('customer-id');
        $.ajax({
          url: '/dashboard/get-customer-data',
          type: 'GET',
          data: {
            id: customerId
          },
          success: function(response) {
            $('#kt_drawer_customer').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching customer data:', xhr);
          }
        });
      });
    });
  </script>
@endsection
