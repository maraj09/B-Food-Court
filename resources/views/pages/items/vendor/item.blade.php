@extends('layouts.vendor.app')
@section('contents')
  @include('pages.items.vendor.toolbar.itemToolbar')
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
              <input type="text" id="searchItems" data-kt-ecommerce-order-filter="search"
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Food Items">
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $items->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Food Items</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <div id="kt_ecommerce_sales_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
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
                    <th class="min-w-135px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table" rowspan="1"
                      colspan="1" aria-label="Food Items: activate to sort column ascending" style="width: 150.391px;">
                      Food Items</th>
                    <th class="text-center min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                      rowspan="1" colspan="1" aria-label="Price: activate to sort column ascending"
                      style="width: 92.6094px;">Price</th>
                    <th class="text-center min-w-75px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                      rowspan="1" colspan="1" aria-label="Total Orders: activate to sort column ascending"
                      style="width: 114.484px;">Total Orders</th>
                    <th class="text-center min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                      rowspan="1" colspan="1" aria-label="Total Earn: activate to sort column ascending"
                      style="width: 122.812px;">Total Earn</th>
                    <th class="text-center min-w-100px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                      rowspan="1" colspan="1" aria-label="Reviews: activate to sort column ascending"
                      style="width: 122.812px;">Reviews</th>
                    <th class="text-center min-w-100px sorting_disabled" rowspan="1" colspan="1"
                      aria-label="Added On" style="width: 124.828px;">Added On</th>
                    <th class="text-center min-w-100px sorting_disabled" rowspan="1" colspan="1"
                      aria-label="Added On" style="width: 124.828px;">Approved</th>
                    <th class="text-center min-w-50px sorting" tabindex="0" aria-controls="kt_ecommerce_sales_table"
                      rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending"
                      style="width: 69.7031px;">Actions</th>
                  </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">

                  @foreach ($items as $item)
                    <tr>
                      <td>
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                          <input class="form-check-input bulk-chkbox" type="checkbox" value="1">
                        </div>
                      </td>
                      <td data-kt-ecommerce-order-filter="order_id">
                        <div class="d-flex align-items-center">
                          <!--begin:: Avatar -->
                          <div class="symbol symbol-circle symbol-35px">
                            <a href="/vendor/items/{{ $item->id }}">
                              <div class="symbol-label fs-4 bg-light-danger text-danger">
                                @if ($item->item_image)
                                  <img src="{{ asset($item->item_image) }}" width="35px" height="35px"
                                    class="object-contain symbol symbol-circle" alt="User Avatar">
                                @else
                                  <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" width="35px"
                                    height="35px" alt="Default Avatar" class="symbol symbol-circle">
                                @endif
                              </div>
                            </a>
                          </div>

                          <!--end::Avatar-->
                          <div class="ms-2">
                            <!--begin::Title-->
                            <a href="/vendor/items/{{ $item->id }}"
                              class="text-gray-800 text-hover-primary fs-6 fw-bold d-block">{{ $item->item_name }}</a>
                            <span class="badge {{ $item->category->ribbon_color }}" data-bs-toggle="tooltip"
                              data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                              data-bs-original-title="Category"
                              data-kt-initialized="1">{{ $item->category->name }}</span>
                            <!--end::Title-->
                          </div>
                        </div>
                      </td>
                      <td class="pe-0 text-center" data-order="{{ $item->price }}">
                        <span class="fw-bold">₹{{ round($item->price) }}</span>
                      </td>
                      <td class="pe-0 text-center">
                        <span
                          class="fw-bold text-success">{{ $item->orderItems->filter(function ($orderItem) {
                                  return $orderItem->status !== 'unpaid';
                              })->count() }}
                          Times</span>
                      </td>
                      <td class="text-center"
                        data-order="{{ $item->orderItems->filter(function ($orderItem) {
                                return $orderItem->status !== 'unpaid';
                            })->sum(function ($orderItem) {
                                return $orderItem->quantity * $orderItem->price;
                            }) }}">
                        <div class="badge badge-light-success fw-bold fs-6" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                          data-bs-original-title=" Total Earn" data-kt-initialized="1">
                          ₹{{ $item->orderItems->filter(function ($orderItem) {
                                  return $orderItem->status !== 'unpaid';
                              })->sum(function ($orderItem) {
                                  return $orderItem->quantity * $orderItem->price;
                              }) }}
                        </div>
                      </td>
                      <td class="text-center">
                        <span class="text-muted d-block fw-bold"> {{ $item->ratings->count() }} Reviews </span>
                        <span class="badge badge-warning" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                          data-bs-original-title="Ratings"
                          data-kt-initialized="1">{{ $item->itemRating->rating ?? 0 }}<i
                            class="fa-regular fa-star-half-stroke text-gray-900 ms-2"></i></span>
                      </td>
                      <td class="text-center" data-order="{{ $item->created_at }}">
                        <span class="fw-bold">{{ $item->created_at->format('d-m-Y') }}</span>
                      </td>
                      <td class="text-center">
                        <span
                          class="{{ $item->approve ? 'text-success' : 'text-danger' }}">{{ $item->approve ? 'Live' : 'Not Live' }}</span>
                      </td>
                      <td class="text-end">
                        <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
                          <input class="form-check-input status-checkbox h-15px w-30px" type="checkbox" value=""
                            id="flexSwitch20x30" {{ $item->status === 1 ? 'checked' : '' }}
                            data-item-id="{{ $item->id }}">
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
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
  @include('pages.items.vendor.modules.drawers.addItem')
  <!--begin::Toast-->
  @include('pages.items.vendor.modules.toasts.status')
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
                  targets: 7
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
    var KTAddItem = (function() {
      var modal, form, submitButton, cancelButton, drawer;

      return {
        init: function() {
          modal = document.querySelector("#kt_add_item");
          drawer = KTDrawer.getInstance(modal);
          form = document.querySelector("#kt_modal_add_item_form");
          submitButton = document.querySelector("#kt_add_item_submit");
          cancelButton = document.querySelector("#kt_add_item_close");

          // Initialize FormValidation
          var fv = FormValidation.formValidation(form, {
            fields: {
              // Define your validation rules here
              item_name: {
                validators: {
                  notEmpty: {
                    message: "Name is required",
                  },
                },
              },
              price: {
                validators: {
                  notEmpty: {
                    message: "Price is required",
                  },
                },
              },
              category_id: {
                validators: {
                  notEmpty: {
                    message: "Please select a category",
                  },
                },
              },
              item_type: {
                validators: {
                  notEmpty: {
                    message: "Please select a type",
                  },
                },
              },
            },
            plugins: {
              trigger: new FormValidation.plugins.Trigger(),
              bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
                eleInvalidClass: "",
                eleValidClass: "",
              }),
            },
          });

          // Submit form with AJAX
          submitButton.addEventListener("click", function(e) {
            e.preventDefault();

            fv.validate().then(function(isValid) {
              if (isValid === "Valid") {
                // You can customize the AJAX request here
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;
                var formData = new FormData(form);

                // Append CSRF token to the headers
                formData.append(
                  "_token",
                  document.querySelector('meta[name="csrf-token"]')
                  .content
                );
                $.ajax({
                  url: "/vendor/items/store", // Update with your actual API endpoint
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    console.log(response);
                    Swal.fire({
                      text: "Form has been successfully submitted!",
                      icon: "success",
                      buttonsStyling: false,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    }).then(function(result) {
                      if (result.isConfirmed) {
                        drawer.hide();
                        form.reset(); // Reset the form after successful submission
                        location.reload();
                      }
                    });
                  },
                  error: function(error) {
                    // Registration failed, show error messages
                    console.log(error);
                    var errors = error.responseJSON.errors;
                    var errorMessage = Object.values(errors)
                      .flat()
                      .join("<br>");

                    Swal.fire({
                      html: errorMessage,
                      icon: "error",
                      buttonsStyling: !1,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    });
                  },
                  complete: function() {
                    submitButton.removeAttribute(
                      "data-kt-indicator"
                    );
                    submitButton.disabled = false;
                  },
                });
              } else {
                Swal.fire({
                  text: "Sorry, looks like there are some errors detected, please try again.",
                  icon: "error",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
              }
            });
          });

          // Reset form on cancel
          cancelButton.addEventListener("click", function(e) {
            e.preventDefault();
            form.reset();
            drawer.hide();
          });
        },
      };
    })();

    // Initialize the script on page load
    KTUtil.onDOMContentLoaded(function() {
      KTAddItem.init();
    });

    $(".status-checkbox").change(function() {
      // Get the item ID from the data attribute
      var itemId = $(this).data("item-id");

      // Save the reference to $(this) in a variable for later use
      var $checkbox = $(this);

      // Make an AJAX request to update the status
      $.ajax({
        url: "/vendor/update-item-status/" + itemId, // Replace with your actual route
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
@endsection
