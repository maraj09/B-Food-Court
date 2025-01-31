@extends('layouts.admin.app')
@section('contents')
  @include('pages.event.admin.toolbars.event-toolbar')
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Event" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $events->count() }}</span>
              <span class="text-muted fw-semibold fs-7">Total Events</span>
            </h3>
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
                      data-kt-check-target="#kt_ecommerce_sales_table .form-check-input-first" value="1" />
                  </div>
                </th>
                <th class="min-w-175px">Event</th>
                <th class="text-center min-w-175px">Date</th>
                <th class="text-center min-w-100px">Duration</th>
                <th class="text-center min-w-75px">Price</th>
                <th class="text-center min-w-100px">Max Seat</th>
                <th class="text-center min-w-75px">Booked</th>
                <th class="text-center min-w-100px">Status</th>
                <th class="text-center min-w-50px">Actions</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
              @foreach ($events as $event)
                <tr>
                  <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                      <input class="form-check-input form-check-input-first" type="checkbox" value="1" />
                    </div>
                  </td>
                  <td data-kt-ecommerce-order-filter="order_id">
                    <div class="d-flex align-items-center">
                      <!--begin:: Avatar -->
                      <div class="symbol symbol-circle symbol-35px overflow-hidden">
                        {{-- <a href="#" id="kt_add_event_toggle">
                          <div class="symbol-label fs-4 bg-light-danger text-danger">{{ getInitials($event->title) }}
                          </div>
                        </a> --}}
                        <img src="{{ asset($event->image) }}" alt="">
                      </div>
                      <!--end::Avatar-->
                      <div class="ms-2">
                        <!--begin::Title-->
                        <a href="#" id="kt_event_toggle"
                          class="text-gray-800 text-hover-primary fs-6 fw-bold d-block kt_edit_event_toggle"
                          data-event-id="{{ $event->id }}">{{ $event->title }}</a>
                        <!--end::Title-->
                      </div>
                    </div>
                  </td>
                  <td data-order="{{ $event->start_date }}">
                    <div class="d-flex text-center">
                      <div class="flex-grow-1">
                        <span
                          class="text-gray-900 d-block fs-6">{{ \Carbon\Carbon::parse($event->start_date)->format('d-m-Y') }}</span>
                        <span
                          class="text-muted d-block fs-6">{{ \Carbon\Carbon::parse($event->start_date)->format('g:iA') }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="text-center" data-order="{{ durationCalculation($event->start_date, $event->end_date) }}">
                    <div class="badge badge-light-info fw-bold fs-6">
                      {{ durationCalculation($event->start_date, $event->end_date) }}</div>
                  </td>
                  <td class="text-center">
                    <span class="text-success d-block fw-bold"
                      data-order="{{ $event->price }}">₹{{ $event->price }}</span>
                  </td>
                  <td class="text-center">
                    <span class="text-muted d-block fw-bold" data-order="{{ $event->seats }}">{{ $event->seats }}
                      Seats</span>
                  </td>
                  <td class="text-center">
                    <span class="text-warning d-block fw-bold kt_event_bookings_toggle"
                      data-event-id="{{ $event->id }}">{{ $event->orderEvents()->whereNot('status', 'unpaid')->count() }}/{{ $event->seats }}</span>
                  </td>
                  <td class="align-center">
                    <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
                      <input class="form-check-input mx-auto h-15px w-30px area-status-checkbox" type="checkbox"
                        value="" id="flexSwitch20x30" {{ $event->status === 1 ? 'checked' : '' }}
                        data-event-id="{{ $event->id }}" />
                    </div>
                  </td>
                  <td class="text-end">
                    <i class="fa-solid fa-eye text-primary fs-2x mx-2 kt_edit_event_toggle cursor-pointer"
                      data-event-id="{{ $event->id }}"></i><i
                      class="fa-solid fa-pen-to-square text-warning fs-2x kt_edit_event_toggle cursor-pointer"
                      data-event-id="{{ $event->id }}"></i>
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
  @include('pages.event.admin.modules.drawers.create-event-drawer')
  @include('pages.event.admin.modules.drawers.edit-event-drawer')
  @include('pages.items.admin.modules.toasts.status')
  @include('pages.event.admin.modules.drawers.event-booking-drawer')
@endsection
@section('scripts')
  <script>
    $(".kt_datepicker_with_time").flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i",
    });
  </script>
  <script>
    var KTAddEvent = (function() {
      var form, submitButton, cancelButton, drawer;
      return {
        init: function() {
          drawer = KTDrawer.getInstance(document.querySelector("#kt_create_event"));
          form = document.querySelector("#kt_modal_add_event_form");
          submitButton = document.querySelector("#kt_add_event_submit");
          cancelButton = document.querySelector("#kt_create_event_close");

          // Initialize FormValidation
          var fv = FormValidation.formValidation(form, {
            fields: {
              'image': {
                validators: {
                  notEmpty: {
                    message: 'Event Image is required',
                  },
                  file: {
                    extension: 'png,jpg,jpeg',
                    type: 'image/png,image/jpeg',
                    message: 'Please choose a valid image file',
                  },
                },
              },
              'title': {
                validators: {
                  notEmpty: {
                    message: 'Event name is required',
                  },
                },
              },
              'price': {
                validators: {
                  notEmpty: {
                    message: 'Price is required',
                  },
                  integer: {
                    message: 'Price must be an integer',
                    thousandsSeparator: '',
                    decimalSeparator: '.',
                  },
                },
              },
              'start_date': {
                validators: {
                  notEmpty: {
                    message: 'Start Date is required',
                  },
                  date: {
                    format: 'YYYY-MM-DD HH:mm',
                    message: 'The date format should be YYYY-MM-DD HH:mm',
                  },
                },
              },
              'end_date': {
                validators: {
                  notEmpty: {
                    message: 'End Date is required',
                  },
                  date: {
                    format: 'YYYY-MM-DD HH:mm',
                    message: 'The date format should be YYYY-MM-DD HH:mm',
                  },
                  callback: {
                    message: 'End Date cannot be before Start Date',
                    callback: function(input) {
                      var startDate = form.querySelector('[name="start_date"]').value;
                      var endDate = input.value;
                      if (startDate && endDate) {
                        return new Date(endDate) >= new Date(startDate);
                      }
                      return true;
                    }
                  }
                },
              },
              'seats': {
                validators: {
                  notEmpty: {
                    message: 'Seats are required',
                  },
                  integer: {
                    message: 'Seats must be an integer',
                  },
                  callback: {
                    message: 'Seats must be at least 1',
                    callback: function(input) {
                      var value = parseInt(input.value, 10);
                      if (input.value) {
                        return value >= 1;
                      }
                      return true
                    }
                  },
                },
              },
              'details': {
                validators: {
                  notEmpty: {
                    message: 'Details are required',
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
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;
                var formData = new FormData(form);

                // Append CSRF token to the headers
                formData.append(
                  "_token",
                  document.querySelector('meta[name="csrf-token"]').content
                );

                $.ajax({
                  url: "/dashboard/events/store", // Update with your actual API endpoint
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    Swal.fire({
                      text: response.message,
                      icon: "success",
                      buttonsStyling: false,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    }).then(function(result) {
                      location.reload();
                    });
                  },
                  error: function(xhr, status, error) {
                    var errorMessage =
                      "Oops! Something went wrong. Please try again."; // Default error message
                    // Check if the error response contains validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                      // If there are validation errors, construct the error message
                      var errors = xhr.responseJSON.errors;
                      errorMessage = Object.values(errors).flat().join("<br>");
                    }
                    // Display the error message using SweetAlert
                    Swal.fire({
                      html: errorMessage,
                      icon: "error",
                      buttonsStyling: false,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    });
                  },
                  complete: function() {
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;
                  },
                });
              } else {
                Swal.fire({
                  text: "Sorry, looks like there are some errors detected, please try again.",
                  icon: "error",
                  buttonsStyling: false,
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
            var blankImageUrl = '{{ asset('assets/media/svg/files/blank-image-dark.svg') }}';
            $('.image-input-wrapper').css('background-image', 'url(' + blankImageUrl + ')');
          });
        },
      };
    })();

    // Initialize the script on page load
    KTUtil.onDOMContentLoaded(function() {
      KTAddEvent.init();
    });
  </script>
  <script>
    $(".area-status-checkbox").change(function() {
      // Get the item ID from the data attribute
      var eventId = $(this).data("event-id");

      // Save the reference to $(this) in a variable for later use
      var $checkbox = $(this);

      // Make an AJAX request to update the status
      $.ajax({
        url: "/dashboard/update-event-status/" + eventId, // Replace with your actual route
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
                l = new Date(moment($(t[2]).text(), "DD-MM-YYYY")),
                u = new Date(moment($(t[2]).text(), "DD-MM-YYYY"));
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
                altFormat: "d-m-Y",
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
                '[data-kt-ecommerce-order-filter="category"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(1).search(n).draw();
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
      $(document).on('click', '.kt_edit_event_toggle', function(e) {
        e.preventDefault();

        var eventId = $(this).data('event-id');

        $.ajax({
          url: '/dashboard/events/' + eventId + '/edit', // Updated URL to use RESTful route
          type: 'GET',
          success: function(response) {
            $('#kt_edit_event').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            var errorMessage = 'Error fetching play area data.';
            if (xhr.status === 404) {
              errorMessage = 'Play area not found.';
            }
            console.error(errorMessage);
            Swal.fire({
              text: errorMessage,
              icon: 'error',
              buttonsStyling: false,
              confirmButtonText: 'Ok, got it!',
              customClass: {
                confirmButton: 'btn btn-primary',
              },
            });
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.kt_event_bookings_toggle', function(e) {
        e.preventDefault();

        var eventId = $(this).data('event-id');

        $.ajax({
          url: '/dashboard/events/' + eventId + '/attendees', // Updated URL to use RESTful route
          type: 'GET',
          success: function(response) {
            $('#kt_event_bookings').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            var errorMessage = 'Error fetching play area data.';
            if (xhr.status === 404) {
              errorMessage = 'Play area not found.';
            }
            console.error(errorMessage);
            Swal.fire({
              text: errorMessage,
              icon: 'error',
              buttonsStyling: false,
              confirmButtonText: 'Ok, got it!',
              customClass: {
                confirmButton: 'btn btn-primary',
              },
            });
          }
        });
      });
    });
  </script>
@endsection
