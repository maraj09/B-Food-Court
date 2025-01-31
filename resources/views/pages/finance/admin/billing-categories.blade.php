@extends('layouts.admin.app')
@section('contents')
  @include('pages.finance.admin.toolbars.billing-categories-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Categories" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $billingCategories->count() }}</span>
              <span class="text-muted fw-semibold fs-7">Total Categories</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_billing_categories_table">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                  </div>
                </th>
                <th class="text-center min-w-175px">Name</th>
                <th class="text-center min-w-200px">Brand Logo</th>
                <th class="text-center min-w-75px">GST No</th>
                <th class="text-center min-w-125px">Address</th>
                <th class="text-end min-w-100px">Actions</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
              @foreach ($billingCategories as $billingCategory)
                <tr>
                  <td class="text-center">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                      <input class="form-check-input" type="checkbox" value="1" />
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge {{ $billingCategory->color_class }}">{{ $billingCategory->name }}</span>
                  </td>
                  <td class="text-center">
                    @if ($billingCategory->logo)
                      <div class="symbol symbol-50px symbol-2by3">
                        <img src="{{ asset($billingCategory->logo) }}" alt="" />
                      </div>
                    @else
                      -
                    @endif
                  </td>
                  <td class="pe-0 text-center">
                    <span class="fw-bold">{{ $billingCategory->gst_no }}</span>
                  </td>
                  <td class="pe-0 text-center">
                    <span class="fw-bold">{{ $billingCategory->address }}</span>
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
                        <a href="" class="menu-link px-3 kt_drawer_billing_category_edit_button"
                          data-category-id="{{ $billingCategory->id }}">View</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <a href="" class="menu-link px-3 kt_drawer_billing_category_edit_button"
                          data-category-id="{{ $billingCategory->id }}">Edit</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <form action="/dashboard/finance/billing-categories/{{ $billingCategory->id }}/delete" method="post">
                          @csrf
                          @method('delete')
                          <a href="javascript:void(0)" class="menu-link px-3" onclick="submitParentForm(this)">Delete</a>
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
  @include('pages.finance.admin.modules.drawers.create-billing-categories-drawer')
  @include('pages.finance.admin.modules.drawers.edit-billing-categories-drawer')
@endsection
@section('scripts')
  <script>
    @if ($errors->any())
      document.addEventListener('DOMContentLoaded', function() {
        // Trigger the drawer to open on page load
        const cartDrawer = document.getElementById('kt_drawer_billing_category');
        if (cartDrawer) {
          // Check if the cart drawer element exists
          const drawer = KTDrawer.getInstance(cartDrawer);

          drawer.show(); // Open the drawer
        }
      });
    @endif
  </script>
  <script>
    "use strict";
    var KTAppEcommerceSalesListing = (function() {
      var e, t, n, r, o;

      var filterDateRange = (e, n, a) => {
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
          e = document.querySelector("#kt_billing_categories_table");
          if (e) {
            t = $(e).DataTable({
              info: false,
              order: [],
              pageLength: 10,
              columnDefs: [{
                  orderable: false,
                  targets: 0
                },
                {
                  orderable: false,
                  targets: 2
                },
                {
                  orderable: false,
                  targets: 5
                },
              ],
            });
            // Search filter
            document
              .querySelector('[data-kt-ecommerce-order-filter="search"]')
              .addEventListener("keyup", function(e) {
                t.search(e.target.value).draw();
              });
          }
        }
      };
    })();

    KTUtil.onDOMContentLoaded(function() {
      KTAppEcommerceSalesListing.init();
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.kt_drawer_billing_category_edit_button', function(e) {
        e.preventDefault();

        var categoryId = $(this).data('category-id');
        $.ajax({
          url: '/dashboard/get-billing-category-data',
          type: 'GET',
          data: {
            id: categoryId
          },
          success: function(response) {
            $('#kt_drawer_billing_category_edit').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching Order data:', xhr);
          }
        });
      });
    });
  </script>
@endsection
