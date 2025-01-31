<style>
  div.table-responsive>div.dataTables_wrapper>div.row>div[class^=col-]:first-child {
    padding-left: 20px;
  }
</style>
<div class="card w-100 border-0 rounded-0">
  <!--begin::Card header-->
  <div class="card-header pe-5">
    <!--begin::Title-->
    <div class="card-title">
      <!--begin::User-->
      <div class="d-flex justify-content-center flex-column me-3">
        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{ $customer->name }}</a>
        <!--begin::Info-->
        <div class="mb-0 lh-1">
          <div class="d-flex flex-wrap flex-grow-1 flex-center text-center">
            <div>
              <span class="text-success fs-7">Orders</span>
              <span class="text-gray-800 d-block fs-6">{{ $customer->orders->count() }}</span>
            </div>
            <div class="mx-5">
              <span class="text-danger fs-7">Spend</span>
              <span class="text-gray-800 d-block fs-6">₹{{ $totalExpenses }}</span>
            </div>
            <div>
              <span class="text-warning fs-7">Reviews</span>
              <span class="text-gray-800 d-block fs-6">{{ $customer->ratings->count() }}</span>
            </div>
          </div>
        </div>
        <!--end::Info-->
      </div>
      <!--end::User-->
    </div>
    <!--end::Title-->
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
      <!--begin::Close-->
      <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_customer_close">
        <i class="ki-duotone ki-cross-square fs-2">
          <span class="path1"></span>
          <span class="path2"></span>
        </i>
      </div>
      <!--end::Close-->
    </div>
    <!--end::Card toolbar-->
  </div>
  <!--end::Card header-->
  <!--begin::Card body-->
  <div class="card-body">
    <!--begin::Customer Update-->
    <div class="card shadow-sm card-flush my-3">
      <div class="card-header collapsible cursor-pointer rotate my-1" data-bs-toggle="collapse"
        data-bs-target="#kt_update_collapsible">
        <h3 class="card-title">Update {{ $customer->name }}</h3>
        <div class="card-toolbar rotate-180">
          <i class="ki-duotone ki-down fs-1"></i>
        </div>
      </div>
      <div id="kt_update_collapsible" class="collapse">
        <form id="kt_modal_update_user_form" action="{{ route('update.customerWithPoints', $customer->id) }}"
          method="POST">
          <div class="card-body p-5">
            <div class="row g-9 mb-7">
              <!--begin::Col-->
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Name</label>
                <input class="form-control form-control-solid" placeholder="Your Name" name="name"
                  value="{{ $customer->name }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="fv-row col-md-6 fv-plugins-icon-container">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold mb-2">
                  <span class="required">Date of Birth</span>
                </label>
                <!--end::Label-->

                <!--begin::Input-->
                <div class="position-relative d-flex align-items-center">
                  <!--begin::Icon-->
                  <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                      class="path2"></span><span class="path3"></span><span class="path4"></span><span
                      class="path5"></span><span class="path6"></span></i>
                  <!--end::Icon-->
                  <input
                    class="form-control kt_datepicker_dob_update_customer form-control-solid ps-12 flatpickr-input @error('date_of_birth') is-invalid @enderror"
                    placeholder="Select a date" name="date_of_birth" type="text" readonly="readonly"
                    value="{{ $customer->customer->date_of_birth }}">
                </div>
                @error('date_of_birth')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <!--end::Input-->

              </div>
              <!--end::Col-->
            </div>
            <div class="row g-9 mb-7">
              <!--begin::Col-->
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Contact No.</label>
                <input id="phone1" class="form-control form-control-solid" placeholder="Your Contact No."
                  name="phone" value="{{ $customer->phone }}">
                @error('phone')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Email</label>
                <input class="form-control form-control-solid" placeholder="Your Email" name="email"
                  value="{{ $customer->email }}">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <!--end::Col-->
            </div>
            <div class="row g-9 mb-7">
              <!--begin::Col-->
              <div class="col-12 fv-row">
                @php
                  $RedeemedPoints = App\Models\CustomerPointLog::where('user_id', $customer->id)
                      ->where('action', 'Redeem')
                      ->sum('points');

                  $PointsEarned = App\Models\CustomerPointLog::where('user_id', $customer->id)
                      ->where('action', '!=', 'Redeem')
                      ->where('action', '!=', 'Penalty')
                      ->sum('points');
                @endphp
                <!--begin::Label-->
                <label class="fs-6 fw-semibold mb-2">Points</label>
                <!--begin::Badges-->
                <div class="badge badge-light-warning" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" title=" Total Earn">{{ $PointsEarned }}</div>
                <!--end::Badges-->
                <!--begin::Badges-->
                <div class="badge badge-light-danger" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" title="Redeemed">{{ $RedeemedPoints }}</div>
                <!--end::Badges-->
                <!--begin::Badges-->
                <div class="badge badge-light-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" title="Balance">{{ $customer->point->points ?? 0 }}</div>
                <!--end::Badges-->
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-solid" placeholder="Points" name="points" value="0">
                @error('points')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <!--end::Input-->
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-12 fv-row">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold mb-2">Point Alert Message</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-solid" placeholder="Reason for point credit" name="message"
                  value="">
                <!--end::Input-->
              </div>
              <!--end::Col-->
            </div>
          </div>
          <div class="card-footer text-end py-2">
            <button class="btn btn-primary" id="kt_modal_update_user_submit" type="submit">Update</button>
          </div>
        </form>
      </div>
    </div>
    <!--end::Customer Update-->
    <!--begin::Points Log--->
    <div class="card shadow-sm card-flush my-3">
      <!--begin::Card header-->
      <div class="card-header collapsible cursor-pointer rotate my-1" data-bs-toggle="collapse"
        data-bs-target="#kt_points_log_collapsible">
        <!--begin::Title-->
        <div class="card-title">
          <!--begin::User-->
          <div class="d-flex justify-content-center flex-column me-3">
            <a href="#" class="fs-6 fw-bold text-gray-900 mb-2 lh-1">Points Log</a>
            <!--begin::Info-->
            <div class="mb-0 lh-1">
              <!--begin::Search-->
              <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
                <input type="text" data-kt-customer-table-filter="search"
                  class="form-control form-control-solid w-200px ps-13" placeholder="Search Logs" />
              </div>
              <!--end::Search-->
            </div>
            <!--end::Info-->
          </div>
          <!--end::User-->
        </div>
        <!--end::Title-->
        <div class="card-toolbar">
          <h3 class="card-title align-items-center flex-column">
            <span class="card-label fw-bold fs-3 mb-1">{{ $customer->pointLogs->count() }}</span>
            <span class="text-muted fw-semibold fs-7">Total Logs</span>
          </h3>
          <i class="ki-duotone ki-down fs-1 rotate-180"></i>
        </div>
      </div>
      <!--end::Card header-->
      <div id="kt_points_log_collapsible" class="collapse">
        <div class="card-body p-0">
          <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
              <thead>
                <tr class="text-center text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-125px text-center">Points For</th>
                  <th class="min-w-125px text-center">Date</th>
                  <th class="min-w-125px text-center">Points</th>
                </tr>
              </thead>
              <!--begain::Table body-->
              <tbody class="fw-semibold text-gray-600">
                @foreach ($customer->pointLogs()->latest()->get() as $log)
                  <tr class="text-center">
                    <td>{{ $log->action }}</td>
                    <td data-order="{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y') }}">
                      {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y') }}</td>
                    <td>
                      @if ($log->action == 'Redeem' || $log->action == 'Penalty')
                        -{{ $log->points }}
                      @else
                        +{{ $log->points }}
                      @endif
                    </td>
                @endforeach
              </tbody>
              <!--end::Table body-->
            </table>
            <!--end::Table-->
          </div>
        </div>
      </div>
    </div>
    <!--end::Points Log-->
  </div>
  <!--end::Card body-->
</div>
<script>
  "use strict";
  var KTAppPointsLogListing = (function() {
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
              u = new Date(moment($(t[6]).text(), "DD/MM/YYYY"));
            return (
              (null === a && null === c) ||
              (null === a && c >= u) ||
              (a <= l && null === c) ||
              (a <= l && c >= u)
            );
          }),
          t.draw();
      },
      c = () => {
        e.querySelectorAll(
          '[data-kt-ecommerce-order-filter="delete_row"]'
        ).forEach((e) => {
          e.addEventListener("click", function(e) {
            e.preventDefault();
            const n = e.target.closest("tr"),
              r = n.querySelector(
                '[data-kt-ecommerce-order-filter="order_id"]'
              ).innerText;
            Swal.fire({
              text: "Are you sure you want to delete order: " + r + "?",
              icon: "warning",
              showCancelButton: !0,
              buttonsStyling: !1,
              confirmButtonText: "Yes, delete!",
              cancelButtonText: "No, cancel",
              customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary",
              },
            }).then(function(e) {
              e.value ?
                Swal.fire({
                  text: "You have deleted " + r + "!.",
                  icon: "success",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                  },
                }).then(function() {
                  t.row($(n)).remove().draw();
                }) :
                "cancel" === e.dismiss &&
                Swal.fire({
                  text: r + " was not deleted.",
                  icon: "error",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                  },
                });
            });
          });
        });
      };
    return {
      init: function() {
        (e = document.querySelector("#kt_customers_table")) &&
        ((t = $(e).DataTable({
            info: !1,
            order: [],
            pageLength: 10,
            columnDefs: [{
              orderable: !1,
              targets: 0
            }],
          })).on("draw", function() {
            c();
          }),
          (() => {
            const e = document.querySelector(
              "#kt_ecommerce_sales_flatpickr"
            );
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
          (() => {
            const e = document.querySelector(
              '[data-kt-ecommerce-order-filter="status"]'
            );
            $(e).on("change", (e) => {
              let n = e.target.value;
              "all" === n && (n = ""), t.column(3).search(n).draw();
            });
          })(),
          c(),
          document
          .querySelector("#kt_ecommerce_sales_flatpickr_clear")
          .addEventListener("click", (e) => {
            n.clear();
          }));
      },
    };
  })();
  KTUtil.onDOMContentLoaded(function() {
    KTAppPointsLogListing.init();
  });
