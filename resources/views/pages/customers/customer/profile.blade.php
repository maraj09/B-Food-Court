@extends('layouts.customer.app')
@section('contents')
  @php
    $lastSegment = request()->query('show');
  @endphp
  <div id="kt_app_content" class="app-content flex-column-fluid mt-10">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Navbar-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <div class="card mb-5 mb-xl-10 mt-20 mt-md-0">
        <div class="card-body pt-9 pb-0 bg-light-danger">
          <div class="d-flex align-items-center  rounded  mb-7">
            <!--begin::Symbol-->
            <div class="symbol symbol-50px symbol-md-100px symbol-circle flex-shrink-0 me-4">
              <img src="{{ asset(auth()->user()->customer->avatar ?? 'assets/media/svg/avatars/blank.svg') }}"
                class="mw-100" alt="">
            </div>
            <!--end::Symbol-->

            <!--begin::Title-->
            <div class="flex-grow-1 me-2">
              <a href="#" class="fw-bold text-gray-800 text-hover-primary fs-6"><i
                  class="fa-solid fa-user-tie me-2 text-danger"></i> {{ auth()->user()->name }}</a>
              <a href="tel:{{ auth()->user()->phone }}" class="text-muted fw-semibold d-block text-hover-primary "><i
                  class="fa-solid fa-phone-volume me-2 text-danger"></i> {{ auth()->user()->phone }}</a>
              <a href="mailto:{{ auth()->user()->email }}" class="text-muted fw-semibold d-block text-hover-primary "><i
                  class="fa-solid fa-at me-2 text-danger"></i>{{ auth()->user()->email }}</a>
              <span class="text-muted fw-semibold d-block text-hover-primary "><i
                  class="fa-solid fa-cake-candles me-2 text-danger"></i>{{ auth()->user()->customer->date_of_birth }}</span>
            </div>
            <!--end::Title-->

            <!--begin::Lable-->
            <span class="badge badge-warning">{{ auth()->user()->point->points }} Points</span>
            <!--end::Lable-->
          </div>
        </div>
      </div>
      <div class="mb-5 hover-scroll-x">
        <div class="d-grid">
          <ul class="nav nav-tabs flex-nowrap text-nowrap">
            <li class="nav-item">
              <a class="nav-link {{ $lastSegment == 'orders' ? 'active' : '' }} btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_1">Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $lastSegment == 'bookings' ? 'active' : '' }} btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_2">Bookings</a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ $lastSegment == 'point-logs' ? 'active' : '' }} btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_3">Point Logs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $lastSegment == 'profile' ? 'active' : '' }} btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_4">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ $lastSegment == 'tickets' ? 'active' : '' }} btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_5">Tickets</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="tab-content" id="myTabContent">
        @include('pages.customers.customer.components.profile-order')
        @include('pages.customers.customer.components.profile-point-logs')
        @include('pages.customers.customer.components.profile-settings')
        @include('pages.customers.customer.components.profile-tickets')
      </div>
    </div>
  </div>
@endsection
@section('modules')
  @include('pages.customers.customer.modules.toasts.status')
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $(document).on('click', '.order-details-drawer', function(e) {
        e.preventDefault();

        var orderId = $(this).data('order-id');
        $.ajax({
          url: '/get-order-data',
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
    "use strict";
    var KTAppEcommerceSalesListing2 = (function() {
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
          (e = document.querySelector("#kt_customers_points_logs_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [],
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
            .querySelector('[data-kt-customer-table-filter="search"]')
            .addEventListener("keyup", function(e) {
              t.search(e.target.value).draw();
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
      KTAppEcommerceSalesListing2.init();
    });
  </script>
  <script>
    $("#kt_datepicker_dob_custom").flatpickr();
  </script>
  <script>
    // JavaScript to toggle the form visibility
    $(document).ready(function() {
      $('#toggleFormBtn').click(function() {
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
    "use strict";
    var KTAppEcommerceSalesListing3 = (function() {
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
          (e = document.querySelector("#kt_customer_profile_order_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [],
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
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear")
            .addEventListener("click", (e) => {
              n.clear();
            }));
        },
      };
    })();
    KTUtil.onDOMContentLoaded(function() {
      KTAppEcommerceSalesListing3.init();
    });
  </script>
@endsection