</script>
<script>
  var input1 = document.querySelector("#phone1");
  var iti = window.intlTelInput(input1, {
    utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
    separateDialCode: true,
    initialCountry: "auto",
    onlyCountries: ["bd", "in"],
    initialCountry: "bd",
  });
  $(".kt_datepicker_dob_update_customer").flatpickr();
</script>
<script>
  var KTUpdateCustomer = (function() {
    var form, submitButton;

    return {
      init: function() {
        form = document.querySelector("#kt_modal_update_user_form");
        submitButton = document.querySelector("#kt_modal_update_user_submit");

        // Initialize FormValidation
        var fv = FormValidation.formValidation(form, {
          fields: {
            name: {
              validators: {
                notEmpty: {
                  message: "Name is required",
                },
              },
            },
            date_of_birth: {
              validators: {
                notEmpty: {
                  message: "Date of Birth is required",
                },
              },
            },
            phone: {
              validators: {
                callback: {
                  message: "Invalid phone number",
                  callback: function(input) {
                    // Get the phone number value
                    var phoneNumber = input.value;

                    // Validate the phone number format using intl-tel-input
                    var isValidPhoneNumber = iti.isValidNumber();

                    return isValidPhoneNumber;
                  }
                }
              },
            },
            points: {
              validators: {
                notEmpty: {
                  message: "Points field is required",
                },
                numeric: {
                  message: "The value must be a number",
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

          fv.validate().then(function(status) {
            if (status === "Valid") {
              // Form is valid, prepare data for AJAX request
              var formData = new FormData(form);

              // Append CSRF token to the form data
              formData.append(
                "_token",
                document.querySelector('meta[name="csrf-token"]').content
              );
              formData.set("phone", iti.getNumber());
              // Perform AJAX request to update customer
              $.ajax({
                url: form.getAttribute("action"), // Update URL with your endpoint
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                  console.log(response.success);
                  if (response.success) {
                    Swal.fire({
                      text: "Customer has been updated successfully!",
                      icon: "success",
                      buttonsStyling: false,
                      confirmButtonText: "Ok",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    }).then(function(result) {
                      if (result.isConfirmed) {
                        // Optionally handle UI updates or close modal/drawer
                        location.reload(); // Reload the page or update UI as needed
                      }
                    });
                  }
                  // Show success message and handle UI updates

                },
                error: function(error) {
                  // Handle AJAX request error and show error message
                  var errors = error.responseJSON.errors;
                  console.log(errors);
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
              });
            } else {
              // Form validation failed, show error message
              Swal.fire({
                text: "Please fill in all required fields correctly.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
            }
          });
        });
      },
    };
  })();

  // Initialize the script on page load
  KTUtil.onDOMContentLoaded(function() {
    KTUpdateCustomer.init();
  });
</script>
